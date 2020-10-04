<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%background_tasks}}`.
 */
class m200223_151132_create_background_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%background_tasks}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(100)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'response' => $this->text(),
            'reference' => $this->string(800),
        ],Constants::DB_TABLE_OPTIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%background_tasks}}');
    }
}
