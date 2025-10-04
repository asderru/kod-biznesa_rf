<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model Razdel|Product */
    /* @var $title string */
    /* @var $textType int */
    
    $id = $model->id;
?>

<div class='card-header bg-light d-flex justify-content-between'>
    <div>
        <h5>
            <?= Html::encode($title) ?>
        </h5>
        <small>
            <?= StatusHelper::statusBadgeLabel($model->status) ?>
        </small>
    </div>
</div>
