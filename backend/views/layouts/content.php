<?php

use backend\assets\AppAsset;use yii\bootstrap5\Html;

/* @var $this yii\web\View */
/* @var $content string */

AppAsset::register($this);

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


<main class='content'>

    <div class='container-fluid p-0'>
        
        <?= $this->render(
            '/layouts/tops/_breadcrumbs',
        )
        ?>

        <div class="container alert-area">
            <?= $this->render(
                '/layouts/tops/_messages',
            )
            ?>
        </div>
        <?= $content ?>

    </div>
</main>

<!-- Left Navbar nav -->
<?= $this->render(
    '/layouts/bars/footer',
    [
        'modeName' => 'Shop',
        'packName' => 'Ultimate',
    ],
)
?>

<?php
    $this->endBody()
?>

</body>

</html>

<?php
    $this->endPage();
