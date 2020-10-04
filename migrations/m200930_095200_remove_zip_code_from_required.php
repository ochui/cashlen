<?php

use yii\db\Migration;

/**
 * Class m200930_095200_remove_zip_code_from_required
 */
class m200930_095200_remove_zip_code_from_required extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('submissions', 'zip_code', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return true;

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200930_095200_remove_zip_code_from_required cannot be reverted.\n";

        return false;
    }
    */
}
