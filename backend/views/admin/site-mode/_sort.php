<?php
    
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Admin\SiteMode */
    /* @var $form yii\bootstrap5\ActiveForm */
    
    const ADMIN_SITEMODE_SORT_LAYOUT = '#admin_siteMode_sort';
    
    try {
        echo
        DetailView::widget(
            [
                'model'      => $model,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'name',
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
            'DetailWidget ', ADMIN_SITEMODE_SORT_LAYOUT, $e,
        );
    }
?>

    <hr>

    <div class='text-end'>

<?php
    echo Html::a(
        'Смотреть в др.окне',
        [
            '/admin/site-mode/view',
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
            '/admin/site-mode/view',
            'id' => $model->id,
        ],
        [
            'class' => 'btn btn-sm btn-outline-primary mr-1 mb-1',
        ],
    );
