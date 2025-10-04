<?php
    
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Addon\Banner */
    
    const ADDON_BANNER_PART_LAYOUT = '#addon_banner_partView';
    echo PrintHelper::layout(LAYOUT_ID);

?>


<div class='row'>

    <div class='col-xl-6'>
        <?= $this->render(
            '_bannerView',
            [
                'model' => $model,
            ],
        )
        ?>
    </div>

    <div class='col-xl-6'>
        Ссылка:
        <?= Html::a(
            $model->reference,
            $model->reference,
            [
                'target' => '_blank',
            ],
        )
        ?>
        <hr>
        <?= Html::a(
            'Вернуться',
            TypeHelper::getView($model->text_type, $model->parent_id),
            [
                'class' => 'btn btn-sm btn-secondary',
            ],
        )
        ?>
    </div>

</div>
