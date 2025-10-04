<?php
    
    namespace backend\assets;
    
    use yii\web\AssetBundle;
    
    class SummernoteAsset extends AssetBundle
    {
        public $basePath = '@webroot';
        public $baseUrl  = '@web';
        
        public $css
            = [
                'https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css',
                '/vendors/summernote/summernote-bs5.css',
            ];
        
        public $js
            = [
                'https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js',
                '/vendors/summernote/summernote-bs5.js',
                'js/summernote-init.js',
            ];
        
        public $depends
            = [
                'yii\web\JqueryAsset',
                'yii\bootstrap5\BootstrapAsset', // Если используете Bootstrap 5
            ];
    }
