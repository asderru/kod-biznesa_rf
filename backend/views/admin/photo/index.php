<?php
    
    use core\edit\search\Admin\PhotoSizeSearch;
    
    /* @var $this yii\web\View */
    /* @var $searchModel PhotoSizeSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_photo_index';
    
    $this->title = $label;
    
    $buttons = [];
    
    
    $this->params['breadcrumbs'][] = $this->title;
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
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>

    <div class='card-body'>

            <?= $this->render(
                '@app/views/admin/photo/_partIndex', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ],
            )
            ?>
    </div>
</div>
