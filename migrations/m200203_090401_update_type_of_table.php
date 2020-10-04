<?php

use yii\db\Migration;

/**
 * Class m200203_090401_update_type_of_table
 */
class m200203_090401_update_type_of_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tables = [
            'countries',
            'logs',
            'settings',
            'users',
            'user_login_history',
            'user_roles',
            'user_sessions',
            'user_status',
        ];
        foreach ($tables as $table) {
            Yii::$app->db->createCommand("ALTER TABLE `".$table."` ENGINE = INNODB;")->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
