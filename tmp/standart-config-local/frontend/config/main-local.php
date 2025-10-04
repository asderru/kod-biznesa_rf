<?php
    
    /* @var array $params */
    
    $config = [
        'id'         => 'xxxxxx',
        'name'       => 'xxxxxx.zzzzzz',
        'components' => [
            'formatter' => [
                'locale'         => 'ru-RU',
                'timeZone'       => 'Europe/Moscow',
                'dateFormat'     => 'php:d-m-Y',
                'datetimeFormat' => 'php:d-m-Y в H:i:s',
                'timeFormat'     => 'php:H:i:s',
                'currencyCode'   => 'RUB',
            ],
            'log' => [
                'targets' => [
                    [
                        'class'       => 'yii\log\FileTarget',
                        'levels'      => ['error', 'warning'],
                        'logFile'     => '@logsRoot/frontend.log',
                        'maxLogFiles' => 10,
                    ],
                ],
            ],
            'db'  => [
                'class'           => 'yii\db\Connection',
                'enableProfiling' => true,
                'enableLogging'   => true,
                // остальные параметры подключения к базе данных
            ],
        ],
    ];
    
    if (YII_DEBUG) {
        // configuration adjustments for 'dev' environment
        $config['bootstrap'][]      = 'debug';
        $config['modules']['debug'] = [
            'class'      => 'yii\debug\Module',
            'allowedIPs' => ['*'],
        ];
    }
    
    return $config;
