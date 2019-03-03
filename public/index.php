<?php

use Symfony\Component\Dotenv\Dotenv;
use yii\web\Application;

require(__DIR__ . '/../vendor/autoload.php');

// The check is to ensure we don't use .env in production
if (!isset($_SERVER['APP_ENV'])) {
    (new Dotenv())->load(__DIR__.'/../.env');
}

defined('YII_DEBUG') or define('YII_DEBUG', getenv('APP_DEBUG'));
defined('YII_ENV') or define('YII_ENV', getenv('APP_ENV'));

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new Application($config))->run();
