<?php
    return [
        'components' => [
            'elasticsearch' => [
                'class' => 'yii\elasticsearch\Connection',
                'nodes' => [
                    ['http_address' => '127.0.0.1:9200'],
                ],
            ],
            'commonDb'      => [
                'class'               => 'yii\db\Connection',
                'dsn'                 => 'mysql:host=localhost;dbname=db_common',
                'username'            => 'adm_shopnseo',
                'password'            => 'mQqyBHBq)(*^&*^(u1a84Yju',
                'charset'             => 'utf8',
                'enableSchemaCache'   => true,
                'schemaCacheDuration' => 600,
                'schemaCache'         => 'yii\caching\MemCache',
                'enableQueryCache'    => true,
                'queryCacheDuration'  => 3600,
            ],
            'db'            => [
                'class'               => 'yii\db\Connection',
                'dsn'                 => 'mysql:host=localhost;dbname=dbshop_101',
                'username'            => 'adm_shopnseo',
                'password'            => 'mQqyBHBq)(*^&*^(u1a84Yju',
                'charset'             => 'utf8',
                'enableSchemaCache'   => true,
                'schemaCacheDuration' => 600,
                'schemaCache'         => 'yii\caching\MemCache',
                'enableQueryCache'    => true,
                'queryCacheDuration'  => 3600,
            ],
            'mailer'        => [
                'class'            => 'yii\swiftmailer\Mailer',
                'viewPath'         => '@common/mail',
                'htmlLayout'       => 'layouts/html',
                'textLayout'       => 'layouts/text',
                'useFileTransport' => false,
                'transport'        => [
                    'class'      => 'Swift_SmtpTransport',
                    'host'       => 'mail.netangels.ru',
                    'username'   => 'post@domain.zone',
                    'password'   => 'HGJ*()_)hmRQH58tx9',
                    'port'       => '25',
                    'encryption' => 'tls',
                ],
                'messageConfig'    => [
                    'from' => [
                        'post@domain.zone' => 'post@domain.zone',
                    ],
                ],
            ],
        ],
    ];
