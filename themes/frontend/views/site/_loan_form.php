<?php

use app\common\Constants;
use app\common\Helper;
use app\models\activerecord\Submissions;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$model = new Submissions();
$ip = Yii::$app->request->userIP;
$eligibleForNextSubmission = Submissions::eligibleForNextSubmission($ip);

$action = Yii::$app->controller->action->id;

?>
<div id="landeriframe">

    <?php
    if($eligibleForNextSubmission) {
        ?>
        <div class="loan-form-header">
            <h1>Apply For Loan</h1>
        </div>
        <?php
        $form = ActiveForm::begin([
            'id' => 'form-lander',
            'options' => ['class' => 'ajax-form'],
            'fieldConfig' => ['options' => ['class' => 'input-group']],
        ]); ?>



        <div style="display: none;" id="progressbar" class="progress ui-progressbar ui-widget ui-widget-content ui-corner-all"
             role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="28">
            <span id="progresstext" class="progress_percentage">PROGRESS: 0%</span>
            <div class="ui-progressbar-value ui-widget-header ui-corner-left" style="width: 0%;"></div>
        </div>

        <div id="loading-zone" style="display: none">
            <div id="loading-content">
                <img title="Sending" alt="Sending" src="img/busy.gif"><br>
                <div class="content">Loading...</div>
            </div>
        </div>

        <div id="loan-not-eligible" style="display: none">
            <div id="stubModal" style="display: inline-block; width: 450px;" class="modal">
                <br><br>

                Our apologies, due to regulations in the state of NY, we are unable to process your request. <br><br>

                Please note: knowingly falsifying information in order to obtain a loan is a felony.<br><br>

                <a href="<?= Url::toRoute(['site/index']) ?>" id="mainLinkChange" target="_parent">
                    <div style="background-color: lightgreen;padding:20px;text-align:center;">
                        Cash Finder may be able to assist with an average claim of over $860. Click Here to go to their
                        website.
                    </div>

                </a><br><br>
            </div>
        </div>


        <div id="form-steps">
            <div class="content text-center" style="margin-top: 0 !important;">
                <p style="font-size: 20px">Please fill out below details</p>
            </div>
            <section class="progressSection page-step active" id="page1">
                <div class="row">
                    <div class="col-md-6 col col-12">
                        <?= $form->field($model, 'loan_amount_required')->dropDownList([
                            '$100-$500' => '$100 - $500',
                            '$500-$1000' => '$500 - $1,000',
                            '$1000-$2500' => '$1,000 - $2,500',
                            '$2500-$5000' => '$2,500 - $5,000',
                            '$5000-$20000' => '$5,000 - $20,000',
                            '$20000-$50000' => '$20,000 - $50,000',
                        ]) ?>
                    </div>
                    <div class="col-md-6 col col-12">
                        <?= $form->field($model, 'email')->textInput() ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col col-12">
                        <?= $form->field($model, 'first_name')->textInput() ?>
                    </div>
                    <div class="col-md-6 col col-12">
                        <?= $form->field($model, 'last_name')->textInput() ?>
                    </div>
                </div>

                <div class="d-none"><?= $form->field($model, 'dob')->hiddenInput() ?></div>

                <div class="col-12">
                    <label for="">Date Of Birth</label>
                </div>
                <div class="row merger-values" data-target="submissions-dob">
                    <div class="col-md-4 col">
                        <div class="input-group">
                            <select id="birthdate_month" class="form-control merge-item">
                                <option value="">-Select-</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col">
                        <div class="input-group">
                            <select id="birthdate_day" class="form-control merge-item">
                                <option value="">-Select-</option>
                                <?php
                                for ($i = 1; $i <= 31; $i++) {
                                    ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col">
                        <div class="input-group">
                            <select id="birthdate_year" class="form-control merge-item">
                                <option value="">-Select-</option>
                                <?php
                                $birthYears = Helper::getBirthYears();
                                foreach ($birthYears as $birthYear) {
                                    ?>
                                    <option value="<?= $birthYear ?>"><?= $birthYear ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-none"><?= $form->field($model, 'phone_number')->hiddenInput() ?></div>

                <div class="col-12">
                    <label for="">Phone number</label>
                </div>
                <div class="row merger-values" data-target="submissions-phone_number">
                    <div class="col-md-4 col">
                        <div class="input-group">
                            <input id="phone_number_1" type="number" class="form-control jump-next merge-item"
                                   data-max="3" data-next="phone_number_2">
                        </div>
                    </div>
                    <div class="col-md-4 col">
                        <div class="input-group">
                            <input id="phone_number_2" type="number" class="form-control jump-next merge-item"
                                   data-max="3" data-next="phone_number_3">
                        </div>
                    </div>
                    <div class="col-md-4 col">
                        <div class="input-group">
                            <input id="phone_number_3" type="number" class="form-control jump-next merge-item"
                                data-min="4"  data-max="4">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col col-12">
                        <?= $form->field($model, 'street_address')->textInput(); ?>
                    </div>
                </div>

                <div class="bordered-conteiner">
                    <label class="checkbox">
                        <input class="prog" required="required" id="termsField" name="termsField" value="1"
                               type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                    <span>
                &nbsp;&nbsp;            By checking this box, I certify that I am at least 18 years old and agree to the <a
                                id="terms" href="<?= Url::toRoute(['site/terms']) ?>" target="_blank">Terms of Use</a>
                    </span>
                </div>

                <div id="captcha-form">
                    <div class="g-recaptcha" data-sitekey="<?=Helper::getSettingValue(Constants::SETTINGS_RE_CAPTCHA_SITE_KEY)?>"></div>
                </div>


                <button type="submit" class="btn next-page btn-primary" href="javascript:void(0);">Submit</button>

            </section>
        </div>




        <?php ActiveForm::end();
    }else {
        if ($action != "index") {
            ?>

            <div id="form-lander">
                <h4 style="text-align: center">Your have already applied.</h4>
            </div>

            <?php
        }
    }
    ?>
</div>
