<?php
    
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Addon\Banner */

?>

<div class='card mb-3'>
    <?= Html::img(
        $model->picture,
        [
            'class' => 'card-img-top',
        ],
    
    )
    ?>
    <div class='card-body'>
        
        <?= Html::encode($model->description)
        ?>

    </div>
    <div class='card-footer'>
        Источник - <strong>
            <?= Html::encode($model->name)
            ?>. #<?= Html::encode($model->id)
            ?>
        </strong>
        <br>
        Внутренний рейтинг- <?= Html::encode($model->rating)
        ?>
    </div>
</div>
