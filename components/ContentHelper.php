<?php


namespace app\components;


use app\background\EmailSender;
use app\models\Countries;
use app\models\Currencies;
use app\models\Ranks;
use app\models\Settings;
use app\models\UserBinaryCycles;
use app\models\UserInvestments;
use app\models\UserOtp;
use app\models\Users;
use app\models\UserTrades;
use app\models\UserTransactions;
use DateTime;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class ContentHelper
{
    public static function getWeekRoiRepresentingBoxes()
    {
        /**
         * @var Users $identity
         */
        $identity = Yii::$app->user->identity;
        $html = '';
        $weekDates = Helper::getCurrentWeekDates();
        if ($weekDates != null) {
            foreach ($weekDates as $weekDate) {
                $class = "bg-light";

                //check if any roi paid in this day
                $dayStart = date(Constants::PHP_DATE_FORMAT_SHORT, $weekDate) . " 00:00:00";
                $dayEnd = date(Constants::PHP_DATE_FORMAT_SHORT, $weekDate) . " 23:59:59";

                $getUserTransaction = $identity->getUserTransactions()->where([
                    'type_id' => Constants::TRANSACTION_TYPE_ROI_INCOME
                ])
                    ->andWhere(['>=','time',$dayStart])
                    ->andWhere(['<=','time',$dayEnd])
                    ->one();

                if($getUserTransaction!=null){
                    $class = "bg-primary";
                }

                $html .= '<div class="w-6 h-6 ' . $class . ' mr-4 br-7"></div>';
            }
        }
        return $html;
    }

}
