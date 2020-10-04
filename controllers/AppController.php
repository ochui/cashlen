<?php


namespace app\controllers;


use app\base\controllers\ProtectedController;
use app\common\Constants;
use app\common\Helper;
use app\common\SystemLog;
use app\components\Cloudflare;
use app\components\TestingPlatform;
use app\models\activerecord\Categories;
use app\models\activerecord\DiscussionSessions;
use app\models\activerecord\SessionRequests;
use app\models\activerecord\Subjects;
use app\models\activerecord\Survey;
use app\models\activerecord\UserDiscussionSessions;
use app\models\activerecord\UserSessions;
use app\models\activerecord\VideoAssignments;
use app\models\activerecord\Videos;
use app\models\activerecord\VideoWatchedHistory;
use app\models\search\SessionRequestsSearch;
use app\models\search\VideosAssignmentsSearch;
use app\models\search\VideosSearch;
use app\models\search\VideoWatchedHistorySearch;
use app\models\search\ViewsRequestSearch;
use app\sockets\EchoServer;
use PHPUnit\TextUI\Help;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class AppController extends ProtectedController
{
    public function beforeAction($action)
    {
        parent::beforeAction($action);
        if (\Yii::$app->user->isGuest) {
            return $this->redirectTo(['site/index']);
        }
        if($this->identity->role_id!=Constants::USER_ROLE_USER){
            return $this->redirectTo(['site/error']);
        }

        if($this->identity->status_id!=Constants::USER_STATUS_ACTIVE){
            Yii::$app->user->logout();
            return $this->redirect(['auth/login'])->send();
        }

        return true;
    }

    public function actionIndex(){
        return $this->redirect(['user/index'])->send();
    }

}
