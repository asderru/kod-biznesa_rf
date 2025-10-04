<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Shop\Razdel;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Utils\Gallery;
    use core\edit\forms\Utils\Gallery\GalleryAssignForm;
    use core\edit\search\Utils\GallerySearch;
    use core\helpers\ButtonHelper;
    use yii\grid\GridView;
    use yii\bootstrap5\Html;
    use yii\data\ActiveDataProvider;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    /* @var $this View */
    /** @var ActiveDataProvider $dataProvider */
    /** @var GallerySearch $searchModel */
    /** @var GalleryAssignForm $assignForm */
    /** @var Razdel|Product $parent */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_gallery_assign';
    
    $this->title = 'Назначение галерей для ' . $parent->name;
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    );
?>

<div class="gallery-assign">
    <h1><?= Html::encode($this->title)
        ?></h1>
    
    <?php
        $form = ActiveForm::begin(
            [
                'options'     => [
                    'class' => 'active__form',
                ],
                'fieldConfig' => [
                    'errorOptions' => [
                        'encode' => false,
                        'class'  => 'help-block',
                    ],
                ],
            ],
        );
        try {
            echo
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'columns'      => [
                    [
                        'class'           => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function (Gallery $model) use ($assignForm) {
                            // Проверяем, назначена ли уже галерея для данного родителя
                            $isAssigned = $assignForm->isGalleryAssigned($model->id);
                            return [
                                'checked' => $isAssigned,
                                'value'   => $model->id,
                                'name'    => 'galleries[]',
                            ];
                        },
                    ],
                    'id',
                    'name',
                    [
                        'attribute' => 'description',
                        'format'    => 'raw',
                    ],
                    [
                        'attribute' => 'created_at',
                        'format'    => ['datetime'],
                    ],
                    [
                        'attribute' => 'status',
                        'value'     => function ($model) {
                            return StatusHelper::statusLabel($model->status);  // Предполагаем, что есть метод getStatusLabel()
                        },
                        'filter'    => StatusHelper::statusList(),
                        'format'    => 'raw',
                    ],
                    [
                        'attribute' => 'count',
                        'label'     => 'Количество фото',
                        'value'     => function ($model) {
                            return $model->photosCount;  // Предполагаем, что есть метод getPhotosCount()
                        },
                        'filter'    => StatusHelper::statusList(),
                        'format'    => 'raw',
                    ],
                ],
            ]);
        }
        catch (Throwable $e) {
        
        } ?>

    <div class="form-group mt-3">
        <?= ButtonHelper::submit()
        ?>
        
        <?= Html::a('Отмена', ['view', 'id' => $parent->id], [
            'class' => 'btn btn-secondary',
        ])
        ?>
    </div>
    
    <?php
        ActiveForm::end(); ?>
</div>

<style>
    .gallery-assign {
        padding: 20px;
    }

    .form-group {
        margin-top: 20px;
    }

    .btn {
        margin-right: 10px;
    }
</style>
