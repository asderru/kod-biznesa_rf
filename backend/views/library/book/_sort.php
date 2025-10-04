<?php
    
    use backend\helpers\StatusHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    const LIBRARY_BOOK_SORT_LAYOUT = '#library_book_sort';
    echo PrintHelper::layout(LIBRARY_BOOK_SORT_LAYOUT);
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\library\book */
    /* @var $form yii\bootstrap5\ActiveForm */
    
    try {
        echo
        DetailView::widget(
            [
                'model'      => $model,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'status',
                        'value'     => StatusHelper::statusLabel($model->status),
                        'format'    => 'raw',
                        'label'     => 'статус',
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
            'DetailWidget ', LIBRARY_BOOK_SORT_LAYOUT, $e,
        );
    }
?>

    <hr>

    <div class='text-end'>

<?php
    echo Html::a(
        'Смотреть в др.окне',
        [
            '/library/book/view',
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
            '/library/book/update',
            'id' => $model->id,
        ],
        [
            'class' => 'btn btn-sm btn-outline-primary mr-1 mb-1',
        ],
    );
    echo Html::a(
        'Экспресс-правка',
        [
            '/express/book/view',
            'id' => $model->id,
        ],
        [
            'class' => 'btn btn-sm btn-outline-warning text-dark mr-1 mb-1',
        ],
    );
