<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'=> 'ru',
    'aliases' => [
        '@bower' => '@vendor/bower',
        '@npm'   => '@vendor/npm-asset',
        '@root'   => '/var/www/www-root/data/www/getjurist.host/',
        '@web' => 'http://getjurist.host/',
        '@api' => 'http://api.getjurist.com'
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'LtZwZbAL1Z0syBA-QaGdbP0xHQToujhn',
            'baseUrl'=>''
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
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
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=getjurist',
            'username' => 'getjurist',
            'password' => 'hCbS4jMA2PNq3rvx',
            'charset' => 'utf8mb4',
        ],
        'dbChat' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=ejabberd',
            'username' => 'ejabberd',
            'password' => '8O6o3G6s',
            'charset' => 'utf8mb4',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'search/<city_id:\d+>/<interest_id:\w+>' => 'site/search',
                'search/<city_id:\d+>/' => 'site/search',
                'search/<interest_id:\w+>/' => 'site/search',
                'profile/<user_id:\d+>' => 'profile/index',
                'map/<city_id:\d+>/<interest_id:\w+>' => 'site/map',
                'map/<city_id:\d+>/' => 'site/map',
                'map/<interest_id:\w+>/' => 'site/map',
                'map/' => 'site/map',
                'settings/' => 'my/settings',
                'logout' => 'site/logout',
                'login' => 'site/login',
                'news' => 'my/news',
                'messenger' => 'my/chat',
                'confirm/<hash:\w+>' => 'site/confirm',
                'reset-password/<hash:\w+>' => 'site/reset',
                'send-reset-password' => 'site/sendreset',
                'save-reset-password' => 'site/savereset',
                'messages' => 'my/messages',
                'view_jurist' => 'admin/admin',
                'del_cert' => 'post/del-cert',
				'admin/verification_passport'=>'admin/verification-passport',
				'admin/view_user/<user_id:\d+>'=>'admin/view-uesr',
                'get_map_avatar' => 'site/get-map-avatar',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
