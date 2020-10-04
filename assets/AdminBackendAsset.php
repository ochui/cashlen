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
class AdminBackendAsset extends AssetBundle
{
    public $basePath = '@webroot/backend/';
    public $baseUrl = '@web/backend/';
    public $css = [
        'assets/plugins/bootstrap/css/bootstrap.min.css',
        'assets/css/style.css',
        'assets/plugins/scroll-bar/jquery.mCustomScrollbar.css',
        'assets/plugins/horizontal-menu/dropdown-effects/fade-down.css',
        'assets/plugins/horizontal-menu/horizontalmenu.css',
        'assets/plugins/bootstrap-daterangepicker/daterangepicker.css',
        'assets/plugins/sidebar/sidebar.css',
        'assets/plugins/accordion1/css/easy-responsive-tabs.css',
        'assets/plugins/accordion/accordion.css',
        'assets/plugins/owl-carousel/owl.carousel.css',
        'assets/plugins/iconfonts/plugin.css',
        'assets/plugins/iconfonts/icons.css',
        'assets/fonts/fonts/font-awesome.min.css',
        'assets/plugins/morris/morris.css',
        'assets/css/currency.css',
        'assets/css/custom.css?v=0.101',
    ];
    public $js = [
        'assets/plugins/bootstrap/popper.min.js',
        'assets/plugins/bootstrap/js/bootstrap.min.js',
        'assets/js/vendors/jquery.sparkline.min.js',
        'assets/js/vendors/circle-progress.min.js',
        'assets/plugins/rating/jquery.rating-stars.js',
        'assets/plugins/moment/moment.min.js',
        'assets/plugins/bootstrap-daterangepicker/daterangepicker.js',
        'assets/plugins/horizontal-menu/horizontalmenu.js',
        'assets/plugins/toggle-sidebar/sidemenu.js',
        'assets/plugins/accordion1/js/easyResponsiveTabs.js',
        'assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js',
        'assets/plugins/owl-carousel/owl.carousel.js',
        'assets/plugins/owl-carousel/owl-main.js',
        'assets/plugins/sidebar/sidebar.js',
        'assets/plugins/chart/utils.js',
        'assets/plugins/counters/jquery.missofis-countdown.js',
        'assets/plugins/counters/counter.js',
        'assets/plugins/morris/raphael-min.js',
        'assets/plugins/accordion/accordion.min.js',
        'assets/plugins/date-picker/spectrum.js',
        'assets/plugins/date-picker/jquery-ui.js',
        'assets/plugins/input-mask/jquery.maskedinput.js',
        'assets/plugins/morris/morris.js',
        'assets/js/custom.js',
        'assets/js/main.js?v=0.100',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
