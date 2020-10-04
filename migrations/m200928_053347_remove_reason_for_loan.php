<?php

use yii\db\Migration;

/**
 * Class m200928_053347_remove_reason_for_loan
 */
class m200928_053347_remove_reason_for_loan extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('submissions','reason_for_loan', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('submissions','reason_for_loan', $this->text()->notNull());
    }

}
