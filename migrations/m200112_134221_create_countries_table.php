<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%countries}}`.
 */
class m200112_134221_create_countries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%countries}}', [
            'id' => $this->primaryKey(),
            'iso' => $this->string(2)->notNull(),
            'name' => $this->string(80)->notNull(),
            'nicename' => $this->string(80)->notNull(),
            'iso3' => $this->string(3),
            'numcode' => $this->integer(6),
            'phonecode' => $this->integer(5)->notNull(),
        ],Constants::DB_TABLE_OPTIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%countries}}');
    }
}
