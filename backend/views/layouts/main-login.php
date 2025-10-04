<?php
    
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /** @var View $this */
    /** @var string $content */
    
    const MAIN_LAYOUT = '#layouts_partials_main-login';
    echo PrintHelper::layout(MAIN_LAYOUT);
    
    try {
        $this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
    }
    catch (Exception|Throwable $e) {
        Yii::$app->errorHandler->logException($e);
        Yii::$app->session->
        setFlash(
            'danger',
            'Что-то пошло не так.
                    Случилась ошибка. Попробуйте еще раз!',
        );
    } ?>
<?php
    $this->beginPage()
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags()
        ?>
        <title><?= Html::encode($this->title)
            ?></title>
        <?php
            $this->head()
        ?>
    </head>

    <body class="login-page">
    
    <?php
        $this->beginBody()
    ?>

    <div class="login-box">
        
        <?= $this->render(
            '/layouts/tops/_messages',
        )
        ?>
        
        <?= $content ?>
    </div>
    
    <?php
        $this->endBody()
    ?>

    </body>
    </html>
<?php
    $this->endPage();
