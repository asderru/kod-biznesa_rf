<?php
    
    /* @var array $params */
    
    $config = [
        'id'         => 'babylon-bar',
        'name'       => 'domain.zone',
        'components' => [
            'formatter' => [
                'locale'         => 'ru-RU',
                'timeZone'       => 'Europe/Moscow',
                'dateFormat'     => 'php:d-m-Y',
                'datetimeFormat' => 'php:d-m-Y в H:i:s',
                'timeFormat'     => 'php:H:i:s',
                'currencyCode'   => 'RUB',
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
