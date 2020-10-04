<?php
/**
 * Created by PhpStorm.
 * User: prabhjyot
 * Date: 2019-01-30
 * Time: 22:46
 */

namespace app\common;

use app\common\Constants;
use app\common\SystemLog;
use Yii;
use yii\base\ErrorException;
use yii\web\ErrorAction;


class LogClass extends ErrorAction
{
    public $api_key;
    public $project_id=null;

    public $base_url = "https://crm.teamoxio.com/log/";
    private $endpoints = array(
        "write"=>"write",
    );

    private $error_object;

    private $error_id=null;

    public $debug = false;

    public $layout=null;
    public $theme=null;


    /**
     * Runs the action.
     *
     * @return string result content
     */
    public function run()
    {
        if($this->project_id == null){
            throw new ErrorException("LogClass component isn't configured properly. Need api_key and project_id as configuration parameters.");
            exit();
        }

        if($this->theme!=null){
            Yii::$app->view->theme = $this->theme;
        }

        if ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }

        Yii::$app->getResponse()->setStatusCodeByException($this->exception);


        if($this->debug==false){
            $e = $this->exception;

            $message = "Type: " . get_class( $e ) . "; Message: {$e->getMessage()}; File: {$e->getFile()}; Line: {$e->getLine()};";

            $this->error_object['timestamp'] = time();
            $this->error_object['datestamp'] = date("Y-m-d H:i:s");
            $this->error_object['timezone'] = date_default_timezone_get();
            $this->error_object['description'] = $message;

            $this->logError();


        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return $this->renderAjaxResponse();
        }

        return $this->renderHtmlResponse();

    }

    protected function renderAjaxResponse()
    {
        if($this->error_id!=null) {

            if($this->debug==false)
                return json_encode(
                    array("status" => "error",
                        "message" => "An error has occurred, we have been informed about the same. Use reference id: <strong>" . $this->error_id . "</strong> to address this case with our support team.",
                        "error_id"=>$this->error_id

                    )
                );
            else{
                return json_encode(array("status"=>"error","message"=>$this->getExceptionName() . ': ' . $this->getExceptionMessage()));


            }
        }
        else{
            if($this->debug==false)
                return json_encode(
                    array("status" => "error",
                        "message" => "An error has occurred, we have been informed about the same. "
                    )
                );
            else{
                return json_encode(array("status"=>"error","message"=>$this->getExceptionName() . ': ' . $this->getExceptionMessage()));

            }

        }


    }


    protected function renderHtmlResponse()
    {

        if($this->debug == true)
            return $this->controller->render($this->view ?: $this->id, $this->getViewRenderParams());
        else{

            $message = "An error has occurred, we have been informed about the same. ";

            if($this->error_id!=null) {
                $message = "An error has occurred, we have been informed about the same. Use reference id: <strong>" . $this->error_id . "</strong> to address this case with our support team.";
            }

            $this->defaultMessage = $message;

            return Yii::$app->controller->render($this->view ?: $this->id, $this->getViewRenderParams());

        }
    }


    protected function logError(){

        SystemLog::log(
            Constants::USER_ADMINISTRATOR,
            $this->error_object['description'],
            Constants::LOG_TYPE_ERROR,
            null,
            json_encode($this->error_object)
        );

        if($this->project_id>0){
            $this->error_object['project_id'] = $this->project_id;

            $this->error_object['api_key'] = $this->api_key;

            $ch = curl_init($this->base_url.$this->endpoints['write']);
            curl_setopt_array($ch,array(
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_SSL_VERIFYHOST=>false,
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_TIMEOUT=>5,
                CURLOPT_POST=>true,
                CURLOPT_POSTFIELDS=>http_build_query($this->error_object)
            ));


            $response = curl_exec($ch);

            if($response){
                $response = json_decode($response);

                if($response->status == "success"){
                    if($response->log_id)
                        $this->error_id = $response->log_id;
                }

            }
        }


    }

}

