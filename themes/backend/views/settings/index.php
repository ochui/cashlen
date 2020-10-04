<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Settings');
?>

<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= \yii\helpers\Url::toRoute(['admin/index']) ?>">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
</div>


<div class="row">
    <div class="col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="table-responsive">
                <?php Pjax::begin(); ?>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([

                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout' => Yii::$app->params["gridViewTemplate"],
                    'tableOptions' => ['class' => 'table'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'name',
                        [
                            'format' => 'raw',
                            'attribute' => 'settingValue',
                        ],
                        [
                            'header' => 'Actions',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update}',
                            'buttons' => [
                                'view' => function ($url) {
                                    return Html::a(
                                        '<span class="ti-eye"></span>',
                                        $url,
                                        [
                                            'title' => 'View',
                                            'data-pjax' => '0',
                                        ]
                                    );
                                },
                                'update' => function ($url) {
                                    return Html::a(
                                        '<span class="ti-pencil"></span>',
                                        $url,
                                        [
                                            'title' => 'Edit',
                                            'data-pjax' => '1',
                                        ]
                                    );
                                },
                                'delete' => function ($url) {
                                    return Html::a(
                                        '<span class="ti-trash "></span>',
                                        $url,
                                        [
                                            'title' => 'Delete',
                                            'data-pjax' => '2',
                                            'data-confirm' => 'Are you sure you want to delete this item?',
                                            'data-method' => 'post',
                                        ]
                                    );
                                }
                            ],
                        ],

                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>

</div>
