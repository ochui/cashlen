<?php

use yii\db\Migration;

/**
 * Class m200930_055948_add_pixel_setting
 */
class m200930_055948_add_pixel_setting extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'id' => \app\common\Constants::SETTINGS_THANKS_PAGE_PIXEL_CODE,
            'name' => 'Thank You Page - Pixel Code',
            'value' => '-'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('settings', [
            'id' => \app\common\Constants::SETTINGS_THANKS_PAGE_PIXEL_CODE,
        ]);
    }

}
