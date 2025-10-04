<?php
    
    return [
        // Основные параметры приложения
        'adminEmail'                    => 'admin@sv-partner.ru',
        'supportEmail'                  => 'support@sv-partner.ru',
        'senderEmail'                   => 'noreply@sv-partner.ru',
        'orderEmail'                    => 'order@sv-partner.ru',
        'senderName'                    => 'sv-partner.ru mailer',
        
        // Пользовательские настройки
        'user.passwordResetTokenExpire' => 3600,              // 1 час
        'user.emailConfirmTokenExpire'  => 3600 * 24,         // 24 часа
        'user.rememberMeDuration'       => 3600 * 24 * 30,    // 30 дней (ваше дополнение)
        'user.passwordMinLength'        => 8,
        'user.passwordMaxLength'        => 128,
        'user.maxLoginAttempts'         => 5,
        'user.lockoutDuration'          => 900,               // 15 минут блокировки
        'user.sessionTimeout'           => 3600 * 2,          // 2 часа неактивности
        'user.enableRegistration'       => !YII_ENV_PROD,     // отключить регистрацию в проде
        'user.requireEmailConfirmation' => YII_ENV_PROD,      // обязательное подтверждение email в проде
        
        // ID текущего сайта (должен быть установлен для каждого окружения)
        'siteId'                        => 1,
        
        // Redis database для разных окружений
        'redis'                         => YII_ENV_PROD ? 0 : 1,
        
        // Настройки длительности кэширования (в секундах)
        'cacheDurations'                => [
            // Базовые интервалы
            'instant'  => YII_ENV_PROD ? 60 : 5,     // 1 мин / 10 сек - для очень быстро меняющихся данных
            'short'    => YII_ENV_PROD ? 300 : 30,     // 5 мин / 30 сек - для частых обновлений
            'medium'   => YII_ENV_PROD ? 1800 : 60,     // 30 мин / 1 мин - стандартный интервал
            'long'     => YII_ENV_PROD ? 3600 : 120,    // 1 час / 2 мин - для стабильных данных
            'extended' => YII_ENV_PROD ? 86400 : 300,    // 24 часа / 5 мин - для редко меняющихся данных
            'static'   => YII_ENV_PROD ? 604800 : 600,    // 7 дней / 10 мин - для статичных данных
            
            // Специфичные для типов данных
            'database' => YII_ENV_PROD ? 1800 : 60,     // Для данных из БД
            'api'      => YII_ENV_PROD ? 600 : 30,     // Для API ответов
            'user'     => YII_ENV_PROD ? 3600 : 120,    // Для пользовательских данных
            'system'   => YII_ENV_PROD ? 86400 : 300,    // Для системных настроек
            'session'  => YII_ENV_PROD ? 7200 : 300,    // Для сессионных данных
            'file'     => YII_ENV_PROD ? 43200 : 600,    // Для файлового кэша (12 часов)
            'search'   => YII_ENV_PROD ? 900 : 60,     // Для результатов поиска (15 мин)
            'menu'     => YII_ENV_PROD ? 14400 : 180,    // Для меню и навигации (4 часа)
            'widgets'  => YII_ENV_PROD ? 7200 : 120,    // Для виджетов (2 часа)
            'reports'  => YII_ENV_PROD ? 1800 : 120,    // Для отчетов (30 мин)
        ],
        
        // Настройки кэширования
        'cache'                         => [
            'enableTagDependency' => YII_ENV_PROD,
            'maxKeys'             => 10000,
            'cleanupProbability'  => YII_ENV_PROD ? 0.01 : 0.1,
            'compressionLevel'    => YII_ENV_PROD ? 6 : 0, // Сжатие данных в проде
            
            // Префиксы для разных типов кэша
            'prefixes'            => [
                'user'     => 'u_',
                'system'   => 'sys_',
                'database' => 'db_',
                'api'      => 'api_',
                'session'  => 'sess_',
                'file'     => 'file_',
                'search'   => 'search_',
                'menu'     => 'menu_',
                'widget'   => 'w_',
                'report'   => 'rep_',
            ],
            
            // Настройки автоочистки
            'autoCleanup'         => [
                'enabled'   => YII_ENV_PROD,
                'interval'  => 3600, // каждый час
                'threshold' => 0.8,  // при заполнении 80%
            ],
        ],
        
        // Мониторинг и отладка кэша
        'cacheMonitoring'               => [
            'enabled'         => !YII_ENV_PROD,
            'logHits'         => !YII_ENV_PROD,
            'logMisses'       => !YII_ENV_PROD,
            'logInvalidation' => !YII_ENV_PROD,
            'showStats'       => YII_DEBUG,
            'trackMemory'     => YII_DEBUG,
            'maxLogEntries'   => 1000,
        ],
        
        // Настройки базы данных
        'database'                      => [
            'connectionTimeout' => YII_ENV_PROD ? 30 : 10,
            'queryTimeout'      => YII_ENV_PROD ? 60 : 30,
            'enableProfiling'   => !YII_ENV_PROD,
            'enableLogging'     => !YII_ENV_PROD,
        ],
        
        // Настройки безопасности
        'security'                      => [
            'passwordHashCost' => YII_ENV_PROD ? 13 : 10,
            'csrfValidation'   => true,
        ],
        
        // Настройки API
        'api'                           => [
            'version'         => '1.0',
            'rateLimit'       => YII_ENV_PROD ? 100 : 1000, // запросов в час
            'timeout'         => 30,
            'retryAttempts'   => 3,
            'enableCors'      => !YII_ENV_PROD,
            'defaultPageSize' => 20,
            'maxPageSize'     => YII_ENV_PROD ? 100 : 1000,
        ],
        
        // Настройки логирования
        'logging'                       => [
            'enableDbLogging'   => !YII_ENV_PROD,
            'enableFileLogging' => true,
            'maxLogFiles'       => YII_ENV_PROD ? 30 : 7,
            'logRotateSize'     => '10MB',
            'flushInterval'     => YII_ENV_PROD ? 1000 : 1,
        ],
        
        // Настройки файлов и загрузок
        'files'                         => [
            'maxUploadSize'     => '10MB',
            'allowedExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
            'uploadPath'        => '@webroot/uploads',
            'tempPath'          => '@runtime/temp',
            'enableCompression' => YII_ENV_PROD,
        ],
        
        // Настройки производительности
        'performance'                   => [
            'enableGzip'        => YII_ENV_PROD,
            'enableMinify'      => YII_ENV_PROD,
            'enableAssetBundle' => YII_ENV_PROD,
            'enableOpCache'     => YII_ENV_PROD,
            'memoryLimit'       => '256M',
            'executionTime'     => YII_ENV_PROD ? 300 : 0,
        ],
        
        // Настройки уведомлений
        'notifications'                 => [
            'email' => [
                'enabled'   => true,
                'fromEmail' => 'noreply@sv-partner.ru',
                'fromName'  => 'System Notifications',
                'replyTo'   => 'support@sv-partner.ru',
            ],
            'sms'   => [
                'enabled'  => YII_ENV_PROD,
                'provider' => 'twilio', // или другой провайдер
            ],
            'push'  => [
                'enabled' => false,
            ],
        ],
        
        // Настройки для разных окружений
        'environment'                   => [
            'name'                => YII_ENV,
            'debug'               => YII_DEBUG,
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => YII_ENV_PROD,
        ],
        
        // Настройки интеграций
        'integrations'                  => [
            'elasticsearch' => [
                'enabled' => false,
                'hosts'   => ['localhost:9200'],
                'index'   => 'main',
            ],
            'redis'         => [
                'sentinels'     => [],
                'clusterNodes'  => [],
                'enableCluster' => false,
            ],
            'memcached'     => [
                'servers' => [
                    ['host' => 'localhost', 'port' => 11211, 'weight' => 1],
                ],
                'options' => [],
            ],
        ],
        
        // Настройки мониторинга системы
        'monitoring'                    => [
            'enabled'         => YII_ENV_PROD,
            'metricsEndpoint' => '/metrics',
            'healthEndpoint'  => '/health',
            'enableProfiler'  => !YII_ENV_PROD,
            'trackQueries'    => !YII_ENV_PROD,
            'trackCache'      => !YII_ENV_PROD,
        ],
    ];
