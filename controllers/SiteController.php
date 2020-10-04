<?php

namespace app\controllers;

use app\base\controllers\BaseController;
use app\common\Constants;
use app\common\Helper;
use app\models\activerecord\Submissions;
use app\models\activerecord\Users;
use app\models\activerecord\VideoWatchedHistory;
use app\models\base\ContactForm;
use app\models\base\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends BaseController
{


    public function beforeAction($action)
    {
        if ($action->id == "lead-submit") {
            $this->enableCsrfValidation = false;
        }

        if ($action->id == "error") {
            $this->layout = "authLayout";
        } else {
            Yii::$app->view->theme = new \yii\base\Theme([
                'pathMap' => [
                    '@app/views' => '@app/themes/frontend/views',
                ],
                'baseUrl' => '@web/resources/frontend',
                'basePath' => '@app/themes/frontend',
            ]);
        }
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'app\components\LogClass',
                'project_id' => 171,
                'api_key' => '5c519b5a114c6',
                'debug' => YII_DEBUG
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('_index');
    }

    public function actionLeadSubmit()
    {
        $url = $_ENV['APP_URL']."site/thank-you";
        $response = ['status' => 'error', 'message' => 'Failed to complete request', 'url' => $url];
        $model = new Submissions();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $response['status'] = 'success';
                $response['message'] = 'Not Qualified';
            }
        }
        if (Yii::$app->request->isAjax) {
            echo json_encode($response);die;
        } else {
            return $this->redirect($urlSetting->value)->send();
        }
    }

    public function actionApply()
    {
        $this->view->title = "Apply";
        return $this->render('apply');
    }

    public function actionRates()
    {
        $this->view->title = "Rates";
        return $this->render('rates');
    }

    public function actionTerms()
    {
        $this->view->title = "Terms and Conditions";
        return $this->render('terms');
    }

    public function actionPartners()
    {
        return $this->redirect(['site/index'])->send();
    }

    public function actionPrivacy()
    {
        $this->view->title = "Privacy";
        return $this->render('privacy');
    }

    public function actionPrivacyCalifornia()
    {
        $this->view->title = "Privacy California";
        return $this->render('privacy_california');
    }

    public function actionAbout()
    {
        $this->view->title = "About Us";
        return $this->render('about');
    }

    public function actionFaq()
    {
        $this->view->title = "FAQ";
        return $this->render('faq');
    }

    public function actionContact()
    {
        $this->view->title = "Contact";
        return $this->render('contact');
    }

    public function actionHow()
    {
        $this->view->title = "How it Works";
        return $this->render('how');
    }

    public function actionThankYou(){
        $this->view->title = "Thank You";
        return $this->render('thanks');
    }

}

