<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%submissions}}`.
 */
class m200925_081317_create_submissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%submissions}}', [
            'id' => $this->primaryKey(),

            'reason_for_loan' => $this->text()->notNull(),
            'loan_amount_required' => $this->string()->notNull(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'last_four_ssn' => $this->integer()->notNull(),
            'birth_year' => $this->integer()->notNull(),
            'zip_code' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'phone_number' => $this->string()->notNull(),
            'dob' => $this->string()->notNull(),
            'active_military' => $this->integer()->notNull(),
            'street_address' => $this->string()->notNull(),
            'years_living_from' => $this->string()->notNull(),
            'home_owner' => $this->integer()->notNull(),
            'employment_status' => $this->string()->notNull(),
            'years_with_employer' => $this->string()->notNull(),
            'how_often_paid' => $this->string()->notNull(),
            'monthly_income' => $this->string()->notNull(),
            'next_pay_date' => $this->string()->notNull(),
            'employer_name' => $this->string()->notNull(),
            'occupation' => $this->string()->notNull(),
            'employer_phone_number' => $this->string()->notNull(),
            'drivers_license' => $this->string()->notNull(),
            'state' => $this->string()->notNull(),
            'ssn' => $this->integer()->notNull(),
            'bank_routing_number' => $this->integer()->notNull(),
            'account_number' => $this->string()->notNull(),
            'bank_name' => $this->string()->notNull(),
            'how_get_paid' => $this->string()->notNull(),
            'time_with_account' => $this->string()->notNull(),
            'account_type' => $this->string()->notNull(),
            'unsecured_debt' => $this->integer()->notNull(),

            'useragent' => $this->string(200)->notNull(),
            'time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'ip' => $this->string(50)->notNull(),
        ],Constants::DB_TABLE_OPTIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%submissions}}');
    }
}
