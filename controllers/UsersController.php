<?php

namespace app\controllers;

use app\common\Constants;
use app\common\Helper;
use app\common\SystemLog;
use app\models\activerecord\UserLoginHistory;
use app\models\activerecord\UserParentsDetails;
use app\models\activerecord\Videos;
use app\models\activerecord\VideoWatchedHistory;
use app\models\base\BulkEmail;
use app\models\search\LogsSearch;
use Mailgun\Mailgun;
use Yii;
use app\models\activerecord\Users;
use app\models\search\UsersSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\User;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends \app\base\controllers\ProtectedController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionDelete($id){
        $model = $this->findModel($id);
        if($model->delete()){
            SystemLog::log(
                $model->id,
                'User ('.$model->username.') deleted by admin.',
                Constants::LOG_TYPE_USER_DELETED
            );
            $this->flash('success','User has been deleted');
        }
        return $this->redirectTo(['users/index']);
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchQuery = '';
        if(isset($_POST['q'])){
            $searchQuery = $_POST['q'];
            $searchQuery = trim(strip_tags($searchQuery));
        }
        $searchModel = new UsersSearch();
        $searchModel->searchQuery = $searchQuery;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModelLogs = new LogsSearch();
        $searchModelLogs->user_id = $model->id;
        $dataProviderLogs = $searchModelLogs->search(Yii::$app->request->queryParams);

        $searchModelLoginLogs = UserLoginHistory::find()->where(['user_id'=>$model->id]);
        $dataProviderLoginLogs = new ActiveDataProvider([
            'query' => $searchModelLoginLogs,
            'sort'=>[
                'defaultOrder'=>['id'=>SORT_DESC]
            ]
        ]);

        return $this->render('view', compact('model','searchModelLogs','dataProviderLogs', 'searchModelLoginLogs', 'dataProviderLoginLogs'));
    }


    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users(['scenario'=>Users::SCENARIO_REGISTER]);
        $model->referral_code = '';

        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['Users']['is_demo_account'])) {
                $model->is_demo_account = $_POST['Users']['is_demo_account'];
            }
            if($model->save()){
                $this->flash("success", "User has been created.");
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                $this->flash("success", "Failed to create student.");
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionPassword($id){
        $userModel = $this->findModel($id);
        $this->view->title = "Reset Password";

        $model = new Users(['scenario' => Users::SCENARIO_REGISTER]);

        if($model->load(Yii::$app->request->post())){

            if ($model->new_password == $model->confirm_password) {

                if ($userModel->updatePassword($model->new_password)) {
                    $this->flash("success", "Password has been changed.");
                    return $this->redirect(['users/view','id'=>$userModel->id])->send();
                } else {
                    $this->flash("error", "Failed to update password at the moment.");
                }

            } else {
                $this->flash("error", "New and Confirm password doesn't match.");
            }

            return $this->redirect(['users/view','id'=>$userModel->id])->send();
        }
        return $this->render('reset-password', compact('model','userModel'));
    }


    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            //due to mass assignment restricted
            if(isset($_POST['Users']['role_id'])) {
                $model->role_id = $_POST['Users']['role_id'];
            }
            if(isset($_POST['Users']['status_id'])) {
                $model->status_id = $_POST['Users']['status_id'];
            }
            if(isset($_POST['Users']['is_demo_account'])) {
                $model->is_demo_account = $_POST['Users']['is_demo_account'];
            }

            $model->save();

            $this->flash("success", "User has been updated.");


            SystemLog::log(
                $model->id,
                'User profile updated by admin.',
                Constants::LOG_TYPE_USER_PROFILE_UPDATE
            );

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }



    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionEmail(){
        $this->view->title = "Send Email to Users";

        $model = new BulkEmail(['scenario'=>BulkEmail::BULK_EMAIL_SCENARIO_PRIMARY]);

        $user = null;
        if(isset($_GET['user']) && is_numeric($_GET['user'])){
            $user = Users::findOne($_GET['user']);
            if($user==null){
                Yii::$app->session->setFlash('error','Invalid request.');
                return $this->redirect(Yii::$app->request->referrer ?: ['users/index']);
            }

            $this->view->title = "Send Email to ".$user->username;
            $model->users = [$user->id];
        }

        if($model->load(Yii::$app->request->post()) && $model->validate()){

            if($model->send()){
                Yii::$app->session->setFlash('success','Email sent successfully');
                $model = new BulkEmail(['scenario'=>BulkEmail::BULK_EMAIL_SCENARIO_PRIMARY]);
            }
        }

        return $this->render("email",compact('model', 'user'));
    }


    public function actionUpdateStatus($id, $active){
        $model = $this->findModel($id);

        if($active=='true'){
            $model->status_id = Constants::USER_STATUS_ACTIVE;

            SystemLog::log(
                $model->id,
                'User status marked as active by admin.',
                Constants::LOG_TYPE_USER_PROFILE_UPDATE
            );

        }else{
            $model->status_id = Constants::USER_STATUS_INACTIVE;

            SystemLog::log(
                $model->id,
                'User status marked as in-active by admin.',
                Constants::LOG_TYPE_USER_PROFILE_UPDATE
            );

        }

        $model->update(false, ['status_id']);
        Yii::$app->session->setFlash('success','Status has been updated.');
        return $this->redirect(['users/view','id'=>$id]);
    }

}
