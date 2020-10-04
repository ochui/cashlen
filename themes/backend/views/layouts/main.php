<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\BackendAsset;
use app\common\Constants;
use app\common\Helper;
use app\models\activerecord\Leads;
use app\models\activerecord\Users;
use app\widgets\Alert;
use lo\modules\noty\Wrapper;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

BackendAsset::register($this);

/**
 * @var Users $identity
 */
$identity = Yii::$app->user->identity;
$session = Yii::$app->session;


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <base href="<?=Yii::$app->getHomeUrl();?>backend/"/>
    <link rel="icon" href="<?=Helper::withBaseUrl(Yii::$app->params['fav_icon'])?>">

    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?=Helper::getWebsiteTitle()?></title>
    <?php $this->head() ?>
    <script>
        const baseUrl = '<?=Helper::withBaseUrl('')?>';
    </script>
    <style>
        .goog-te-banner-frame.skiptranslate {
            display: none;
        }
        body {
            top: 0 !important;
        }
    </style>
</head>
<body class="app sidebar-mini rtl">
<?php $this->beginBody() ?>

<!--Global-Loader-->
<div id="global-loader">
    <img src="assets/images/icons/loader.svg" alt="loader">
</div>

<div class="page">
    <div class="page-main">

        <!--app-header-->
        <div class="app-header header d-flex">
            <div class="container-fluid">
                <div class="d-flex">
                    <a class="header-brand logo-saver" href="<?=Url::toRoute(['user/index'])?>">
                        <img src="<?= Helper::withBaseUrl(Yii::$app->params['logo_url']) ?>"
                             class="header-brand-img main-logo" alt="Logo">
                        <img src="<?= Helper::withBaseUrl(Yii::$app->params['logo_url']) ?>"
                             class="header-brand-img icon-logo" alt="Logo">
                    </a><!-- logo-->
                    <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="javascrip:;"></a>
                    <div class="d-flex order-lg-2 ml-auto header-rightmenu">
                        <div class="dropdown">
                            <a  class="nav-link icon full-screen-link" id="fullscreen-button">
                                <i class="fe fe-maximize-2"></i>
                            </a>
                        </div>

                        <div class="dropdown header-user">
                            <a class="nav-link leading-none siderbar-link"  data-toggle="sidebar-right" data-target=".sidebar-right">
										<span class="mr-3 d-none d-lg-block ">
											<span class="text-gray-white"><span class="ml-2"><?=$identity->getPublicName()?></span></span>
										</span>
                                <span class="avatar avatar-md brround"><img src="<?=$identity->getProfilePicture()?>" alt="Profile-img" class="avatar avatar-md brround"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <div class="header-user text-center mt-4 pb-4">
                                    <span class="avatar avatar-xxl brround"><img src="<?=$identity->getProfilePicture()?>" alt="Profile-img" class="avatar avatar-xxl brround"></span>
                                    <a href="javascript:;" class="dropdown-item text-center font-weight-semibold user h3 mb-0"><?=$identity->getPublicName()?></a>
                                    <small>Email <?=$identity->email?></small>
                                </div>
                                <div class="card-body border-top">
                                    <div class="row">
                                        <div class="col-6 text-center">
                                            <a class="" href="<?=Url::toRoute(['user/logout'])?>"><i class="dropdown-icon mdi mdi-logout-variant fs-30 m-0 leading-tight"></i></a>
                                            <div>Sign out</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- profile -->
                        <div class="dropdown">
                            <a  class="nav-link icon siderbar-link" data-toggle="sidebar-right" data-target=".sidebar-right">
                                <i class="fe fe-more-horizontal"></i>
                            </a>
                        </div><!-- Right-siebar-->
                    </div>
                </div>
            </div>
        </div>
        <!--app-header end-->

        <!-- Sidebar menu-->
        <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
        <aside class="app-sidebar">
            <a href="javascript:;" id="closeMenu">
                <i class="icon icon-close"></i>
            </a>
            <div class="app-sidebar__user pb-0">
                <div class="user-body">
                    <span class="avatar avatar-xxl brround text-center cover-image" data-image-src="<?=$identity->getProfilePicture()?>"></span>
                </div>
                <div class="user-info">
                    <a href="javascript:;" class="ml-2"><span class="text-dark app-sidebar__user-name font-weight-semibold"><?=$identity->getPublicName()?></span></a>
                </div>
            </div>

            <div class="tab-menu-heading siderbar-tabs border-0 p-0">
                <div class="tabs-menu ">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs">
                        <li class=""><a href="<?=Url::toRoute(['user/index'])?>" class="active"><i class="fa fa-home fs-17"></i></a></li>
                        <li><a href="<?=Url::toRoute(['user/profile'])?>" ><i class="fa fa-user fs-17"></i></a></li>
                        <li><a href="<?=Url::toRoute(['user/logout'])?>" title="logout"><i class="fa fa-power-off fs-17"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body tabs-menu-body side-tab-body p-0 border-0 ">
                <div class="tab-content">
                    <div class="tab-pane active " id="index1">
                        <ul class="side-menu toggle-menu">

                            <li style="display: none" >
                                <div id="google_translate_element"></div>
                            </li>

                            <li class="slide">
                                <a class="side-menu__item" href="<?= Url::toRoute(['user/index']) ?>">
                                    <i class="side-menu__icon si si-grid"></i><span
                                        class="side-menu__label">Dashboard</span>
                                </a>
                            </li>

                            <?php if($identity->role_id==Constants::USER_ROLE_ADMIN) { ?>

                                <li class="slide">
                                    <a class="side-menu__item" href="<?= Url::toRoute(['submissions/index']) ?>">
                                        <i class="side-menu__icon si si-people"></i><span
                                            class="side-menu__label">Submissions</span>
                                    </a>
                                </li>

                                <li class="slide">
                                    <a class="side-menu__item" href="<?= Url::toRoute(['settings/index']) ?>">
                                        <i class="side-menu__icon si si-settings"></i><span
                                                class="side-menu__label">Settings</span>
                                    </a>
                                </li>

                            <?php } ?>

                            <?php if($identity->role_id==Constants::USER_ROLE_USER) { ?>

                            <?php } ?>

                            <li class="slide">
                                <a class="side-menu__item" href="<?= Url::toRoute(['user/profile']) ?>">
                                    <i class="side-menu__icon si si-user"></i><span
                                        class="side-menu__label">Update Profile</span>
                                </a>
                            </li>


                            <li class="slide">
                                <a class="side-menu__item" href="<?= Url::toRoute(['user/logout']) ?>">
                                    <i class="side-menu__icon si si-logout"></i><span
                                        class="side-menu__label">Logout</span>
                                </a>
                            </li>


                        </ul>
                    </div>

                </div>
            </div>
        </aside>
        <!--sidemenu end-->

        <!-- app-content-->
        <div class="app-content  my-3 my-md-5">
            <div class="side-app">

                <?=$content?>

            </div><!--End side app-->

            <!-- Right-sidebar-->
            <div class="sidebar sidebar-right sidebar-animate">
                <div class="panel-body tabs-menu-body side-tab-body p-0 border-0 ">
                    <div class="tab-content border-top">
                        <div class="tab-pane active " id="tab">
                            <div class="card-body p-0">
                                <div class="header-user text-center mt-4 pb-4">
                                    <span class="avatar avatar-xxl brround"><img src="<?=$identity->getProfilePicture()?>" alt="Profile-img" class="avatar avatar-xxl brround"></span>
                                    <div class="dropdown-item text-center font-weight-semibold user h3 mb-0"><?=$identity->getPublicName()?></div>
                                </div>
                                <div class="card-body border-top">
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <a class="" href="<?=Url::toRoute(['user/index'])?>"><i class="dropdown-icon mdi  mdi-view-dashboard fs-30 m-0 leading-tight"></i></a>
                                            <div>Dashboard</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <a class="" href="<?=Url::toRoute(['user/profile'])?>"><i class="dropdown-icon mdi mdi-tune fs-30 m-0 leading-tight"></i></a>
                                            <div>Profile</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <a class="" href="<?=Url::toRoute(['user/logout'])?>"><i class="dropdown-icon mdi mdi-logout-variant fs-30 m-0 leading-tight"></i></a>
                                            <div>Sign out</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <footer class="footer">
                <div class="container">
                    <div class="row align-items-center flex-row-reverse">
                        <div class="col-lg-12 col-sm-12   text-center">
                            Copyright © <?=date('Y')?>
                                <a href="javascript:;"><?=Yii::$app->name?></a>.
                        </div>
                    </div>
                </div>
            </footer>


        </div>

    </div>
</div>

<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

<?php
echo Wrapper::widget([
    'layerClass' => 'lo\modules\noty\layers\Growl',
]);
$this->endBody();
?>
<script>
    $(document).ready(function () {
        if($('.datetimepicker').length>0) {
            $('.datetimepicker').datetimepicker();
        }
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
