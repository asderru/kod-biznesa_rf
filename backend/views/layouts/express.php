<?php
    
    use backend\assets\AppAsset;
    use backend\tools\TinyHelper;
    use core\edit\entities\User\User;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /** @var yii\web\View $this */
    /** @var string $content */
    /** @var User $user */
    
    AppAsset::register($this);
    const EXPRESS_LAYOUT = '#layouts_simple';
    
    $siteName = Yii::$app->name;
    if (!isset($_SESSION['time'])) {
        /** @noinspection GlobalVariableUsageInspection */
        $_SESSION['time'] = date('d.m.Y H:i:s');
    }
    
    $appType  = Yii::$app->params[('appType')];
    $siteMode = Yii::$app->params[('siteMode')];
    $context  = $this->context->id;
    
    $packName = match ($appType) {
        Constant::APP_ULTIMATE => 'Ultimate',
        Constant::APP_SERVER   => 'Server',
        Constant::APP_BUSINESS => 'Business',
        Constant::APP_STANDART => 'Standart',
        default                => 'Start',
    };
    
    $modeName            = match ($siteMode) {
        Constant::MODE_SHOP     => 'Shop',
        Constant::MODE_MAGAZIN  => 'Magazin',
        Constant::MODE_LIBRARY  => 'Library',
        Constant::MODE_FORUM    => 'Forum',
        Constant::MODE_BLOG     => 'Blog',
        Constant::MODE_AGENCY   => 'Agency',
        Constant::MODE_PRODUCER => 'Producer',
        default                 => 'Site',
    };
    
    /** @noinspection GlobalVariableUsageInspection */
    if (!isset($_SESSION['siteMode'])) {
        /** @noinspection GlobalVariableUsageInspection */
        $_SESSION['siteMode'] = $siteMode;
    }
    
    /** @noinspection GlobalVariableUsageInspection */
    if (!isset($_SESSION['appType'])) {
        /** @noinspection GlobalVariableUsageInspection */
        $_SESSION['appType'] = $appType;
    }
    
    /** @noinspection GlobalVariableUsageInspection */
    if (!isset($_SESSION['packName'])) {
        /** @noinspection GlobalVariableUsageInspection */
        $_SESSION['packName'] = $packName;
    }
    
    /** @noinspection GlobalVariableUsageInspection */
    if (!isset($_SESSION['modeName'])) {
        /** @noinspection GlobalVariableUsageInspection */
        $_SESSION['packName'] = $modeName;
    }
    
    $this->beginPage()
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta
                name='viewport'
                content='width=device-width, initial-scale=1, shrink-to-fit=no'
        >
        <meta
                name='description'
                content='Dashboard'
        >
        <meta name='author' content='test'>
        <meta
                name='keywords'
                content='admin, dashboard,'
        >
        <meta
                name="viewport"
                content="width=device-width, initial-scale=1, shrink-to-fit=no"
        >
        <!--Favicons-->
        <link rel='shortcut icon' href='/favicon.ico' type='image/x-icon'>
        <link rel='icon' href='/favicon.ico' type='image/x-icon'>
        
        <?php
            $this->registerCsrfMetaTags()
        ?>
        <title><?= $siteName . ' | ' . Html::encode($this->title)
            ?></title>
        
        <?php
            $this->head()
        ?>
    </head>

    <body class='bg-light'>
    
    <?php
        $this->beginBody()
    ?>
    <!-- Page content -->
    <div id='page-content'>
        
        <?= $content ?>

    </div>


    <!-- Left Navbar nav -->
    <?php
        echo $this->render(
            '/layouts/bars/footer',
            [
                'modeName' => $modeName,
                'packName' => $packName,
            ],
        );
        TinyHelper::getText();
        TinyHelper::getDescription();
        
        $this->endBody()
    ?>

    </body>
    </html>

<?php
    $this->endPage();
