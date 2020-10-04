<?php

use app\components\Helper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url; ?>
<h3><?=$this->title?></h3>
<p class="text-muted">Enter verification code below to continue.</p>

<?php $form = ActiveForm::begin([
    'id' => 'two-fa-verify-form',
    'options' => ['class' => ''],
    'fieldConfig' => ['options' => ['class' => 'input-group']],
]); ?>

<?=$this->render('/layouts/_two_fa_form');?>
<input type="hidden" name="verified" value="ok">
<div class="row mt-3">
    <div class="col-12">
        <button type="submit" class="btn btn-primary btn-block">Submit</button>
    </div>
</div>

<?php ActiveForm::end() ?>