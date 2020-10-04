<?php

namespace app\components;

use app\models\Users;
use Yii;

class ProtectedController extends BaseController
{
    /**
     * @var Users
     */
    public $identity;

    /**
     * @var array $TWO_FA_ACTIONS - add names of actions here
     * and the system will enforce 2FA verification on those actions
     */

    public $TWO_FA_ACTIONS = array('profile', 'verify-twofa', 'disable-twofactor','withdraw','update-btc-address','update-password','profile');

    //array of controller names if entered like controller/action users/index then considered as full route as checked accordingly
    public $ADMIN_ROUTES = ['admin','settings','user-transactions','ranks','withdrawals','user-investments','users'];
    public $SUPPORT_ROUTES = ['users', 'user-investments'];


    public $INVESTMENT_REQUIRED_ACTIONS = ['investments','claim-rank','ranks','withdraw','finances'];

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirectTo(['auth/index']);
        }

        $this->identity = \Yii::$app->user->identity;

        //set language not for admin
        if($this->identity->role_id!=Constants::USER_ROLE_ADMIN){
            $language = $this->identity->getUserLanguage();
            \Yii::$app->language = $language;
        }

        if(!$this->identity->isSessionValid()) {
            return $this->redirect(['auth/login'])->send();
        }

        if ($this->identity->two_fa_secret != null) {
            Yii::$app->session->set('two_fa_secret', $this->identity->two_fa_secret);
        }

        if (in_array($action->id, $this->TWO_FA_ACTIONS)) {
            if (Yii::$app->request->isPost) {
                if ($this->identity->is_two_fa == Constants::ENABLED_FLAG || isset($_POST['two_fa_code'])) {

                    $isValid = false;

                    if (isset($_POST['two_fa_code']) && Yii::$app->session->has('two_fa_secret')) {
                        $this->identity->two_fa_secret = Yii::$app->session->get('two_fa_secret');

                        if (trim($_POST['two_fa_code']) == '') {
                            $this->flash('error', Yii::t('app','2fa code cannot be empty'));
                        } else {
                            $response = $this->identity->verify2FA($_POST['two_fa_code']);
                            if (!$response) {
                                $this->flash('error', Yii::t('app',Yii::t('app','2FA code is invalid.')));
                            } else {
                                $isValid = true;
                            }
                        }
                    }else{
                        $this->flash('error', Yii::t('app','2fa code cannot be empty'));
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
        $this->verifyUserInvestmentRoutes();

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

    private function verifyInSupportRoutes()
    {
        if($this->identity->role_id==Constants::USER_ROLE_SUPPORT) {
            $currentController = Yii::$app->controller->id;
            $currentAction = Yii::$app->controller->action->id;
            $fullPath = $currentController . "/" . $currentAction;
            if (in_array($currentController, $this->SUPPORT_ROUTES)) {
                return true;
            } else {
                if (in_array($fullPath, $this->SUPPORT_ROUTES)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function checkAdminAccess()
    {
        if($this->identity->role_id!=Constants::USER_ROLE_ADMIN){
            //not admin
            $allowed = false;
            switch ($this->identity->role_id){
                case Constants::USER_ROLE_SUPPORT:
                    $allowed = $this->verifyInSupportRoutes();
                    break;
            }
            if(!$allowed) {
                return $this->redirectTo(['site/error']);
            }
        }
    }

    private function verifyUserInvestmentRoutes()
    {
        if($this->identity->role_id==Constants::USER_ROLE_USER){
            //if user
            $currentAction = Yii::$app->controller->action->id;
            if(in_array($currentAction,$this->INVESTMENT_REQUIRED_ACTIONS)){
                if(!$this->identity->userMenuEligible()){
                    $this->flash('error',Yii::t('app','You need to make investment to start further.'));
                    return $this->redirect(['app/index'])->send();
                }
            }
        }
    }


}
