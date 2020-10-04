<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Class m200131_214130_user_sessions
 */
class m200131_214130_user_sessions extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_sessions}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'ip' => $this->string(50),
            'useragent' => $this->string(100),
            'expires' => $this->timestamp()->defaultValue(null),
            'hash' => $this->string(128)->notNull(),
        ],Constants::DB_TABLE_OPTIONS);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_sessions-user_id}}',
            '{{%user_sessions}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_sessions-user_id}}',
            '{{%user_sessions}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_sessions-user_id}}',
            '{{%user_sessions}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_sessions-user_id}}',
            '{{%user_sessions}}'
        );

        $this->dropTable('{{%user_sessions}}');
    }
}
