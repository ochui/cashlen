<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_status}}`.
 */
class m200112_133418_create_user_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
        ],Constants::DB_TABLE_OPTIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_status}}');
    }
}
