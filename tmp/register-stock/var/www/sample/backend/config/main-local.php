<?php
    $config = [
        'id'   => 'babylon-bar',
        'name' => 'panel.babylon-bar',
    ];
    
    if (!YII_ENV_TEST) {
        // configuration adjustments for 'dev' environment
        $config['bootstrap'][]      = 'debug';
        $config['modules']['debug'] = [
            'class'      => 'yii\debug\Module',
            'allowedIPs' => ['*'],
            'panels'     => [
                'queue' => 'yii\queue\debug\Panel',
            ],
        ];
        
        $config['bootstrap'][]    = 'gii';
        $config['modules']['gii'] = [
            'class' => 'yii\gii\Module',
        ];
    }
    
    return $config;
