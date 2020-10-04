<?php

use yii\db\Migration;

/**
 * Class m200930_051014_remove_null_from_columns
 */
class m200930_051014_remove_null_from_columns extends Migration
{
    public $columns = [
        'last_four_ssn',
        'birth_year',
        'years_living_from',
        'employment_status',
        'years_with_employer',
        'how_often_paid',
        'monthly_income',
        'next_pay_date',
        'employer_name',
        'occupation',
        'employer_phone_number',
        'drivers_license',
        'state',
        'account_number',
        'bank_name',
        'how_get_paid',
        'time_with_account',
        'account_type',
    ];

    public $numberColumns = [
        'unsecured_debt',
        'bank_routing_number',
        'ssn',
        'active_military',
        'home_owner',
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach ($this->columns as $column){
            $this->alterColumn('submissions', $column, $this->string()->null());
        }
        foreach ($this->numberColumns as $column){
            $this->alterColumn('submissions', $column, $this->integer()->null());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

}
