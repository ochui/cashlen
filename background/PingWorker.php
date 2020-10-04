<?php


namespace app\background;


use app\components\Constants;
use app\components\Helper;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class PingWorker extends BaseObject implements JobInterface
{

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function execute($queue)
    {
        $ping = Helper::getSetting(Constants::SETTINGS_BACKGROUND_SERVER_PING);
        if($ping == null)
            return false;

        //update setting
        $ping->value = date(Constants::PHP_DATE_FORMAT);
        $ping->update(false,['value']);

        return true;

    }
}
