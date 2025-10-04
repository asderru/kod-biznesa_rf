<?php
    
    use core\helpers\FaviconHelper;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $title string */
    /* @var $textType int */

?>

<div class='card-header bg-light d-flex justify-content-between'>
    <div class='h5'>
        <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($title)
        ?>
    </div>
    <div class='btn-group-sm d-grid gap-2 d-sm-block'>
        <?= ButtonHelper::submit()
        ?>
    </div>
</div>
