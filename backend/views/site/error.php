<?php
    
    /** @var yii\web\View $this */
    /** @var string $name */
    /** @var string $message */
    
    /** @var Exception $exception */
    
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    const LAYOUT_ID = '#site_error';
    echo PrintHelper::layout(LAYOUT_ID);
    
    $this->title = $name;
    
    $appType  = Yii::$app->params[('appType')];
    $siteMode = Yii::$app->params[('siteMode')];

?>

<section class='inner-page'>

    <div class='container'>

        <div class="site-error">

            <h1><?= Html::encode($this->title)
                ?></h1>

            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message))
                ?>
            </div>

            <p>
                Случилась ошибка. Сообщите администратору.
            </p>
            <p>
                ID сайта - <?= Yii::$app->params[('siteId')] ?> <br>
                Тип сайта - <?= $appType ?> <br>
                Режим сайта "SiteMode" - <?= $siteMode ?> <br>
            </p>

        </div>
    </div>
</section>
