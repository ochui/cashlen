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
class AuthAsset extends AssetBundle
{
    public $version = '';//check __construct function
    public $basePath = '@webroot/backend/';
    public $baseUrl = '@web/backend/';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
    ];


    public function __construct($config = [])
    {
        $this->version = $_ENV['APP_VERSION'];
        $this->css = [
            'assets/plugins/bootstrap/css/bootstrap.min.css',
            'assets/css/style.css',
            'assets/plugins/scroll-bar/jquery.mCustomScrollbar.css',
            'assets/plugins/toggle-sidebar/sidemenu-dark.css',
            'assets/plugins/bootstrap-daterangepicker/daterangepicker.css',
            'assets/plugins/sidebar/sidebar.css',
            'assets/plugins/bootstrap-daterangepicker/daterangepicker.css',
            'assets/plugins/accordion1/css/easy-responsive-tabs-dark.css',
            'assets/plugins/sidebar/sidebar.css',
            'assets/plugins/iconfonts/plugin.css',
            'assets/plugins/iconfonts/icons.css',
            'assets/fonts/fonts/font-awesome.min.css',
            'assets/css/custom.css?v='.$this->version,
        ];
        $this->js = [
            'assets/plugins/bootstrap/popper.min.js',
            'assets/plugins/bootstrap/js/bootstrap.min.js',
            'assets/js/vendors/jquery.sparkline.min.js',
            'assets/js/vendors/circle-progress.min.js',
            'assets/plugins/rating/jquery.rating-stars.js',
            'assets/plugins/accordion1/js/easyResponsiveTabs.js',
            'assets/plugins/moment/moment.min.js',
            'assets/plugins/bootstrap-daterangepicker/daterangepicker.js',
            'assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js',
            'assets/js/custom.js',
            'assets/js/main.js?v='.$this->version,
        ];
        parent::__construct($config);
    }

}