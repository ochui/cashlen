<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FrontendAsset extends AssetBundle
{
    public $version = '';//check __construct function
    public $basePath = '@webroot/frontend/';
    public $baseUrl = '@web/frontend/';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function __construct($config = [])
    {
        $this->version = $_ENV['APP_VERSION'];
        $this->css = [
            '//fonts.googleapis.com/css?family=Lato:300,400,700',
            'css/style.css?v='.$this->version,
        ];
        $this->js = [
            '//www.google.com/recaptcha/api.js',
            'js/script.js?v='.$this->version,
        ];
        parent::__construct($config);
    }
}
