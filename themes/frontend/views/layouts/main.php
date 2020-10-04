<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\common\Constants;
use app\common\Helper;

use app\widgets\Alert;
use lo\modules\noty\Wrapper;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

\app\assets\FrontendAsset::register($this);
$action = Yii::$app->controller->action->id;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <base href="<?=Yii::$app->getHomeUrl();?>frontend/"/>

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
<body data-time="<?=date(Constants::PHP_DATE_FORMAT)?>">
<?php $this->beginBody() ?>

<header>
    <div class="container">
        <a class="logo">
            <img src="img/generic.png" alt="logo">
        </a><span style="vertical-align: middle;padding:10px;display:block;float:left;" class="text-logo">Cash<strong>Lending</strong></span>
        <?php
        if($action!=="index"){
        ?>
        <div class="header-nav-wrapper">
            <div class="close-nav"></div>
            <ul class="nav">
                <li>
                    <a href="<?=Url::toRoute(['site/index'])?>" class="@@default">
                        Home
                    </a>
                </li>
                <li>
                    <a href="<?=Url::toRoute(['site/apply'])?>" class="active">
                        Start Here
                    </a>
                </li>
                <li>
                    <a href="<?=Url::toRoute(['site/rates'])?>" class="@@default">
                        Rates
                    </a>
                </li>
                <li>
                    <a href="<?=Url::toRoute(['site/how'])?>" class="@@default">
                        How it works
                    </a>
                </li>
            </ul>
        </div>
            <div class="open-nav">
                <span></span>
                <span></span>
                <span></span>
            </div>
        <?php } ?>
    </div>
</header>

<?=$content?>

<footer>
    <div class="container">
        <div class="footer-nav-block">
            <div class="nav-wrapper">
                <ul class="footer-nav">
                    <li>
                        <a href="<?=Url::toRoute(['site/apply'])?>" class="@@default">Start Here</a>
                    </li>
                    <li>
                        <a href="<?=Url::toRoute(['site/rates'])?>" class="@@default">Rates</a>
                    </li>
                    <li>
                        <a href="<?=Url::toRoute(['site/terms'])?>" class="@@default">Terms & Conditions</a>
                    </li>
                    <li>
                        <a href="<?=Url::toRoute(['site/privacy'])?>" class="@@default">Privacy Policy</a>
                    </li>
                    <li>
                        <a href="<?=Url::toRoute(['site/privacy-california'])?>" class="@@default">Privacy Notice for<br/>California Residents</a>
                    </li>
                </ul>

                <ul class="footer-nav">
                    <li>
                        <a href="<?=Url::toRoute(['site/about'])?>" class="@@default">About us</a>
                    </li>
                    <li>
                        <a href="<?=Url::toRoute(['site/faq'])?>" class="@@default">FAQ</a>
                    </li>
                    <li>
                        <a href="<?=Url::toRoute(['site/contact'])?>" class="@@default">Contact us</a>
                    </li>
                    <li>
                        <a href="http://www.unsubscribemaster.com/">Unsubscribe</a>
                    </li>
                </ul>
            </div>
            <p class="copyright">
                &copy; <?=date('Y')?> <a href="javascript:;"><?=$_ENV['APP_DOMAIN']?></a> . All Rights Reserved.
            </p>
        </div>
        <div class="footer-content">
            <div class="content">
                <p>
                    <strong>Important Disclosures</strong>: This website does not constitute an offer or
                    solicitation to lend. The operator of this website is NOT A LENDER, does not make loan or credit
                    decisions, and does not broker loans. The operator of this website is not an agent or
                    representative of any lender. We are a lead generator. See <span><a href="<?=Url::toRoute(['site/apply'])?>">certain disclosures regarding lead generation</a></span>
                    for important information about us and about lead generation and aggregation. This website's aim
                    is to provide lenders with information about prospective consumer borrowers. We are compensated
                    by lenders for this service. This website is operated by onlineloannetwork.com. This service and
                    lenders are not available in all states.
                </p>
                <p>
                    <strong>Information about loans</strong>: Not all lenders can provide loan amounts up to
                    $50,000. The maximum amount you may borrow from any lender is determined by the lender based on
                    its own policies, which can vary, and on your creditworthiness. The time to receive loan
                    proceeds varies among lenders, and in some circumstances faxing of loan request form materials
                    and other documents may be required. Submitting your information online does not guarantee that
                    you will be approved for a loan.
                </p>
                <p>
                    Every lender has its own terms and conditions and renewal policy, which may differ from lender
                    to lender. You should review your lender's terms and renewal policy before signing the loan
                    agreement. Late payments of loans may result in additional fees or collection activities, or
                    both.
                </p>
            </div>
        </div>
    </div>
</footer>

<?php
$this->endBody();
?>
</body>
</html>
<?php $this->endPage() ?>
