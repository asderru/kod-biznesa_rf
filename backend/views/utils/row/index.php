<?php
    
    use core\edit\search\Utils\RowSearch;
    
    /* @var $this yii\web\View */
    /* @var $table core\edit\entities\Utils\Table */
    /* @var $searchModel RowSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_row_index';
    
    $this->title = $label;
    
    $buttons = [];
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Таблицы',
        'url'   => [
            'utils/table/index',
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $table->name,
        'url'   => [
            'utils/table/view',
            'id' => $table->id,
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
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>
    <div class='card-body'>
            <?= $this->render('@app/views/utils/row/_partIndex', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ])
            ?>
    </div>
</div>
