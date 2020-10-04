<?php


namespace app\controllers;


use app\common\Constants;
use app\common\Helper;
use app\base\controllers\ProtectedController;
use app\common\SystemLog;
use app\models\activerecord\UserParentsDetails;
use app\models\activerecord\Users;
use Yii;

class UserController extends ProtectedController
{
    public function actionIndex(){
        $identity = $this->identity;
        if($identity->role_id==Constants::USER_ROLE_ADMIN){
            return $this->redirect(['admin/index'])->send();
        }elseif($identity->role_id==Constants::USER_ROLE_USER){
            return $this->redirect(['app/index'])->send();
        }else{
            Yii::$app->user->logout();
            $this->flash('error','Invalid details.');
            return $this->redirect(['auth/login'])->send();
        }
    }


    public function actionVerifyTwofa(){
        $this->layout = "authLayout";
        $this->view->title = "Verify to continue";
        if(isset($_POST['verified']) && $_POST['verified']=='ok'){
            Yii::$app->session->remove('login_two_fa');
            return $this->redirectTo(['user/index']);
        }
        return $this->render('verify_two_fa');
    }

    public function actionProfile(){
        $this->view->title = "Update profile";
        $identity = $this->identity;
        $model = $identity;
        if($model->load(Yii::$app->request->post())){

            $model->mobile_no = trim($model->mobile_no);

            $hasError = false;

            if($model->mobile_no!=null) {
                $findMobileNo = Users::find()->where(['mobile_no' => $model->mobile_no])->andWhere(['!=', 'id', $identity->id])->one();
                if ($findMobileNo != null) {
                    $model->addError('mobile_no','This mobile no. belongs to other student.');
                    $hasError = true;
                }
            }

            if(!$hasError) {

                $identity->first_name = $model->first_name;
                $identity->gender = $model->gender;
                $identity->last_name = $model->last_name;
                $identity->last_name = $model->last_name;
                $identity->mobile_no = $model->mobile_no;

                $identity->update(false, ['first_name', 'last_name', 'gender', 'mobile_no']);

                SystemLog::log($identity->id, 'Profile has been update', Constants::LOG_TYPE_USER_PROFILE_UPDATE);
                $this->flash('success', 'Profile has been updated.');

            }
        }

        $changePasswordModel = new Users(['scenario'=>Users::SCENARIO_CHANGE_PASSWORD]);
        $twoFactorModel = new Users();
        $twoFactorModel->scenario = Users::SCENARIO_TWO_FACTOR;
        $secretKey = Helper::getGoogleSecretKey();

        return $this->render('update_profile',compact('model','identity','changePasswordModel','secretKey','twoFactorModel'));
    }


    public function actionEnableTwofactor(){
        $session = Yii::$app->session;
        $identity = $this->identity;

        if (!$session->has('google_secret')){
            return $this->redirect(['user/profile'])->send();
        }

        $googleSecret = $session->get('google_secret');
        $this->view->title = "Validating Code...";
        $model = new Users();
        $model->scenario = Users::SCENARIO_TWO_FACTOR;

        if(isset($_POST['Users']['auth_key'])){
            $model->auth_key = $_POST['Users']['auth_key'];
        }

        if($model->load(Yii::$app->request->post())){

            if ($model->auth_key != "" && is_numeric($model->auth_key)) {

                $identity->two_fa_secret = $googleSecret['code'];
                if ($identity->verify2FA($model->auth_key)) {

                    //insert into db
                    $model = $identity;
                    $model->two_fa_secret = $googleSecret['code'];
                    $model->is_two_fa = Constants::YES_FLAG;

                    if ($model->update(false, ['two_fa_secret', 'is_two_fa'])) {

                        SystemLog::log(
                            $model->id,
                            'Two Factor authentication enabled from ' . Yii::$app->request->getUserIP() . " using device " . Yii::$app->request->getUserAgent(),
                            Constants::LOG_TYPE_USER_TWO_FACTOR_AUTHENTICATION
                        );

                        $session->remove('google_secret');

                        $this->flash('success', 'Two factor authentication enabled successfully.');

                    } else {
                        $this->flash('error', 'Failed to enable two factor authentication.');
                    }

                } else {
                    $this->flash('error', 'Invalid Google Authentication Code');
                }
            } else {
                $this->flash('error', 'Invalid Google Authentication Code');
            }

        }
        return $this->redirect(['user/profile'])->send();
    }

    public function actionDisableTwofactor(){
        $this->view->title = "Disabling Two Factor Authentication...";
        $identity = $this->identity;
        $model = $identity;

        if($identity->is_two_fa == Constants::YES_FLAG) {

            $model->two_fa_secret = null;
            $model->is_two_fa = Constants::NO_FLAG;

            if ($model->update(true, ['two_fa_secret', 'is_two_fa', 'is_login_two_factor'])) {

                SystemLog::log(
                    $model->id,
                    'Two Factor authentication disabled from ' . Yii::$app->request->getUserIP() . " using device " . Yii::$app->request->getUserAgent(),
                    Constants::LOG_TYPE_USER_TWO_FACTOR_AUTHENTICATION
                );
                if (isset($_GET['login'])) {
                    $this->flash('success', 'Login Two factor authentication disabled successfully.');
                }else{
                    $this->flash('success', 'Two factor authentication disabled successfully.');
                }

            } else {
                $this->flash('error', 'Failed to disable two factor authentication.');
            }

        }
        return $this->redirect(['user/profile'])->send();
    }


    public function actionUpdatePassword(){
        $identity = $this->identity;

        $userModel = $identity;
        $userModel->scenario = Users::SCENARIO_CHANGE_PASSWORD;

        if ($userModel != null) {
            if ($userModel->load(Yii::$app->request->post())) {
                if ($userModel->validate()) {

                    if($userModel->old_password==$userModel->new_password){
                        $this->flash('error', 'New Password cannot be same as old.');
                    }else {

                        if ($userModel->validatePassword($userModel->old_password)) {
                            $newModel = $identity;
                            if ($newModel->updatePassword($userModel->new_password)) {
                                $this->flash('success', 'Password changed successfully.');
                            } else {
                                $this->flash('error', 'Something went wrong, please try again later.');
                            }
                        } else {
                            $this->flash('error', 'Old Password is Incorrect.');
                        }
                    }
                } else {
                    $this->flash('error', 'Something went wrong, please try again later.');
                }
            }
        } else {
            $this->flash('error', 'Something went wrong, please try again later.');
        }

        return $this->redirect(['user/profile'])->send();
    }


    public function actionRemovePic(){
        $identity = $this->identity;
        if($identity->profile_pic!=null){
            try{
                if(file_exists($identity->profile_pic)){
                    unlink($identity->profile_pic);
                }
            }catch (\Exception $ex){}
        }
        $identity->profile_pic = null;
        $identity->update(false,['profile_pic']);
        $this->flash('success', 'Profile picture removed successfully.');
        return $this->redirect(['user/profile'])->send();
    }

    public function actionUploadPic(){
        $identity = $this->identity;
        $uploadImageResponse = $identity->upload($identity,'profile_pic','profile');
        if($uploadImageResponse===true) {
            $this->flash('success', 'Profile picture uploaded successfully.');
        }else{
            $this->flash('error', $uploadImageResponse);
        }
        return $this->redirect(['user/profile'])->send();
    }

    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->redirect(['auth/login'])->send();
    }

}
