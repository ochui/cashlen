<?php


namespace app\common\exceptions;

use app\common\BaseActiveRecord;
use app\common\Constants;
use Exception;

class PersistException extends LoggableException
{
    /** @var BaseActiveRecord $model */
    public $model;

    public function __construct($model, $errorInfo = [], $code = 500, Exception $previous = null)
    {

        $this->model = $model;

        $this->errorInfo = $this->model->getErrors();
        $message = 'Error while saving object '.get_class($this->model);

        $this->log_type = Constants::LOG_TYPE_ERROR_PERSIST;



        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Persist Exception - '.get_class($this->model) ;
    }

    public function getErrors(){
        return $this->errorInfo;
    }
}
