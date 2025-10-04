<?php
    
    use common\bootstrap\SetUp;
    use yii\queue\LogBehavior;
    use yii\queue\redis\Queue;
    use yii\redis\Connection;
    
    $params = array_merge(
        require(__DIR__ . '/params-local.php'),
    );

// Определяем, включен ли режим отладки
    $isDebug = defined('YII_DEBUG') && YII_DEBUG;
    
    return [
        'vendorPath' => '/var/www/vendor',
        'bootstrap'  => [
            'queue',
            SetUp::class,
        ],
        'language'   => 'ru-RU',
        'aliases'    => [
            '@bower' => '@vendor/bower-asset',
            '@npm'   => '@vendor/npm-asset',
        ],
        'components' => [
            'queue'          => [
                'class'   => Queue::class,
                'as log'  => LogBehavior::class,
                'channel' => 'queue',
                'ttr'     => 120,
                'redis' => 'redis',
            ],
            'redis'          => [
                'class'      => Connection::class,
                'unixSocket' => null,
                'hostname'   => 'localhost',
                'port'       => 6379,
                'database'   => $params['redis'],
            ],
            
            // ГЛАВНОЕ: В debug режиме используем DummyCache
            'cache'          => $isDebug
                ? [
                    'class' => 'yii\caching\DummyCache',
                ]
                : [
                    'class'           => 'yii\redis\Cache',
                    'defaultDuration' => $params['cacheDurations']['medium'] ?? 1800,
                    'keyPrefix'       => $params['siteId'],
                ],
            'fileCache'      => $isDebug
                ? [
                    'class' => 'yii\caching\DummyCache',
                ]
                : [
                    'class'           => 'yii\caching\FileCache',
                    'cachePath'       => '@common/runtime/cache',
                    'defaultDuration' => $params['cacheDurations']['static'] ?? 86400,
                    'keyPrefix'       => $params['siteId'] . '_file_',
                ],
            'dataCache'      => $isDebug
                ? [
                    'class' => 'yii\caching\DummyCache',
                ]
                : [
                    'class'           => 'yii\caching\DbCache',
                    'defaultDuration' => $params['cacheDurations']['database'] ?? 1800,
                    'keyPrefix'       => $params['siteId'] . '_data_', // ИСПРАВЛЕНО: было 'dbshop_2'
                ],
            'cacheMemcache' => [
                'class' => 'yii\caching\MemCache',
                    'useMemcached'    => true,
                    'defaultDuration' => $params['cacheDurations']['short'] ?? 300,
                    'keyPrefix'       => $params['siteId'] . '_mc_',
                    'servers'         => [
                        [
                            'host' => 'localhost',
                            'port' => 11211,
                        ],
                    ],
                ],
            'elasticsearch' => [
                'class' => 'yii\elasticsearch\Connection',
                'nodes' => [
                    [
                        'http_address' => '127.0.0.1:9200',
                    ],
                ],
            ],
            'authManager'   => [
                'class' => 'core\services\AccessManageService',
                'itemTable'       => '{{%auth_items}}',
                'itemChildTable'  => '{{%auth_item_children}}',
                'assignmentTable' => '{{%auth_assignments}}',
                'ruleTable'       => '{{%auth_rules}}',
            ],
            'formatter'     => [
                'locale'         => 'ru-RU',
                'timeZone'       => 'Europe/Moscow',
                'dateFormat'     => 'php:d-m-Y',
                'datetimeFormat' => 'php:d-m-Y в H:i:s',
                'timeFormat'     => 'php:H:i:s',
                'currencyCode'   => 'RUB',
            ],
        ],
    ];
