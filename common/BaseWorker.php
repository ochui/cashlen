<?php


namespace app\common;


use app\models\activerecord\BackgroundTasks;
use yii\base\BaseObject;
use yii\queue\JobInterface;

abstract class BaseWorker extends BaseObject implements JobInterface
{
    /** @var BackgroundTasks $task */
    public $task;
}
