<?php

    use core\helpers\PrintHelper;
    use core\helpers\StatusHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Content\Page */
    /* @var $form yii\bootstrap5\ActiveForm */
    /* @var $textType int */
    
    const CONTENT_PAGE_SORT_LAYOUT = '#content_page_sort';
    echo PrintHelper::layout(CONTENT_PAGE_SORT_LAYOUT);
    
    try {
        echo
        DetailView::widget(
            [
                'model'      => $model,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'status',
                        'label'     => 'статус',
                        'value'  => StatusHelper::statusLabel($model->status, $textType),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'name',
                    ],
                    [
                        'attribute' => 'title',
                    ],
                    [
                        'attribute' => 'slug',
                    ],
                    [
                        'attribute' => 'description',
                        'label'     => 'Описание',
                        'format'    => 'Html',
                    
                    ],
                
                ],
            ],
        );
    }
    catch (Exception|Throwable $e) {
        PrintHelper::exception(
            'DetailWidget ', CONTENT_PAGE_SORT_LAYOUT, $e,
        );
    }
?>

    <hr>

    <div class='text-end'>

<?php
    echo Html::a(
        'Смотреть в др.окне',
        [
            '/content/page/view',
            'id' => $model->id,
        ],
        [
            'class'  => 'btn btn-sm btn-outline-success mr-1 mb-1',
            'target' => '_blank',
        ],
    );
    echo Html::a(
        'Редактировать',
        [
            '/content/page/view',
            'id' => $model->id,
        ],
        [
            'class' => 'btn btn-sm btn-outline-primary mr-1 mb-1',
        ],
    );
    echo Html::a(
        'Экспресс-правка',
        [
            '/express/page/view',
            'id' => $model->id,
        ],
        [
            'class' => 'btn btn-sm btn-outline-warning text-dark mr-1 mb-1',
        ],
    );
