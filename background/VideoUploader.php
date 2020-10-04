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

class VideoUploader extends BaseObject implements JobInterface{

    /**
     * @var Videos $videoModel
    */

    public $videoModel;

    /**
     * @param \yii\queue\Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        echo "START -> Sending video to cloudflare ".$this->videoModel->title.PHP_EOL;
        $this->videoModel->uploadToCloudflare();
        echo "FINISH -> Sending video to cloudflare ".$this->videoModel->title.PHP_EOL;
    }

}
