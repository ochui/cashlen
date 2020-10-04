<?php

namespace app\background;

use app\common\Constants;
use app\common\Helper;
use app\common\SystemLog;
use app\models\activerecord\Videos;
use Mailgun\Mailgun;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class UpdateVideoInfo extends BaseObject implements JobInterface{

    /**
     * @var Videos $videoModel
    */

    public $videoModel;
    public $tried = 0;
    public $max_try = 10;

    /**
     * @param \yii\queue\Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        if($this->tried>=$this->max_try){
            SystemLog::log(Constants::USER_ADMINISTRATOR,'Video info not found, stopped after max tried '.$this->max_try." times.",Constants::LOG_TYPE_VIDEO_UPLOAD,$this->videoModel->id);
            echo 'Finished due to max tried.';die;
        }
        $this->videoModel->updateVideoInfo(false,$this->tried);
    }

}
