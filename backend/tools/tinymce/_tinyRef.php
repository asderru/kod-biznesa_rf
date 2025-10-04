<?php
    
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\BootstrapPluginAsset;
    
    /* @var $this yii\web\View */
    
    $tinyKey = Yii::$app->params['tinyKey'] ?? null;
    
    $tinyScript = ($tinyKey)
        ?
        'https://cdn.tiny.cloud/1/' . $tinyKey . '/tinymce/6/tinymce.min.js'
        :
        'https://cdn.tiny.cloud/1/q5ti87sumprlklm72xthexqsmg999azep4kdpfr3naw0envb/tinymce/6/tinymce.min.js';
    
    try {
        $this->registerJsFile(
            $tinyScript,
            ['depends' => [BootstrapPluginAsset::class]],
            'script-tiny',
        );
    }
    catch (InvalidConfigException $e) {
        Yii::error('Error registering JS file: ' . $e->getMessage());
    }
