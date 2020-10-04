<?php


namespace app\common\exceptions;


use app\common\Constants;
use app\common\SystemLog;
use Exception;

class LoggableException extends Exception
{

    public $errorInfo = [];

    public $log_type = Constants::LOG_TYPE_ERROR_EXCEPTION;
    public function __construct($message, $errorInfo = [], $code = 0, Exception $previous = null)
    {
        SystemLog::log(Constants::USER_ADMINISTRATOR,
            $message,$this->log_type,
            json_encode($this->errorInfo));

        parent::__construct($message, $code, $previous);
    }

}
