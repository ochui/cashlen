<?php

use yii\db\Migration;

/**
 * Class m200415_100046_update_user_agent_length_in_some_table
 */
class m200415_100046_update_user_agent_length_in_some_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%logs}}','useragent',$this->string(500)->notNull());
        $this->alterColumn('{{%user_sessions}}','useragent',$this->string(500)->notNull());
        $this->alterColumn('{{%user_login_history}}','useragent',$this->string(500)->notNull());
        $this->alterColumn('{{%users}}','useragent',$this->string(500)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
