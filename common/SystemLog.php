<?php


namespace app\common;


use app\models\activerecord\Logs;
use Yii;

class SystemLog
{
    public static function log($user_id,
                               $particulars,
                               $type,
                               $parent_id = null,
                               $data = null,
                               $parent_log_id = null
    )
    {
        $log = new Logs();
        if ($parent_log_id) {
            $log = Logs::findOne($parent_log_id);
            $log->particulars .= $particulars;
        } else {
            $log->particulars = $particulars;
        }

        $log->user_id = $user_id;
        $log->type = $type;
        $log->data = $data;
        $log->parent_id = $parent_id;

        if (Yii::$app instanceof Yii\console\Application) {
            $log->ip = "::1";
            $log->useragent = "console";
        } else {
            $log->ip = Yii::$app->request->getUserIP();
            $log->useragent = Yii::$app->request->getUserAgent();
            $log->country_id = Helper::getCountryIDFromIP($log->ip);
        }

        if ($log->save())
            return $log->id;
        else {
            return false;
        }
    }
}
