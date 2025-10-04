<?php
    
    namespace frontend\assets;
    
    use yii\bootstrap5\BootstrapPluginAsset;
    use yii\web\AssetBundle;
    use yii\web\JqueryAsset;
    
    /**
     * Main frontend application asset bundle.
     * family=Montserrat&
     * family=Open+Sans:wght@300;400;500&
     * family=Oswald:wght@300;400
     */
    class AppAsset extends AssetBundle
    {
        public $baseUrl = '@url';
        public $basePath = '@frontRoot';
        
        public $css
            = [
                'assets/css/bootstrap.min.css',
                'assets/css/fontawesome.min.css',
                'assets/css/magnific-popup.min.css',
                'assets/css/slick.min.css',
                'assets/css/style.css',
                '/css/main.css',
            ];
        
        public $js
            = [
                'assets/js/slick.min.js',
                'assets/js/jquery-ui.min.js',
                'assets/js/jquery.magnific-popup.min.js',
                'assets/js/jquery.counterup.min.js',
                'assets/js/jquery.marquee.min.js',
                'assets/js/imagesloaded.pkgd.min.js',
                'assets/js/isotope.pkgd.min.js',
                'assets/js/main.js',
            ];
        
        public $depends
        = [
            JqueryAsset::class,
            BootstrapPluginAsset::class,
            ];
    }
