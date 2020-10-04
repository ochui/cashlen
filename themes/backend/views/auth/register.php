<?php

use app\common\Constants;
use app\common\Helper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

?>

<h3><?= $this->title ?></h3>
<p class="text-muted">Enter details below to create a new account.</p>
<?php $form = ActiveForm::begin([
    'id' => 'register-form',
    'options' => ['class' => ''],
    'fieldConfig' => ['options' => ['class' => 'input-group']],
]); ?>


<i class="fas fa-user-alt icons"></i>

<div class="row">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <?= $form->field($model, 'first_name', ['template' => Helper::formFieldTemplate('fa fa-user')])->textInput(['class' => 'form-control', 'placeholder' => 'Enter first name']) ?>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <?= $form->field($model, 'last_name', ['template' => Helper::formFieldTemplate('fa fa-user')])->textInput(['class' => 'form-control', 'placeholder' => 'Enter last name']) ?>
        </div>
    </div>
</div>


<div class="form-group">
    <?= $form->field($model, 'username', ['template' => Helper::formFieldTemplate('fa fa-user-o')])->textInput(['class' => 'form-control', 'placeholder' => 'Enter username']) ?>
    <div class="help-block" id="user-name-check"></div>
</div>

<div class="form-group">
    <?= $form->field($model, 'email', ['template' => Helper::formFieldTemplate('fa fa-envelope')])->textInput(['class' => 'form-control', 'placeholder' => 'Enter email']) ?>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6 col-12">
            <?= $form->field($model, 'new_password', ['template' => Helper::formFieldTemplate('fa fa-unlock-alt')])->passwordInput(['class' => 'form-control', 'placeholder' => 'Enter password']) ?>
        </div>
        <div class="col-md-6 col-12">
            <?= $form->field($model, 'confirm_password', ['template' => Helper::formFieldTemplate('fa fa-unlock-alt')])->passwordInput(['class' => 'form-control', 'placeholder' => 'Confirm password']) ?>
        </div>
    </div>
</div>




<?=$this->render('/layouts/_recaptcha_field');?>

<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-primary btn-block">Create an Account</button>
    </div>
    <div class="col-12">
        <p>Already have an account? <a href="<?= Url::toRoute(['auth/login']) ?>"
                                       class="btn btn-link box-shadow-0 px-0">Login Now</a></p>
    </div>
</div>


<?php ActiveForm::end() ?>

<?php
$js = <<<JS
    
    $(document).on("change","#users-username",function() {
      let obj = $(this);
      $.get(baseUrl+"auth/check-username?q="+obj.val(),function(data) {
        if(data.status==='error'){
            $("#user-name-check").addClass("help-block-error").html("Username is already taken.");
        }else{
            $("#user-name-check").removeClass("help-block-error").html("Username is available.");
        }
      });
    });
    
    
    function checkFormForErrors(){
        var isValid = true;
        var submitBtn = $("#register-form").find('button[type="submit"]');
        $("#register-form .help-block-error").each(function(){
            var obj = $(this);
            var value = obj.text().trim();
            if(value!=''){
            isValid = false;
          }
        });
        if(isValid){
            submitBtn.attr("disabled",false);
        }else{
            submitBtn.attr("disabled",true);
        }
    }
  
    $('input').on('blur keyup', function() {
        setTimeout(function() {
          checkFormForErrors();
        },200);
        setTimeout(function() {
          checkFormForErrors();
        },500);
    });
    
     $('button').on('click', function() {
        setTimeout(function() {
          checkFormForErrors();
        },200);
        setTimeout(function() {
          checkFormForErrors();
        },500);
    });
  
    
JS;

if(isset($_GET['c']) && $_GET['c']!=''){
    $js .= '$("#users-referral_code").trigger("change");';
}

$this->registerJs($js, View::POS_END);
?>
