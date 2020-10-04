<?php

namespace app\components;


use app\models\Currencies;
use app\models\Users;
use Yii;

class BaseController extends \yii\web\Controller
{
    /**
     * @var Users|null $identity
    */
    public $identity = null;

    public function afterAction($action,$result)
    {
        \Yii::$app->user->setReturnUrl(
            [\Yii::$app->controller->id."/".$action->id]
        );

        return parent::afterAction($action,$result);
    }

    public function beforeAction($action)
    {
        $this->identity = \Yii::$app->user->identity;
        if($this->identity && Yii::$app->session->has('login_two_fa')){
            if($action->id != 'verify-twofa' && $action->id != 'logout'){
                return $this->redirectTo(['user/verify-twofa']);
            }
        }
        return parent::beforeAction($action);
    }

    protected function flash($type,$message, $translate=true){
        if($translate) {
            $message = Yii::t('app', $message);
        }
        \Yii::$app->session->setFlash($type,$message);
    }

    protected function redirectTo($url){
        return $this->redirect($url)->send();
    }
}
