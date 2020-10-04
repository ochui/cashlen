<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user_status}}`
 * - `{{%countries}}`
 * - `{{%countries}}`
 * - `{{%verification_levels}}`
 * - `{{%countries}}`
 * - `{{%user_roles}}`
 */
class m200112_142700_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'identifier' => $this->string(500)->notNull()->unique(),
            'username' => $this->string(200)->notNull(),
            'first_name' => $this->string(200),
            'last_name' => $this->string(200),
            'email' => $this->string(200)->notNull(),
            'password' => $this->string(600)->notNull(),
            'status_id' => $this->integer()->notNull(),
            'code' => $this->string(800),
            'country_id' => $this->integer(),
            'time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_on' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'is_two_fa' => $this->integer()->notNull()->defaultValue(0),
            'two_fa_secret' => $this->string(200),
            'auth_key' => $this->string(500)->notNull(),
            'ip' => $this->string(50)->notNull(),
            'useragent' => $this->string(200)->notNull(),
            'role_id' => $this->integer()->notNull(),
            'referral_code' => $this->string(200)->notNull(),
            'referred_by' => $this->integer(),
        ],Constants::DB_TABLE_OPTIONS);

        // creates index for column `status_id`
        $this->createIndex(
            '{{%idx-users-status_id}}',
            '{{%users}}',
            'status_id'
        );

        // add foreign key for table `{{%user_status}}`
        $this->addForeignKey(
            '{{%fk-users-status_id}}',
            '{{%users}}',
            'status_id',
            '{{%user_status}}',
            'id',
            'CASCADE'
        );

        // creates index for column `referred_by`
        $this->createIndex(
            '{{%idx-users-referred_by}}',
            '{{%users}}',
            'referred_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-users-referred_by}}',
            '{{%users}}',
            'referred_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-users-country_id}}',
            '{{%users}}',
            'country_id'
        );

        // add foreign key for table `{{%countries}}`
        $this->addForeignKey(
            '{{%fk-users-country_id}}',
            '{{%users}}',
            'country_id',
            '{{%countries}}',
            'id',
            'CASCADE'
        );


        // creates index for column `role_id`
        $this->createIndex(
            '{{%idx-users-role_id}}',
            '{{%users}}',
            'role_id'
        );

        // add foreign key for table `{{%user_roles}}`
        $this->addForeignKey(
            '{{%fk-users-role_id}}',
            '{{%users}}',
            'role_id',
            '{{%user_roles}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user_status}}`
        $this->dropForeignKey(
            '{{%fk-users-status_id}}',
            '{{%users}}'
        );

        // drops index for column `status_id`
        $this->dropIndex(
            '{{%idx-users-status_id}}',
            '{{%users}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-users-referred_by}}',
            '{{%users}}'
        );

        // drops index for column `referred_by`
        $this->dropIndex(
            '{{%idx-users-referred_by}}',
            '{{%users}}'
        );

        // drops foreign key for table `{{%countries}}`
        $this->dropForeignKey(
            '{{%fk-users-country_id}}',
            '{{%users}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-users-country_id}}',
            '{{%users}}'
        );


        // drops foreign key for table `{{%user_roles}}`
        $this->dropForeignKey(
            '{{%fk-users-role_id}}',
            '{{%users}}'
        );


        // drops index for column `role_id`
        $this->dropIndex(
            '{{%idx-users-role_id}}',
            '{{%users}}'
        );
        $this->execute('SET FOREIGN_KEY_CHECKS=0;');
        $this->dropTable('{{%users}}');
        $this->execute('SET FOREIGN_KEY_CHECKS=1;');
    }
}
