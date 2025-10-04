<?php
    
    use backend\widgets\PagerWidget;
    use backend\widgets\SimplePagerWidget;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Photo */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_photo_view';
    
    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = [
        'label' => 'Галереи',
        'url'   => [
            '/utils/gallery/index',
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => 'Галерея ' . $model->gallery->name,
        'url'   => [
            '/utils/gallery/view',
            'id' => $model->gallery->id,
        ],
    ];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
    
    ];
    
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

<div class='card'>
    <?php
        try {
            echo
            SimplePagerWidget::widget(
                [
                    'model'  => $model,
                    'folder' => true,
                ],
            );
        }
        catch (Throwable $e) {
        
        }
        
        echo $this->render(
            '/layouts/tops/_viewHeader-start',
            [
                'id'     => $model->id,
                'status' => $model->status,
                'title'  => $this->title . ' для галереи ' . $model->status,
                'buttons' => $buttons,
            ],
        )
    ?>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?php
            echo ButtonHelper::fullActivationSet($model);
            echo ButtonHelper::create();
            echo ButtonHelper::galleryResort(
            $model->id,
            'Сортировать фотографии',
            );
            echo ButtonHelper::delete($model)
        ?>
    </div>

</div>

<div class='card rounded-0'>

    <div class='card-body'>
        
        <?= $this->render(
            '../../../views/utils/photo/_partView',
            [
                'model'      => $model,
                'uploadForm' => $uploadForm,
            ],
        )
        ?>

    </div>
</div>
