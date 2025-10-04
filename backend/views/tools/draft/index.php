<?php
    
    use core\helpers\ButtonHelper;
    
    /* @var $this yii\web\View */
    /* @var $searchModel core\edit\search\Tools\DraftSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $roots array */
    /* @var $files array */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#tools_draft_index';
    
    $this->title = $label;
    
    $buttons = [
        ButtonHelper::expressType($textType, null, 'Экспресс-правка'),
        ButtonHelper::import($textType),
        ButtonHelper::export($textType),
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
                '_partIndex',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                ],
            )
            ?>
    </div>
</div>
