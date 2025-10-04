<?php
    /** @noinspection ALL */
    
    use common\auth\Identity;
    use yii\bootstrap5\LinkPager;
    use yii\redis\Connection;
    
    $params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'),
        require(__DIR__ . '/../../common/config/prefix-local.php'),
        require(__DIR__ . '/../../common/config/photos-local.php'),
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php'),
        require(__DIR__ . '/bundles.php'),
    );
    return [
        'basePath'            => dirname(__DIR__),
        'controllerNamespace' => 'backend\controllers',
        'bootstrap'           => [
            'log',
            'common\bootstrap\SetUp',
            'backend\bootstrap\SetUp',
        ],
        'layout'              => 'blank',
        'aliases'             => [
            '@homepage'      => $params['frontendHostInfo'],
            '@frontRoot'     => $params['frontendPath'],
            '@watermark'     => $params['watermarkHostInfo'],
            '@watermarkRoot' => $params['watermarkPath'],
            '@backHost'      => $params['backendHostInfo'],
            '@backRoot'      => $params['backendPath'],
            '@staticHost'    => $params['staticHostInfo'],
            '@staticRoot'    => $params['staticPath'],
            '@uploadHost'    => $params['uploadHostInfo'],
            '@uploadRoot'    => $params['uploadPath'],
            '@filesHost'     => $params['filesHostInfo'],
            '@filesRoot'     => $params['filesPath'],
            '@logsRoot'      => $params['logsPath'],
        ],
        'container'           => [
            'definitions' => [
                \yii\widgets\LinkPager::class => LinkPager::class,
            ],
        ],
        'controllerMap'       => [
            'elfinder' => [
                'class'            => 'backend\widgets\elfinder\Controller',
                'access'           => ['@'], //глобальный доступ к фаил менеджеру @ - для авторизорованных , ? - для гостей , чтоб открыть всем ['@', '?']
                'disabledCommands' => ['netmount'], //отключение ненужных команд https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#commands
                'roots'            => [
                    [
                        'baseUrl'  => '@staticHost',
                        'basePath' => '@staticRoot',
                        'path'     => 'files/global',
                        'name'     => 'global',
                    ],
                ],
                'plugin'           => [
                    [
                        'class'       => '\backend\widgets\elfinder\plugin\Sluggable',
                        'lowercase'   => true,
                        'replacement' => '-',
                    ],
                ],
            ],
        ],
        'components'          => [
            'request'            => [
                'csrfParam'           => '_csrf-backend',
                'cookieValidationKey' => $params['cookieValidationKey'],
            ],
            'assetManager'       => [
                'bundles'         => $bundles,
                'appendTimestamp' => true,
                'assetMap'        => [
                ],
            ],
            'user'               => [
                'identityClass'   => Identity::class,
                'enableAutoLogin' => true,
                'identityCookie'  => [
                    'name'     => '_identity',
                    'httpOnly' => true,
                    'domain'   => $params['cookieDomain'],
                ],
                'loginUrl'        => [
                    'auth/login',
                ],
            ],
            'session'            => [
                'name'         => '_session',
                'cookieParams' => [
                    'domain'   => $params['cookieDomain'],
                    'httpOnly' => true,
                ],
            ],
            'log'                => [
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets'    => [
                    [
                        'class'       => 'yii\log\FileTarget',
                        'levels'      => ['error', 'warning'],
                        'logFile'     => '@logsRoot/backend.log',
                        'maxLogFiles' => 10,
                    ],
                ],
            ],
            'errorHandler'       => [
                'errorAction'    => 'site/error',
                'maxSourceLines' => 20,
            ],
            'redis'              => [
                'class'      => Connection::class,
                //'unixSocket' => '/var/run/redis/redis.sock',
                'unixSocket' => null,
                'hostname'   => 'localhost',
                'port'       => 6379,
                'database'   => $params['redis'],
            ],
            'phpMorphy'          => [
                'class' => 'cijic\phpMorphy\Morphy',
            ],
            'backendUrlManager'  => require __DIR__ . '/urlManager.php',
            'frontendUrlManager' => require __DIR__ . '/../../frontend/urls/urlManager.php',
            'urlManager'         => function () {
                return Yii::$app->get('backendUrlManager');
            },
        ],
        'as access'           => [
            'class'  => 'yii\filters\AccessControl',
            'except' => [
                'auth/login',
            ],
            'rules'  => [
                [
                    'allow' => true,
                    'roles' => [
                        '@',
                    ],
                ],
            ],
        ],
        'params'              => $params,
    ];
