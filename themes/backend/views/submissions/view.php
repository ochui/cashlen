<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\activerecord\Submissions */

$this->title = "#".$model->id;
?>

<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/teamoxio/to-cash-lendings/web/gii/user/index">Dashboard</a></li>
        <li class="breadcrumb-item">
            <?= Html::a('Submissions', ['index'], ['class' => '']) ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <?= Html::a('Go Back', ['index'], ['class' => 'btn btn-info mr-2']) ?>

            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
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
                <?= DetailView::widget([

                    'model' => $model,
                    'options' => ['class' => 'table'],
                    'attributes' => [
                        'loan_amount_required',
                        'first_name',
                        'last_name',
                        'email:email',
                        'dob',
                        'phone_number',
                        'street_address',
                        'zip_code',
                        'useragent',
                        'time',
                        'ip',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
