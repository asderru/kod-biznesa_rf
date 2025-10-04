<?php
    
    use core\edit\search\Admin\PeriodSearch;
    
    /* @var $this yii\web\View */
    /* @var $searchModel PeriodSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_period_index';
    
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
            <?= $this->render(
                '@app/views/admin/period/_partIndex', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ],
            )
            ?>
    </div>

</div>
