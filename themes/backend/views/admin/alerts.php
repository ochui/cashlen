<?php

use app\components\Constants;
use app\components\Helper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserInvestmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=\yii\helpers\Url::toRoute(['admin/index'])?>">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <?= Html::a(Yii::t('app', 'Mark all as Read'), ['admin/alert-read'], ['class' => 'btn mr-2 btn-primary']) ?>
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
                             'attribute'=>'particular',
                             'format'=>'raw',
                             'value'=>function ($model){
                                 return $model->shortParticular(false);
                             },
                             'headerOptions' => ['class' => 'v-small-width-table-col'],
                             'contentOptions' => ['class' => 'v-small-width-table-col'],
                             'filterOptions' => ['class' => 'v-small-width-table-col'],
                         ],
                        [
                            'attribute'=>'ip',
                            'format'=>'raw',
                            'value'=>function ($model){
                                return '<span title="'.$model->ip.'">'.$model->getColoredIp().'</span>';
                            },
                            'headerOptions' => ['class' => 'same-line-content small-width-table-col'],
                            'contentOptions' => ['class' => 'same-line-content small-width-table-col','style'=>'overflow:hidden'],
                            'filterOptions' => ['class' => 'same-line-content small-width-table-col'],
                        ],
                        [
                            'attribute'=>'country_id',
                            'value'=>function ($model){
                                if($model->country!=null) {
                                    return $model->country->name;
                                }
                                return '-';
                            },
                            'filter' => \yii\helpers\ArrayHelper::map(\app\models\Countries::find()->all(),'id','nicename'),
                            'filterInputOptions' => [
                                'class'       => 'gridfilter form-control',
                                'prompt' => 'Select Country'
                            ],
                            'headerOptions' => ['class' => 'same-line-content medium-width-table-col'],
                            'contentOptions' => ['class' => 'same-line-content medium-width-table-col'],
                            'filterOptions' => ['class' => 'same-line-content medium-width-table-col'],
                        ],
                        [
                            'label'=>'Username\'s Used',
                            'value'=>function ($model){
                                return $model->getUsernameUsed();
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
                            'headerOptions' => ['class' => 'same-line-content medium-width-table-col'],
                            'contentOptions' => ['class' => 'same-line-content medium-width-table-col'],
                            'filterOptions' => ['class' => 'same-line-content medium-width-table-col'],
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
