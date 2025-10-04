<?php
    
    use backend\widgets\PagerWidget;
    use core\edit\forms\UploadPhotoForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Photo */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_photo_view';
    
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
    
    try {
        echo
        PagerWidget::widget(
            [
                'model'  => $model,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
    
    }
    
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
            '/utils/photo/_partView',
            [
                'model'      => $model,
                'uploadForm' => $uploadForm,
                'actionId' => $actionId,
            ],
        )
        ?>

    </div>

<?php
    echo '</div>';
