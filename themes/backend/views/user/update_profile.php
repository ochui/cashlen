<?php

use app\common\Constants;
use app\common\Helper;
use app\models\activerecord\Countries;
use app\models\activerecord\Users;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * @var Users $identity
 */
?>
<div class="page-header">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=Url::toRoute(['user/index'])?>">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?=$this->title?></li>
    </ol>
</div>
<div class="row mb-5">
    <div class="col-lg-4 mb-4 mb-md-0 col-12">
        <div class="card h-100">
            <div class="card-body">
                <div class="dash2 text-center">
                    <img src="<?=$identity->getProfilePicture()?>" alt="img" class="w-35">
                    <h4 class="text-uppercase mt-2 mb-1 font-weight-semibold"><?=$identity->getPublicName()?></h4>
                    <p class="text-muted">Joined On: <?=date('d M Y',strtotime($identity->time))?></p>
                    <div class="d-none">
                        <?php $form = ActiveForm::begin([
                            'id' => 'profile-pic-form',
                            'action' => Url::toRoute(['user/upload-pic']),
                            'options' => ['enctype' => 'multipart/form-data']
                        ]); ?>
                        <?= $form->field($model, 'profile_pic')->fileInput() ?>
                        <?php ActiveForm::end() ?>
                    </div>
                    <div class="button-container">
                        <a href="javascript:;" id="uploadImageUploader" class="btn btn-primary mr-md-2 mb-2"><i class="fe fe-camera mr-2"></i>Change Photo</a>
                        <a href="<?=Url::toRoute(['user/remove-pic'])?>" class="btn btn-info mb-2"><i class="fe fe-camera-off mr-2"></i>Remove Photo</a>
                    </div>
                    <div class="user-info">
                        <ul>
                            <li><span>Username:</span> <?=$identity->username?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 mt-5 mt-lg-0 col-12">
        <div class="card h-100">
            <div class="card-header card-header-tabed">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Personal Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Change Password</a>
                    </li>

                  <!--  <li class="nav-item">
                        <a class="nav-link" id="two-fa-tab" data-toggle="tab" href="#two-fa" role="tab" aria-controls="contact" aria-selected="false">2FA</a>
                    </li>-->

                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content border p-0 border-0" id="myTabContent">
                    <div class="tab-pane fade p-0 active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <?php $form = ActiveForm::begin([
                            'id' => 'update-basic-form',
                            'options' => ['class' => ''],
                        ]); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <?= $form->field($model, 'first_name')->textInput(['class' => 'form-control', 'placeholder' => 'Enter first name']) ?>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <?= $form->field($model, 'last_name')->textInput(['class' => 'form-control', 'placeholder' => 'Enter last name']) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <?= $form->field($model, 'gender')->dropDownList(Constants::$GENDERS);?>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <?= $form->field($model, 'mobile_no')->textInput(['class' => 'form-control', 'placeholder' => 'Enter mobile no.']) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'placeholder' => 'Enter email','readonly'=>true]) ?>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group field-users-country_id has-success">
                                            <label class="control-label" for="">Country</label>
                                            <input type="text" class="form-control"value="<?php
                                            if($model->country!=null){
                                                echo $model->country->nicename;
                                            }else{
                                                echo '-';
                                            }
                                            ?>" readonly="">
                                            <p class="help-block help-block-error"></p>
                                        </div>
                                    </div>
                                </div>
                                <?php if($identity->is_two_fa==Constants::YES_FLAG){ ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <?=$this->render('/layouts/_two_fa_form')?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="btn-list">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>
                    <div class="tab-pane fade p-0" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <?php $form = ActiveForm::begin([
                            'id' => 'change-password-form',
                            'action'=>Url::toRoute(['user/update-password']),
                            'options' => ['class' => ''],
                        ]); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($changePasswordModel, 'old_password')->passwordInput(['class' => 'form-control', 'placeholder' => 'Enter old password']) ?>
                                <?= $form->field($changePasswordModel, 'new_password')->passwordInput(['class' => 'form-control', 'placeholder' => 'Enter new password']) ?>
                                <?= $form->field($changePasswordModel, 'confirm_password')->passwordInput(['class' => 'form-control', 'placeholder' => 'Confirm password']) ?>
                                <?php if($identity->is_two_fa==Constants::YES_FLAG){ ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <?=$this->render('/layouts/_two_fa_form')?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="btn-list">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>

                    <div class="tab-pane fade p-0" id="two-fa" role="tabpanel" aria-labelledby="two-fa-tab">
                        <?php
                        if(false){
                        if($model->is_two_fa==Constants::NO_FLAG){
                            ?>

                            <?php $form = ActiveForm::begin([
                                'id' => 'two-factor-form',
                                'action'=>\yii\helpers\Url::toRoute(['user/enable-twofactor']),
                                'options'=>['class'=>'form-horizontal w-100'],
                                'fieldConfig' => [
                                    'labelOptions' => ['class' => ''],

                                ],
                            ]); ?>

                            <?= $form->field($twoFactorModel, 'auth_key')->textInput(['class'=>'form-control','placeholder'=>'Enter Google Authenticator code'])?>

                            <p>
                                Use below key or scan QR-Code to generate google code, you also need Google Authenticator app for this.
                            </p>

                            <div class="google_auth_box">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <strong>Key: </strong> <br> <span class="text-primary"><?=$secretKey['code']?></span>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <img style="width: 150px;float: right" src="<?=$secretKey['qr_code']?>">
                                    </div>
                                </div>
                            </div>

                            <?= \yii\helpers\Html::submitButton('Enable Now', ['class' => 'btn btn-primary btn-modern', 'name' => 'invest-button']) ?>

                            <?php ActiveForm::end()?>

                            <?php
                        }else{
                        ?>
                        <div class="row">
                            <div class="col-12 col-xs-12">
                                <p>
                                    You have enabled two factor authentication for your account to secure your account. If you wish to disable
                                    enter the 2FA code and click <strong>Disable Now</strong> button below.
                                </p>
                            </div>
                            <div class="col-12 col-xs-12">
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'two-factor-disable-form',
                                    'action'=>Url::toRoute(['user/disable-twofactor']),
                                    'options'=>['class'=>'form-horizontal w-100'],
                                    'fieldConfig' => [
                                        'labelOptions' => ['class' => 'font-weight-bold text-dark'],

                                    ],
                                ]); ?>


                                <?= $this->render("//layouts/_two_fa_form")?>

                                <button type="submit" class="btn mt-3 btn-primary">Disable 2FA</button>


                                <?php
                                ActiveForm::end();
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php } } ?>
        </div>
    </div>
</div>
    </div>
</div>
<?php
$js = <<<JS
    $(document).on("change","#users-profile_pic",function(){
       $("#profile-pic-form").submit(); 
    });
    $(document).on("click","#uploadImageUploader",function(){
       $("#users-profile_pic").trigger("click"); 
    });

JS;
$this->registerJs($js,\yii\web\View::POS_END);
?>
