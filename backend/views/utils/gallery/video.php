<?php
    
    use core\edit\entities\Utils\Gallery;
    use core\edit\forms\UploadMultiPhotosForm;
    use core\helpers\ButtonHelper;
    
    /* @var $this yii\web\View */
    /* @var $model Gallery */
    /* @var $videoForm UploadMultiPhotosForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_gallery_video';
    
    $this->title = ($model->isRoot())
        ? 'Описание галерей сайта'
        :
        $model->name;
    
    if ($model::hasModels($model->site_id)) {
        $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    }
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons        = [
        ButtonHelper::fullActivationSet($model),
        ButtonHelper::create(),
        ButtonHelper::galleryResort(
            $model->id,
            'Сортировать фотографии',
        ),
        ButtonHelper::delete($model),
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
    
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_viewHeaderModel',
        [
            'model' => $model,
            'textType' => $textType,
            'buttons'  => $buttons, // передаем массив кнопок
        ],
    )
?>

    <div class='card-body'>
        
        <?= $this->render(
            '/utils/gallery/_partVideo',
            [
                'model'     => $model,
                'videoForm' => $videoForm,
            ],
        )
        ?>

    </div>

<?php
    echo '</div>';
