<?php
    
    namespace backend\assets;
    
    use yii\web\AssetBundle;
    use yii\web\YiiAsset;
    
    /**
     * Main backend application asset bundle.
     */
    class ServerAppAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl  = '@web';
        
        public $css
            = [
                'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap',
                '/vendors/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css',
                '/css/light.css',
                '/css/addition.css',
            ];
        
        public $js
            = [
                '/js/main.js',
            ];
        
        public $depends
            = [
                YiiAsset::class,
            ];
    }
