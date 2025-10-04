<?php
    
    namespace backend\assets;
    
    use yii\bootstrap5\BootstrapPluginAsset;
    use yii\web\AssetBundle;
    use yii\web\JqueryAsset;
    use yii\web\YiiAsset;
    
    /**
     * Main backend application asset bundle.
     */
    class AppAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl  = '@web';
        
        public $css
            = [
                'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap',
                '/vendors/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css',
                [
                    '/css/light.css',
                    'id' => 'color-scheme',
                ],
                '/css/addition.css',
            ];
        
        public $js
            = [
                '/js/main.js',
            ];
        
        public $depends
            = [
                JqueryAsset::class,
                YiiAsset::class,
                BootstrapPluginAsset::class,
            ];
        
    }
