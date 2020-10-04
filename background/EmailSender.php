<?php

namespace app\background;

use app\common\Constants;
use app\common\Helper;
use Mailgun\Mailgun;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class EmailSender extends BaseObject implements JobInterface{

    public $to;
    public $subject;
    public $message;
    public $from=null;

    /**
     * @param \yii\queue\Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        echo "Sending email ".$this->subject." to ".$this->to.PHP_EOL;
        Helper::sendEmailCall($this->from, $this->to, $this->subject, $this->message);
    }

}
