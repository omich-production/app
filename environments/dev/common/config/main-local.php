<?php
$config = [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=photo',
            'username' => 'root',
            'password' => 'test',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'view' => [
            'as Debug' => [
                'class' => 'common\components\Debug',
            ],
        ],
    ],
];


use common\components\Debug;

function v($v)
{
    Debug::$varExport[] = $v;
    return $v;
}

function vv($v)
{
    return Debug::$varDump[] = $v;
}

return $config;
