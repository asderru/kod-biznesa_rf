<?php

use backend\assets\AppAsset;use core\tools\Constant;use yii\bootstrap5\Html;

/* @var $this yii\web\View */
/* @var $content string */
/* @var $modeName string */
/* @var $packName string */
/* @var $context string */
/* @var $appType int */

AppAsset::register($this);

$siteName = Yii::$app->name;
if (!isset($_SESSION['time'])) {
    /** @noinspection GlobalVariableUsageInspection */
    $_SESSION['time'] = date('d.m.Y H:i:s');
}

$appType = Yii::$app->params[('appType')];
$siteMode = Yii::$app->params[('siteMode')];
$context = $this->context->id;

$packName = match ($appType) {
    Constant::APP_ULTIMATE => 'Ultimate',
    Constant::APP_SERVER   => 'Server',
    Constant::APP_BUSINESS => 'Business',
    Constant::APP_STANDART => 'Standart',
    default                => 'Start',
};

$modeName = match ($siteMode) {
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
?><!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="<?= Yii::$app->language ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="<?= Yii::$app->language ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="ltr" lang="<?= Yii::$app->language ?>">
<!--<![endif]-->
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
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>

    <!--Favicons-->
    <link rel='shortcut icon' href='/favicon.ico' type='image/x-icon'>
    <link rel='icon' href='/favicon.ico' type='image/x-icon'>
    
    <?php
        $this->registerCsrfMetaTags()
    ?>

    <title>
        <?= Yii::$app->name . ' | ' . Html::encode($this->title)
        ?>
    </title>
    
    <?php
        $this->head()
    ?>
    <script>

    </script>

</head>

<body data-theme='default' data-layout='fluid' data-sidebar-position='left' data-sidebar-layout='default'>


<?php
    $this->beginBody()
?>


<div class='wrapper'>


    <!-- Left Sidebar nav -->
    <?= $this->render(
        '/layouts/bars/sidebar',
        [
            'modeName' => $modeName,
            'packName' => $packName,
            'context'  => $context,
            'appType'  => $appType,
        ],
    )
    ?>


    <div class='main'>

        <!-- Top Navbar nav -->
        <?= $this->render(
            '/layouts/bars/navbar',
        )
        ?>

        <main class='content'>
            <div class='container-fluid p-0'>
                
                <?= $content ?>

            </div>
        </main>

        <!-- Left Navbar nav -->
        <?= $this->render(
            '/layouts/bars/footer',
            [
                'modeName' => $modeName,
                'packName' => $packName,
            ],
        )
        ?>

    </div>


</div>

<?php
    $this->endBody()
?>


</body>

</html>

<?php
    $this->endPage();
