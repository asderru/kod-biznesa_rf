<?php
    
    use core\tools\params\Prefix;
    use frontend\urls\AnonsRule;
    use frontend\urls\BloggerRule;
    use frontend\urls\BookRule;
    use frontend\urls\BrandRule;
    use frontend\urls\CategoryRule;
    use frontend\urls\ChapterRule;
    use frontend\urls\FaqRule;
    use frontend\urls\NewsRule;
    use frontend\urls\PageRule;
    use frontend\urls\PostRule;
    use frontend\urls\ProductRule;
    use frontend\urls\RazdelRule;
    use frontend\urls\SeoRule;
    use frontend\urls\ThreadRule;
    use yii\web\UrlManager;
    
    /** @var array $params */
    /** @var string $pagePrefix */
    /** @var string $bookPrefix */
    /** @var string $chapterPrefix */
    
    return [
        'class'           => UrlManager::class,
        'hostInfo' => Yii::$app->params['frontendHostInfo'],
        'baseUrl'  => Yii::$app->params['frontendHostInfo'],
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
