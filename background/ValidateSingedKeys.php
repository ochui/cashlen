<?php

namespace app\background;

use app\common\Constants;
use app\common\Helper;
use app\common\SystemLog;
use app\components\Cloudflare;
use app\models\activerecord\Videos;
use Mailgun\Mailgun;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ValidateSingedKeys extends BaseObject implements JobInterface{

    public $videoModel;
    public $tried = 0;
    public $max_try = 2;

    /**
     * @param \yii\queue\Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {
        if($this->tried>=$this->max_try){
            echo 'Finished Validate Singed Keys due to max tried.';die;
        }

        echo "START -> validating singed keys ".PHP_EOL;

        $model = new Cloudflare();
        $model->validateSignedKeys($this->tried);

        echo "FINISH -> validating singed keys ".PHP_EOL;
    }

}
