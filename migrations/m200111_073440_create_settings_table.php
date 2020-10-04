<?php

use app\common\Constants;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%settings}}`.
 */
class m200111_073440_create_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150),
            'value' => $this->text(),
        ],Constants::DB_TABLE_OPTIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
