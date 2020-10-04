<?php


namespace app\common;


use app\models\activerecord\BackgroundTasks;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

abstract class RetryableWorker extends BaseObject implements JobInterface
{
    const EXIT_STATUS_OK = 1;
    const EXIT_STATUS_FAILED = 2;
    const EXIT_STATUS_RETRY = 3;

    public $retry_count = 5;
    public $retry_delay = 25;//in seconds

    protected $exit_status;
    protected $exit_response;

    /** @var Queue $queue */
    protected $queue;

    /** @var BackgroundTasks $task */
    public $task;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue)
    {

        $this->queue = $queue;
        $this->task->attempts = $this->task->attempts + 1;

        echo 'Running ServerOperationWorker task type '.
            $this->task->type.' and attempt '.$this->task->attempts. PHP_EOL;

        $this->run();
        $this->checkOrRetry();

        echo 'Run completed of ServerOperationWorker task type '.
            $this->task->type.' and attempt '.$this->task->attempts. PHP_EOL;
    }

    protected function checkOrRetry(){
        if($this->exit_status != self::EXIT_STATUS_OK){
            //check retries
            if($this->task->attempts < $this->retry_count
                && $this->exit_status == self::EXIT_STATUS_RETRY
            ){
                //retry this one
                $this->queue->delay($this->retry_delay)
                    ->push($this);
            }

        }
        $this->task->response = $this->exit_response;
        $this->task->save();
    }

    abstract protected function run();

}
