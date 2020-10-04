<?php
ini_set('memory_limit', '512M');

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__.'/../.env');

$yiiDebug = $_ENV['YII_DEBUG'];
if($yiiDebug==='false'){
    $yiiDebug = false;
}else{
    $yiiDebug = true;
}

if (array_key_exists('HTTP_HOST', $_SERVER)) {
    if (strpos($_ENV['APP_URL'], 'www') !== false && strpos($_SERVER['HTTP_HOST'], 'www') === false) {
        $urlToGo = rtrim($_ENV['APP_URL'],'/');
        $urlToGo = $urlToGo . ($_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : '');
        header("Location: " . $urlToGo);
        exit(0);
    }
}

defined('YII_DEBUG') or define('YII_DEBUG', $yiiDebug);
defined('YII_ENV') or define('YII_ENV', $_ENV['YII_ENV']);

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';


$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
