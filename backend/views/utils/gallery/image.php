<?php
    
    use core\edit\entities\Utils\Gallery;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\search\Utils\PhotoSearch;
    use core\helpers\ButtonHelper;
    
    /* @var $this yii\web\View */
    /* @var $model Gallery */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $searchModel PhotoSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_gallery_image';
    
    $this->title = 'Создание обложки';
    
    $buttons = [];
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = [
        'label' => $model->name, 'url' => [
            'view', 'id' => $model->id,
        ],
    ];
    
    $this->params['breadcrumbs'][] = $this->title;
    
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
    <?= $this->render(
        '/layouts/tops/_viewHeader-start',
        [
            'id'      => $model->id,
            'status'  => $model->status,
            'title'   => $this->title . ' для галереи ' . $model->name,
            'buttons' => $buttons,
        ],
    )
    ?>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?= ButtonHelper::fullActivationSet($model)
        ?>
        <?= ButtonHelper::create()
        ?>
        <?= ButtonHelper::galleryResort(
            $model->id,
            'Сортировать фотографии',
        )
        ?>
        <?= ButtonHelper::delete($model)
        ?>
    </div>

</div>

<div class='card rounded-0'>

    <div class='card-body'>
        
        
        <?= $this->render(
            '/layouts/images/_images',
            [
                'model'      => $model,
                'uploadForm' => $uploadForm,
            ],
        )
        ?>


    </div>
</div>
