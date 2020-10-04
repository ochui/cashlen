<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_login_history}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%countries}}`
 */
class m200112_160831_create_user_login_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_login_history}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'ip' => $this->string(64)->notNull(),
            'useragent' => $this->string(100)->notNull(),
            'country_id' => $this->integer(),
        ],Constants::DB_TABLE_OPTIONS);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_login_history-user_id}}',
            '{{%user_login_history}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_login_history-user_id}}',
            '{{%user_login_history}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-user_login_history-country_id}}',
            '{{%user_login_history}}',
            'country_id'
        );

        // add foreign key for table `{{%countries}}`
        $this->addForeignKey(
            '{{%fk-user_login_history-country_id}}',
            '{{%user_login_history}}',
            'country_id',
            '{{%countries}}',
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
            '{{%fk-user_login_history-user_id}}',
            '{{%user_login_history}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_login_history-user_id}}',
            '{{%user_login_history}}'
        );

        // drops foreign key for table `{{%countries}}`
        $this->dropForeignKey(
            '{{%fk-user_login_history-country_id}}',
            '{{%user_login_history}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-user_login_history-country_id}}',
            '{{%user_login_history}}'
        );

        $this->dropTable('{{%user_login_history}}');
    }
}
