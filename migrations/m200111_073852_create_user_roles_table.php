<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_roles}}`.
 */
class m200111_073852_create_user_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_roles}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'modules' => $this->text(),
        ],Constants::DB_TABLE_OPTIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_roles}}');
    }
}
