<?php

use yii\db\Migration;

/**
 * Class m200409_095324_add_some_columns_in_users
 */
class m200409_095324_add_some_columns_in_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}','gender',$this->string()->null());
        $this->addColumn('{{%users}}','profile_pic',$this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}','gender');
        $this->dropColumn('{{%users}}','profile_pic');
    }


}
