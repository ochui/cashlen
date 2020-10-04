<?php

use app\components\Constants;
use app\components\Helper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserInvestmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ip Logs');

$logTypes = [
    Constants::LOG_TYPE_USER_LOGIN,
    Constants::LOG_TYPE_USER_FAILED_LOGIN,
    Constants::LOG_TYPE_USER_FORGOT_PASSWORD,
    Constants::LOG_TYPE_USER_PASSWORD_CHANGED,
    Constants::LOG_TYPE_USER_BTC_ADDRESS_CHANGED
];

?>

<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=\yii\helpers\Url::toRoute(['admin/index'])?>">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <div class="dropleft btn-group mt-2 mb-2">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <?php if ($type != null) {
                        echo Helper::printUnderScoreName($type);
                    } else {
                        echo 'All';
                    } ?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <?php
                    foreach ($logTypes as $logType) {
                        ?>
                        <li>
                            <a href="<?= Url::toRoute(['admin/ip-logs', 'type' => $logType]) ?>"><?= Helper::printUnderScoreName($logType) ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="table-responsive">
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout' => Yii::$app->params["gridViewTemplate"],
                    'tableOptions' => ['class' => 'table'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute'=>'type',
                            'value'=>function ($model){
                                return ucwords(str_replace(array("_",'user'),array(" ",""),$model->type));
                            },
                            'headerOptions' => [ 'class' => 'medium-width-table-col'],
                            'filterOptions' => [ 'class' => 'medium-width-table-col'],
                            'contentOptions' => [ 'class' => 'medium-width-table-col'],
                        ],
                       /* [
                            'attribute'=>'particulars',
                            'format'=>'raw',
                            'value'=>function ($model){
                                return $model->particulars;

                            }
                        ],*/
                        [
                            'attribute'=>'data',
                            'format'=>'raw',
                            'value'=>function ($model){


                                if(!isset($_GET['type'])){

                                    if(stripos($model->data,"-")){
                                        $username = explode("-",$model->data)[0];
                                        $user_check = \app\models\Users::find()->where(['username'=>$username])->one();
                                        if($user_check!=null)
                                            return \yii\helpers\Html::a($username,
                                                Url::toRoute(['users/view','id'=>$user_check->id]),['style'=>'color:black']);
                                        else
                                            return trim($username);
                                    }
                                }

                                $user_check = \app\models\Users::find()->where(['username'=>$model->data])->one();
                                if($user_check!=null)
                                    return \yii\helpers\Html::a($model->data,
                                        Url::toRoute(['users/view','id'=>$user_check->id]),['style'=>'color:black']);
                                else
                                    return $model->data;
                            }
                        ],
                        'ip',
                        [
                            'attribute'=>'country_id',
                            'value'=>function ($model){
                                if($model->country!=null) {
                                    return $model->country->name;
                                }
                                return '-';
                            },
                            'headerOptions' => ['class' => 'same-line-content large-width-table-col'],
                            'contentOptions' => ['class' => 'same-line-content large-width-table-col'],
                            'filterOptions' => ['class' => 'same-line-content large-width-table-col'],
                        ],

                        [
                            'attribute'=>'useragent',
                            'value'=>function ($model){
                                return \app\components\Helper::shortenUA($model->useragent);
                            },
                            'headerOptions' => ['class' => 'same-line-content large-width-table-col'],
                            'contentOptions' => ['class' => 'same-line-content large-width-table-col'],
                            'filterOptions' => ['class' => 'same-line-content large-width-table-col'],
                        ],

                        [
                            'attribute'=>'time',
                            'headerOptions' => [ 'class' => 'medium-width-table-col'],
                            'filterOptions' => [ 'class' => 'medium-width-table-col'],
                            'contentOptions' => [ 'class' => 'medium-width-table-col'],
                        ],

                    ],
                ]); ?>

            </div>
        </div>
    </div>

</div>
