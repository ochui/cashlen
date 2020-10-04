<?php

namespace app\base\controllers;

use app\common\Constants;
use app\common\Helper;
use app\models\activerecord\Users;
use Yii;

class ProtectedController extends BaseController
{
    /**
     * @var Users $identity
     */
    public $identity;

    /**
     * @var array $TWO_FA_ACTIONS - add names of actions here
     * and the system will enforce 2FA verification on those actions
     */

    public $TWO_FA_ACTIONS = array('profile', 'verify-twofa', 'disable-twofactor','withdraw','update-password','profile');

    //array of controller names if entered like controller/action users/index then considered as full route as checked accordingly
    public $ADMIN_ROUTES = ['admin','settings','categories','videos'];


    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirectTo(['auth/login']);
        }

        $this->identity = \Yii::$app->user->identity;

        if(!$this->identity->isSessionValid()) {
            return $this->redirect(['auth/login'])->send();
        }

        if ($this->identity->two_fa_secret != null) {
            Yii::$app->session->set('two_fa_secret', $this->identity->two_fa_secret);
        }

        if (in_array($action->id, $this->TWO_FA_ACTIONS)) {
            if (Yii::$app->request->isPost) {
                if ($this->identity->is_two_fa == Constants::YES_FLAG || isset($_POST['two_fa_code'])) {

                    $isValid = false;

                    if (isset($_POST['two_fa_code']) && Yii::$app->session->has('two_fa_secret')) {
                        $this->identity->two_fa_secret = Yii::$app->session->get('two_fa_secret');

                        if (trim($_POST['two_fa_code']) == '') {
                            $this->flash('error', '2fa code cannot be empty');
                        } else {
                            $response = $this->identity->verify2FA($_POST['two_fa_code']);
                            if (!$response) {
                                $this->flash('error', 'Invalid 2fa code');
                            } else {
                                $isValid = true;
                            }
                        }
                    }else{
                        $this->flash('error', '2fa code cannot be empty');
                    }

                    if (!$isValid) {
                        if (isset($_POST['two_fa_fail_redirect'])) {
                            return $this->redirectTo($_POST['two_fa_fail_redirect']);
                        }
                        return $this->redirectTo(Yii::$app->getRequest()->getUrl());
                    }
                }
            }
        }

        $this->verifyAdminRoutes();

        return parent::beforeAction($action);
    }

    private function verifyAdminRoutes()
    {
        $currentController = Yii::$app->controller->id;
        $currentAction = Yii::$app->controller->action->id;
        $fullPath = $currentController."/".$currentAction;
        if(in_array($currentController,$this->ADMIN_ROUTES)){
            $this->checkAdminAccess();
        }else{
            if(in_array($fullPath,$this->ADMIN_ROUTES)){
                $this->checkAdminAccess();
            }
        }
    }

    private function checkAdminAccess()
    {
        if($this->identity->role_id!=Constants::USER_ROLE_ADMIN){
            //not admin
            return $this->redirectTo(['site/error']);
        }
    }



}
