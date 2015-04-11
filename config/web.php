<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'es',
    'aliases' => [
        '@phpexcel' => '@vendor/phpexcel/',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'sssss3234234',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'baseUrl' => 'http://localhost/pago_artistas/web/',
            'rules' => [
            ],
        ],
        'session' => array(
            'timeout' => 21600,
        ),
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => 21600
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                        'class'      => 'Swift_SmtpTransport',
                        'host'       => 'smtp.gmail.com',
                        'username'   => 'sgg.2020@gmail.com',
                        'password'   => 'salvigun 1234',
                        'port'       => '587',
                        'encryption' => 'tls',
            ],
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
                 'dsn' => 'pgsql:host=127.0.0.1;dbname=arquitectura',
		 'username' => 'postgres',
		 'password' => 'uteg1501',
		 'charset' => 'utf8'
                ],
        'db_siga' =>[
                 'class' => 'yii\db\Connection',
                 'dsn' => 'mysql:host=186.5.76.142;dbname=siga', //maybe other dbms such as psql,...
                 'username' => 'sis_siga',
                 'password' => 'sissiga123'
                ]
    ],
    'params' => $params,
];

//configuration adjustments for 'dev' environment
//$config['bootstrap'][] = 'debug';
//$config['modules']['debug'] = 'yii\debug\Module';

$config['bootstrap'][] = 'gii';

//Carga automatica de los modulos
require(__DIR__ . '/modules.php');
$config['modules'] = $arr_mod ["modulos"];




$config['modules']['gii'] = 'yii\gii\Module';
return $config;
