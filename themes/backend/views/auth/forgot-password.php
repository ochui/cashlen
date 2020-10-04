<?php

use app\common\Constants;
use app\common\Helper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>

    <h3><?=$this->title?></h3>
    <p class="text-muted">Enter username below to receive reset password link on registered email.</p>

<?php $form = ActiveForm::begin([
    'id' => 'register-form',
    'options' => ['class' => ''],
    'fieldConfig' => ['options' => ['class' => 'input-group']],
]); ?>

    <div class="form-group">
        <?= $form->field($model, 'username', ['template' => Helper::formFieldTemplate('fa fa-user')])->textInput(['class' => 'form-control', 'placeholder' => 'Enter username']) ?>
    </div>



<?=$this->render('/layouts/_recaptcha_field');?>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </div>
        <div class="col-12">
            <p class="mb-0"><a href="<?=Url::toRoute(['auth/login'])?>" class="btn btn-link box-shadow-0 px-0">Login Now</a></p>
        </div>
    </div>

<?php ActiveForm::end() ?>
