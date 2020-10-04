<?php

namespace app\background;

use app\components\Constants;
use app\components\Helper;
use app\components\SystemLog;
use app\models\UserInvestment;
use app\models\UserInvestments;
use app\models\UserTransactions;

class RoiWorker extends \yii\base\BaseObject implements \yii\queue\JobInterface
{

    public $id;
    public $date = false;

    /**
     * @param \yii\queue\Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function execute($queue)
    {
        $connection = \Yii::$app->db;

        $currentTime = time();
        //$currentTime = strtotime("2020-06-01 19:01:07");

        //$currentTime = strtotime("2020-06-26 04:30:01");

        $to_credit = true;

        $investment = UserInvestments::findOne($this->id);

        echo "Distributing ROI for " . $investment->id . PHP_EOL;

        $user = $investment->user;
        $userRank = $user->rank;
        $userCycle = $user->getCurrentCycle();
        $userWallet = $user->getWallet(Constants::WALLET_TYPE_MAIN);

        if (!$user->isApplicableForROI()) {
            echo "User not applicable for ROI " . PHP_EOL;
            return false;
        }

        if ($investment->status_id != Constants::INVESTMENT_ACTIVE) {
            echo "Invest not active " . $investment->id . PHP_EOL;
            return false;
        }

        $bonus = round($investment->net_amount * ($userRank->roi_bonus / 100), Constants::CURRENCY_PRECISION);

        //get qualifying amount

        $bonus = $investment->user->getQualifyingAmount($bonus);

        if ($this->date == false) {
            $this->date = date("mdY", $currentTime);
        }

        $logData = json_encode([
            'user_wallet_id' => $userWallet->id,
            'user_cycle_id' => $userCycle->id,
            'user_id' => $user->id,
            'investment_id' => $investment->id,
            'date'=> $this->date,
            'bonus'=>$bonus
        ]);

        //check if not already credited

        $duplicate_check = UserTransactions::find()->where(['user_id' => $investment->user->id])
            ->andWhere(['data' => $investment->id . "-" . $this->date])
            ->andWhere(['type_id' => Constants::TRANSACTION_TYPE_ROI_INCOME])
            ->one();

        if ($duplicate_check == null
            && $investment->status_id == Constants::INVESTMENT_ACTIVE
        ) {

            $before_balance = $userWallet->balance;


            $particulars = "Investment " . $investment->identifier . " (" .
                Helper::printNumber($investment->net_amount) . ")";


            //credit the parent
            if ($to_credit) {

                $transaction = $connection->beginTransaction();

                try {

                    echo "Start Paying ROI " . $investment->id . PHP_EOL;

                    $userWallet->balance += $bonus;
                    if($userWallet->update(false, ['balance'])){

                        $userCycle->total_earnings += $bonus;
                        if($userCycle->update(false, ['total_earnings'])){

                            $investment->earnings += $bonus;
                            $investment->roi_income += $bonus;
                            if($investment->update(false, ['earnings', 'roi_income'])){

                                $addTransaction = $user->addTransaction(
                                    $particulars,
                                    Constants::TRANSACTION_TYPE_ROI_INCOME,
                                    Constants::TRANSACTION_IS_CREDIT,
                                    $bonus,
                                    $before_balance,
                                    $userWallet->id,
                                    $investment->id . "-" . $this->date,
                                    $investment->id
                                );

                                if ($addTransaction) {

                                    $transaction->commit();

                                    echo "ROI paid transaction id " . $addTransaction->id . " " . $investment->id . PHP_EOL;

                                    SystemLog::log(
                                        $user->id,
                                        $particulars,
                                        Constants::LOG_TYPE_USER_ROI_INCOME
                                    );

                                    echo $particulars . PHP_EOL;


                                }else{
                                    $transaction->rollback();
                                    SystemLog::log(
                                        null,
                                        'ROI-WORKER - Transaction not saved',
                                        Constants::LOG_TYPE_ERROR,
                                        $logData
                                    );
                                }

                            }else{
                                $transaction->rollback();
                                SystemLog::log(
                                    null,
                                    'ROI-WORKER - User Investment earning not update',
                                    Constants::LOG_TYPE_ERROR,
                                    $logData
                                );
                            }

                        }else{
                            $transaction->rollback();
                            SystemLog::log(
                                null,
                                'ROI-WORKER - User Cycle balance not update',
                                Constants::LOG_TYPE_ERROR,
                                $logData
                            );
                        }

                    }else{
                        $transaction->rollback();
                        SystemLog::log(
                            null,
                            'ROI-WORKER - User wallet balance not update',
                            Constants::LOG_TYPE_ERROR,
                            $logData
                        );
                    }

                } catch(\Exception $e) {
                    $transaction->rollback();

                    SystemLog::log(
                        null,
                        "ROI-WORKER - ".$e->getMessage(),
                        Constants::LOG_TYPE_ERROR,
                        $logData
                    );

                }

            }

        } else {
            echo "System tried to credit ROI income " . Helper::printNumber($bonus) . " to user twice for investment " . $investment->id . " for date " . $this->date . PHP_EOL;
            SystemLog::log(
                null,
                "System tried to credit ROI income " . Helper::printNumber($bonus) . " to user twice for investment " . $investment->identifier . " for date " . $this->date,
                Constants::LOG_TYPE_ERROR,
                $logData
            );
        }

    }



}
