<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Settings */

$this->title = Yii::t('app', 'Update Settings: {name}', [
    'name' => $model->name,
]);
?>

<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=\yii\helpers\Url::toRoute(['admin/index'])?>">Dashboard</a></li>
        <li class="breadcrumb-item">
            <?= Html::a(Yii::t('app', 'Settings'), ['index'], ['class' => '']) ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <?= Html::a(Yii::t('app', 'Go Back'), ['index'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">
                <?= $this->render('_form', [
                'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
