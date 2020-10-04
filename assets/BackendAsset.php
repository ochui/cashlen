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
class BackendAsset extends AssetBundle
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
            'assets/plugins/accordion1/css/easy-responsive-tabs-dark.css',
            'assets/plugins/accordion/accordion.css',
            'assets/plugins/owl-carousel/owl.carousel.css',
            'assets/plugins/morris/morris.css',
            'assets/plugins/iconfonts/plugin.css',
            'assets/plugins/iconfonts/icons.css',
            'assets/fonts/fonts/font-awesome.min.css',
            '//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css',
            'assets/css/bootstrap-glyphicons.css',
            '//cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.3/css/flag-icon.min.css',
            'assets/css/switchery.min.css',
            'assets/css/custom.css?v='.$this->version,
        ];
        $this->js = [
            'assets/plugins/bootstrap/popper.min.js',
            'assets/plugins/bootstrap/js/bootstrap.min.js',
            'assets/js/vendors/jquery.sparkline.min.js',
            'assets/js/vendors/circle-progress.min.js',
            'assets/plugins/rating/jquery.rating-stars.js',
            'assets/plugins/moment/moment.min.js',
            'assets/plugins/bootstrap-daterangepicker/daterangepicker.js',
            'assets/plugins/toggle-sidebar/sidemenu.js',
            'assets/plugins/accordion1/js/easyResponsiveTabs.js',
            'assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js',
            'assets/plugins/owl-carousel/owl.carousel.js',
            'assets/plugins/owl-carousel/owl-main.js',
            'assets/plugins/sidebar/sidebar.js',
            'assets/plugins/chart/utils.js',
            'assets/plugins/counters/jquery.missofis-countdown.js',
            'assets/plugins/counters/counter.js',
            'assets/plugins/accordion/accordion.min.js',
            '//cdn.jsdelivr.net/npm/clipboard@2/dist/clipboard.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js',
            'assets/js/bootstrap-switch.min.js',
            'assets/js/bootstrap-checkbox.min.js',
            'assets/js/switchery.min.js',
            'assets/js/switch.min.js',
            'assets/plugins/morris/raphael-min.js',
            'assets/plugins/morris/morris.js',
            '//canvasjs.com/assets/script/canvasjs.min.js',
            'assets/js/custom.js?v='.$this->version,
            'assets/js/main.js?v='.$this->version,
        ];
        parent::__construct($config);
    }
}
