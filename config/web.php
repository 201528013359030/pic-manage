<?php

$params = require(__DIR__ . '/params.php');
$response = require(__DIR__ . '/response.php');
Yii::$classMap['nusoap_client'] = '@app/libs/nusoap/nusoap.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'webService' => [
            'class' => 'app\modules\webService\api',
        ],
        'frame' => [
        'class' => 'app\modules\frame\frame',
        ],
    ],

    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5ZSVNcMs3wP00Ss5zrnK3BsJe6uBXDT7',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => $response,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',//模型自动登录
            'enableAutoLogin' => true,
            'loginUrl'=>['admin/index/login'],//定义后台默认登录界面[权限不足跳到该页]
        ],
        
        'errorHandler' => [
            'errorAction' => 'site/error',
            //'errorAction' => 'webService/main/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
          //  'port' => 48720,
            'database' => 11,
        ],
        'es' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
            ],
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
            ],
        ],
        'ict2db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=ict2', // MySQL, MariaDB
            'username' => 'root', //数据库用户名
            'password' => 'psw.db7898', //数据库密码
            'charset' => 'utf8',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug']['class'] = 'yii\debug\Module';
    $config['modules']['debug']['allowedIPs'] = ['127.0.0.1', '*', '192.168.139.*'];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii']['class'] = 'yii\gii\Module';
    $config['modules']['gii']['allowedIPs'] = ['127.0.0.1', '::1', '192.168.139.*'];
}

return $config;
