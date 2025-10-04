<?php
    
    use yii\bootstrap\BootstrapAsset;
    use yii\bootstrap5\BootstrapPluginAsset;
    use yii\web\JqueryAsset;
    
    $bundles = [
        JqueryAsset::class                    => [
            'sourcePath' => null,   // do not publish the bundle
            'js'         => [
                [
                    'https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js',
                ],
            ],
        ],
        BootstrapPluginAsset::class           => [
            'sourcePath' => null,
            'baseUrl'    => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist',
            'js'         => [
                [
                    'js/bootstrap.bundle.min.js',
                    'integrity'   => 'sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL',
                    'crossorigin' => 'anonymous',
                ],
            ],
        ],
        BootstrapAsset::class                 => [
            'sourcePath' => null,
            'baseUrl'    => '/',
            'css'        => [
                [
                    'css/theme.min.css',
                ],
            ],
        ],
        \yii\bootstrap5\BootstrapAsset::class => [
            'sourcePath' => null,
            'baseUrl'    => '/',
            'css'        => [
                [
                    'css/theme.min.css',
                ],
            ],
        ],
    ];
    
    return $bundles;
