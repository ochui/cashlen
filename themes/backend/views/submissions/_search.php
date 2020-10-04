<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\SubmissionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-12 col-sm-12">

                    <div class="submissions-search">

                        <?php $form = ActiveForm::begin([
                            'action' => ['index'],
                            'method' => 'get',
                                            ]); ?>

                        <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'reason_for_loan') ?>

    <?= $form->field($model, 'loan_amount_required') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?php // echo $form->field($model, 'last_four_ssn') ?>

    <?php // echo $form->field($model, 'birth_year') ?>

    <?php // echo $form->field($model, 'zip_code') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone_number') ?>

    <?php // echo $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'active_military') ?>

    <?php // echo $form->field($model, 'street_address') ?>

    <?php // echo $form->field($model, 'years_living_from') ?>

    <?php // echo $form->field($model, 'home_owner') ?>

    <?php // echo $form->field($model, 'employment_status') ?>

    <?php // echo $form->field($model, 'years_with_employer') ?>

    <?php // echo $form->field($model, 'how_often_paid') ?>

    <?php // echo $form->field($model, 'monthly_income') ?>

    <?php // echo $form->field($model, 'next_pay_date') ?>

    <?php // echo $form->field($model, 'employer_name') ?>

    <?php // echo $form->field($model, 'occupation') ?>

    <?php // echo $form->field($model, 'employer_phone_number') ?>

    <?php // echo $form->field($model, 'drivers_license') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'ssn') ?>

    <?php // echo $form->field($model, 'bank_routing_number') ?>

    <?php // echo $form->field($model, 'account_number') ?>

    <?php // echo $form->field($model, 'bank_name') ?>

    <?php // echo $form->field($model, 'how_get_paid') ?>

    <?php // echo $form->field($model, 'time_with_account') ?>

    <?php // echo $form->field($model, 'account_type') ?>

    <?php // echo $form->field($model, 'unsecured_debt') ?>

    <?php // echo $form->field($model, 'useragent') ?>

    <?php // echo $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'ip') ?>

                        <div class="form-group">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                            <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>