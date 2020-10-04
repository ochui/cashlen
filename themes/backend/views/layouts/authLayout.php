<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AuthAsset;
use app\common\Constants;
use app\common\Helper;
use app\widgets\Alert;
use lo\modules\noty\Wrapper;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AuthAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <base href="<?=Yii::$app->getHomeUrl();?>backend/"/>
    <link rel="icon" href="<?=Helper::withBaseUrl(Yii::$app->params['fav_icon'])?>">

    <meta name="description" content="<?=$_ENV['APP_INFO']?>">
    <meta name="og:description" content="<?=$_ENV['APP_INFO']?>">
    <meta name="twitter:description" content="<?=$_ENV['APP_INFO']?>">
    <meta name="language" content="en">
    <meta name="og:locale" content="en-US">
    <meta name="og:type" content="website">
    <meta name="og:title" content="<?=Helper::getWebsiteTitle()?>">
    <meta name="og:url" content="<?=$_ENV['APP_URL']?>">
    <meta name="og:site_name" content="<?=Helper::getWebsiteTitle()?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?=Helper::getWebsiteTitle()?>">

    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags()?>
    <title><?=Helper::getWebsiteTitle()?></title>
    <?php $this->head() ?>
    <script>
        const baseUrl = '<?=Helper::withBaseUrl('')?>';
    </script>
</head>
<body class="bg-account">
<?php $this->beginBody() ?>

<div class="page">
    <div class="page-content">
        <div class="container text-center text-dark">
            <div class="row">
                <div class="col-lg-4 col-md-6 d-block mx-auto">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-md-12">
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="text-center logo-saver mt-5">
                                        <img src="<?=Helper::withBaseUrl(Yii::$app->params['logo_url'])?>" class="" alt="<?=$_ENV['APP_NAME']?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 pr-md-0 d-flex justify-content-center align-items-center col-12">
                                            <div class="row w-100 mx-auto">
                                                <div class="col-md-12 mx-auto">
                                                    <div class="p-5">
                                                        <?=$content?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
echo Wrapper::widget([
    'layerClass' => 'lo\modules\noty\layers\Growl',
]);
$this->endBody();
?>
</body>
</html>
<?php $this->endPage() ?>
