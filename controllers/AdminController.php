<?php


namespace app\controllers;


use app\base\controllers\ProtectedController;
use app\common\Constants;
use app\common\Helper;
use app\models\activerecord\Categories;
use app\models\activerecord\Files;
use app\models\activerecord\Leads;
use app\models\activerecord\SessionRequests;
use app\models\activerecord\Subjects;
use app\models\activerecord\UserBillingCycles;
use app\models\activerecord\UserDiscussionSessions;
use app\models\activerecord\Users;
use app\models\activerecord\Videos;
use app\models\activerecord\ViewsRequest;
use app\models\activerecord\WelcomeVideoWatchHistory;
use app\models\search\SessionRequestsSearch;
use app\models\search\UserBillingCyclesSearch;
use app\models\search\UserDiscussionSessionsSearch;
use app\models\search\UserParentsDetailsSearch;
use app\models\search\UsersSearch;
use app\models\search\VideoWatchedHistorySearch;
use app\models\search\ViewsRequestSearch;
use PHPUnit\TextUI\Help;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Session;
use yii\web\UploadedFile;

class AdminController extends ProtectedController
{
    public function actionIndex(){
        $this->view->title = "Dashboard";
        $stats = Helper::getAdminStats();
        return $this->render('_index',compact('stats'));
    }


}
