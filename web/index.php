<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

/* If cookie is set - then get timezone from it; else set default timezone */
if(isset($_COOKIE['timezone'])) {
    date_default_timezone_set($_COOKIE['timezone']);
} else {
    date_default_timezone_set("Atlantic/St_Helena");
}

(new yii\web\Application($config))->run();