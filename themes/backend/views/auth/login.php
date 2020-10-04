<?php

use app\components\Constants;
use app\common\Helper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<h3><?=$this->title?></h3>
<p class="text-muted">Sign In to your account</p>

<?php $form = ActiveForm::begin([
    'id' => 'register-form',
    'options' => ['class' => ''],
    'fieldConfig' => ['options' => ['class' => 'input-group']],
]); ?>

    <div class="form-group">
        <?= $form->field($model, 'username', ['template' => Helper::formFieldTemplate('fa fa-user-o')])->textInput(['class' => 'form-control', 'placeholder' => 'Enter Username/Mobile no.']) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'password', ['template' => Helper::formFieldTemplate('fa fa-unlock-alt')])->passwordInput(['class' => 'form-control', 'placeholder' => 'Enter Password']) ?>
    </div>

<div class="form-group" style="text-align: left">
    <?= \yii\helpers\Html::checkbox('rememberMe',false)?><span style="margin-left: 5px;">Remember Me</span>
</div>


<?=$this->render('/layouts/_recaptcha_field');?>

<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </div>
</div>

<?php ActiveForm::end() ?>
