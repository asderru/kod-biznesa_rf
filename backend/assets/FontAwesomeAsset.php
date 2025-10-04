<?php
    
    namespace backend\assets;
    
    use yii\web\AssetBundle;
    use yii\web\View;
    
    class FontAwesomeAsset extends AssetBundle
    {
        public $baseUrl  = '@web';
        public $basePath = '@webroot';
        
        public $css
            = [
                'vendors/fontawesome/css/all.js',
            ];
        
        public $js
            = [
                'vendors/fontawesome/js/all.js',
            ];
        
        public $jsOptions
            = [
                'position' => View::POS_HEAD,
            ];
    }
