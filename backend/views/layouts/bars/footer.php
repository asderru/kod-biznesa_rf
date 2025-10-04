<?php
    
    use yii\web\View;
    
    
    /* @var $this View */
    /** @var string $packName */
    /** @var string $modeName */
    
?>

<footer class='py-4 bg-light mt-auto'>
    <div class='container-fluid px-4'>
        <div class='d-flex align-items-center justify-content-between small'>
            <div>

					<span class='sidebar-brand-text align-middle'>
						<?= Yii::$app->id ?>
						<sup><small class='badge bg-secondary text-uppercase'><?= $modeName ?></small></sup>
					</span>
                appType: <?= Yii::$app->params['appType'] ?>,
                siteMode: <?= Yii::$app->params['siteMode'] ?>,
            </div>

            <div class='text-muted'>
                Shop'n'SEO <?= $packName ?> Pack 4.3.1
            </div>

            <div class='text-muted'>
                Copyright &copy; shopnseo.com 2008 - <?= date('Y')
                ?>
            </div>

        </div>
    </div>
</footer>
