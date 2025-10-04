<?php
    
    use yii\web\UrlManager;
    
    /** @var array $params */
    /** @var string $pagePrefix */
    /** @var string $bookPrefix */
    /** @var string $chapterPrefix */
    
    return [
        'class'           => UrlManager::class,
        'hostInfo'        => Yii::$app->params['frontendHostInfo'],
        'baseUrl'         => Yii::$app->params['frontendHostInfo'],
        'enablePrettyUrl' => true,
        'showScriptName'  => false,
        'cache'           => false,
        'rules'           => [
            '' => 'site/index',
            
            ['pattern' => 'browserconfig', 'route' => 'site/browserconfig', 'suffix' => '.xml'],
            ['pattern' => 'site', 'route' => 'site/webmanifest', 'suffix' => '.webmanifest'],
            ['pattern' => 'robots', 'route' => 'site/robots', 'suffix' => '.txt'],
        
        ],
    ];
