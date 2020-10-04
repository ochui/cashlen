<?php

use yii\db\Migration;

/**
 * Class m200925_144154_create_captcha_settings
 */
class m200925_144154_create_captcha_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'id' => \app\common\Constants::SETTINGS_RE_CAPTCHA_SITE_KEY,
            'name' => 'Google Re-Captcha Site Key',
            'value' => '6LeqOewUAAAAADs_cfzGB8YXVYw90aektgG_M0sM'
        ]);
        $this->insert('settings', [
            'id' => \app\common\Constants::SETTINGS_RE_CAPTCHA_SECRET_EY,
            'name' => 'Google Re-Captcha Secret Key',
            'value' => '6LeqOewUAAAAAL8cdRkYjiWCg9v6IYhDlbwdf0mQ'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('settings', [
            'id' => \app\common\Constants::SETTINGS_RE_CAPTCHA_SITE_KEY,
        ]);
        $this->delete('settings', [
            'id' => \app\common\Constants::SETTINGS_RE_CAPTCHA_SECRET_EY,
        ]);
    }
}
