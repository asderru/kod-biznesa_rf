<?php
    
    use core\edit\search\Addon\BannerSearch;
    use core\tools\Constant;
    
    /* @var $this yii\web\View */
    /* @var $searchModel BannerSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#addon_banner_index';
    
    $label    = 'Баннеры';
    $textType = Constant::BANNER_TYPE;
    
    $this->title = $label;
    
    $buttons = [];
    
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
            <?= $this->render('@app/views/addon/banner/_partIndex', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ])
            ?>
    </div>
</div>
