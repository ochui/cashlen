<?php
$themeUrl = $_ENV['APP_URL'] . "site/";
$isOutlookEmail = false;
if(isset($isOutlook)){
    $isOutlookEmail = $isOutlook;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= Yii::$app->name ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap');

        * {
            font-family: 'Roboto', sans-serif;
        }

        .main-dreams-time {
            width: 100%;
            background: #f6f6f6;
            padding: 25px;
        }

        .dreams-time {
            width: 50%;
            margin: 0 auto;
            padding: 50px 25px;
            border-radius: 10px;
            color: #fff;
        }
        <?php
        if($isOutlookEmail){
            ?>
            .dreams-time {
                background: #071d3e;
                background-color: #071d3e;
            }
        <?php
        }else{
            ?>
            .dreams-time {
                background: #071d3e;
                background: -moz-linear-gradient(-45deg,  #071d3e 0%, #4168a1 100%);
                background: -webkit-linear-gradient(-45deg,  #071d3e 0%,#4168a1 100%);
                background: linear-gradient(135deg,  #071d3e 0%,#4168a1 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#071d3e', endColorstr='#4168a1',GradientType=1 );
            }
        <?php
        }
        ?>

        .header img {
            height: 49px;
            width: auto;
        }
        .dreams-time .header {
            text-align: center;
        }

        .col-4 {
            width: 33.3%;
        }

        .body {
            font-size: 15px;
            color: #fff;
            white-space: normal;
            word-break: break-all;
            padding-top: 20px;
        }
        .body *{
            color: #fff !important;
        }
        br {
            margin: 5px;
            display: block;
        }
        #nameSeparator {
            display: block;
            width: 100%;
            height: 10px;
        }
        a{
            color: #fff !important;
        }

        .row {
            display: flex;

        }

        .p-15 {
            padding: 15px;
        }

        .text-right {
            text-align: right;
        }

        .icons img {
            width: 30px;
            padding-left: 5px;
            cursor: pointer;
            padding-bottom: 10px;
        }

        .dis-p {
            font-size: 14px;
            line-height: 20px;
        }

        .pb-20 {
            padding-bottom: 20px;
        }

        .border-img {
            margin-top: -11px;
        }

        .border-img img {
            width: 100%;
        }

        .copy-rights p {
            text-align: center;
            font-size: 11px;
            padding-bottom: 15px;
            margin-bottom: 0;
            margin-top: 3px;
            color: #898b8d;
        }

        .copy-rights {
            margin-top: 15px !important;
        }

        .p-30 {
            padding-top: 30px;
        }

        .blog {
            text-align: center;
            font-size: 12px;
            margin-bottom: 0px;
            padding-top: 35px;
        }

        .bg-image {

            background-size: cover;
        }
        .btn-email{
            font-family: "Open Sans", sans-serif;
            font-size: 13px;
            padding: 11px 33px;
            font-weight: 600;
            background: #464de4;
            color: #fff !important;
            border-radius: 3px;
            margin: 15px 0;
            display: inline-block;
            text-decoration: none;
        }
        .btn-email:hover{
            background: #313ee4;
        }
        @media only screen and (max-width: 991px) {
            .main-dreams-time {
                width: 100%;
            }
            .copy-rights {
                width: 100%;
            }
        }
        @media only screen and (max-width: 767px) {
            .main-dreams-time {
                padding: 0 !important;
                width: 90%;
                margin: 0 auto;
            }
            .dreams-time {
                width: 80%;
                margin: 0;
                padding: 10%;
                margin-top: 30px;
            }
            .email-ddd-body{
                margin: 0;
            }
            .dreams-time .header img {
                height: 35px;
                width: auto;
            }
            .main-dreams-time .body {
                font-size: 14px;
                white-space: normal;
                word-break: normal;
            }
        }
    </style>
</head>
<body class="email-ddd-body">
<div class="main-dreams-time">
    <div class="dreams-time">
        <div class="header">
            <img src="<?=\app\common\Helper::withBaseUrl(Yii::$app->params['logo_white_url'])?>" alt="">
        </div>
        <div class="body">
            <p class=""><?= $message ?></p>
            <?php
            if(isset($btn)){
                ?>
                <div class="w-100" style="text-align: center">
                    <a class="btn btn-email" href="<?=$btn['link']?>"><?=$btn['text']?></a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="copy-rights">
        <p>Copyright © <?= date('Y') ?> <?= Yii::$app->name ?>. All rights reserved.</p>
    </div>
</div>




</body>
</html>
