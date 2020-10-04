<?php


namespace app\commands;

use app\background\EmailSenderNew;
use app\common\Constants;
use app\common\SystemLog;
use app\components\Cloudflare;
use app\models\activerecord\BackgroundTasks;
use app\models\activerecord\Files;
use app\models\activerecord\UserAttendance;
use app\models\activerecord\UserBillingCycles;
use app\models\activerecord\Users;
use app\models\activerecord\Videos;
use app\sockets\EchoServer;
use WebSocket\Client;
use yii\console\Controller;
use yii\console\ExitCode;


/**
 * This command can be used for running schedule tasks from command line using crontab
 *
 *
 * @author Prabhjyot Singh <prabhjyot@teamoxio.com>
 */
class CronController extends Controller
{

    public $date = null;

    public function actionDaily(){
        $this->date = date(Constants::PHP_DATE_FORMAT);
        $this->addAttendanceRow();
        $this->markAbsents();
    }

    public function addAttendanceRow(){
        /**
         * @var Users $employee
         */
        $allEmployees = Users::find()->where(['role_id'=>Constants::USER_ROLE_USER])->all();
        foreach ($allEmployees as $employee){
            $employee->getAttendanceOfDay($this->date);
        }
    }

    public function markAbsents()
    {
        /**
         * @var UserAttendance $userAttendance
         */
        $today = date(Constants::PHP_DATE_FORMAT_SHORT);
        $userAttendances = UserAttendance::find()->where(['status'=>Constants::ATTENDANCE_NOT_RECORDED])->all();
        if($userAttendances!=null){
            foreach ($userAttendances as $userAttendance){
                $dayDate = $userAttendance->day_date;
                if($dayDate<$today){
                    $userAttendance->status = Constants::ATTENDANCE_ABSENT;
                    $userAttendance->update(false, ['status']);
                }
            }
        }
    }
}
