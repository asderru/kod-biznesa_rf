<?php
 
	return [
    'components' => [
        'db'       => [
            'class'               => 'yii\db\Connection',
            'dsn'                 => 'mysql:host=localhost;dbname=dbshop_xxx',
            'username'            => 'asder',
            'password'            => '778',
            'charset'             => 'utf8',
            'enableSchemaCache'   => true,
            'schemaCacheDuration' => 600,
            'schemaCache'         => 'yii\caching\MemCache',
            'enableQueryCache'    => true,
            'queryCacheDuration'  => 3600,
        ],
        'mailer'   => [
            'class'            => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@common/mail',
            'htmlLayout'       => 'layouts/html',
            'textLayout'       => 'layouts/text',
            'useFileTransport' => false,
            'transport'        => [
                'class'      => 'Swift_SmtpTransport',
                'host'       => 'smtp.yandex.ru',
                'username'   => 'server@xxxxxx.zzzzzz',
                'password'   => 'HGJlkjnlb{7\M`hmRQ^EH5<8tx96vefas',
                'port'       => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig'    => [
                'from' => [
                    'server@xxxxxx.zzzzzz' => 'server@xxxxxx.zzzzzz'
                ]
            ],
        ],
    ],
];
