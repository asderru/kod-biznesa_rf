<?php
    
    /** @var yii\web\View $this */
    /** @var string $content */
    
    const BLANK_LAYOUT = '#layouts_blank';
?>
<?php
    $this->beginContent('@app/views/layouts/main.php')
?>

    <!--###### Content ##########################################################-->

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

    <div id="page-content"></div>

<?= $content ?>

<?php
    $this->endContent();
