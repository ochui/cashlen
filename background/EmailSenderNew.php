<?php


namespace app\background;


use app\common\Helper;
use app\common\RetryableWorker;
use PHPUnit\TextUI\Help;

class EmailSenderNew extends RetryableWorker
{

    protected function run()
    {
        $response = Helper::sendEmail();
        if(!$response){
            $this->exit_status = self::EXIT_STATUS_RETRY;
        }
        $this->exit_status = self::EXIT_STATUS_OK;
    }
}