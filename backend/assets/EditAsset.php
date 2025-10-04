<?php
    
    namespace backend\assets;
    
    use yii\web\AssetBundle;
    use yii\web\YiiAsset;
    
    class EditAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl  = '@web';
        
        public $js
            = [
                '/js/slugGenerator.js',
            ];
        
        public $jsOptions
            = [
                'charset' => 'utf-8',
            ];
        
        public $depends
            = [
                YiiAsset::class,
            ];
    }
