<?php

use app\common\Constants;
use app\common\Helper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

?>

<h3><?= $this->title ?></h3>
<p class="text-muted">Enter details below to create a new password.</p>
<?php $form = ActiveForm::begin([
    'id' => 'register-form',
    'options' => ['class' => ''],
    'fieldConfig' => ['options' => ['class' => 'input-group']],
]); ?>


<div class="form-group">
    <div class="col-md-12 col-12">
        <?= $form->field($model, 'new_password', ['template' => Helper::formFieldTemplate('fa fa-unlock-alt')])->passwordInput(['class' => 'form-control', 'placeholder' => 'Enter password']) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-md-12 col-12">
        <?= $form->field($model, 'confirm_password', ['template' => Helper::formFieldTemplate('fa fa-unlock-alt')])->passwordInput(['class' => 'form-control', 'placeholder' => 'Confirm password']) ?>
    </div>
</div>


<?=$this->render('/layouts/_recaptcha_field');?>


<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-primary btn-block">Update Password</button>
    </div>
    <div class="col-12">
        <p>Already have an account? <a href="<?= Url::toRoute(['auth/login']) ?>"
                                       class="btn btn-link box-shadow-0 px-0">Login Now</a></p>
    </div>
</div>


<?php ActiveForm::end() ?>