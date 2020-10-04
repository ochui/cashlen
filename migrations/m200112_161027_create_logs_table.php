<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%logs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%countries}}`
 */
class m200112_161027_create_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%logs}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'particulars' => $this->text(),
            'time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'ip' => $this->string(64)->notNull(),
            'useragent' => $this->string(100)->notNull(),
            'type' => $this->string(100),
            'parent_id' => $this->integer()->null(),
            'data' => $this->text(),
            'country_id' => $this->integer(),
        ],Constants::DB_TABLE_OPTIONS);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-logs-user_id}}',
            '{{%logs}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-logs-user_id}}',
            '{{%logs}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-logs-country_id}}',
            '{{%logs}}',
            'country_id'
        );

        // add foreign key for table `{{%countries}}`
        $this->addForeignKey(
            '{{%fk-logs-country_id}}',
            '{{%logs}}',
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
            '{{%fk-logs-user_id}}',
            '{{%logs}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-logs-user_id}}',
            '{{%logs}}'
        );

        // drops foreign key for table `{{%countries}}`
        $this->dropForeignKey(
            '{{%fk-logs-country_id}}',
            '{{%logs}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-logs-country_id}}',
            '{{%logs}}'
        );

        $this->dropTable('{{%logs}}');
    }
}
