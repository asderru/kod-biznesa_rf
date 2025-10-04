<?php
    
    use core\edit\search\Admin\TemplateSearch;
    use core\helpers\ButtonHelper;
    
    /* @var $this yii\web\View */
    /* @var $searchModel TemplateSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_template_index';
    
    $this->title = $label;
    
    $buttons = [
        ButtonHelper::resort(
            null,
            'Сортировать шаблоны',
        ),
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
        
        <?= $this->render('@app/views/admin/template/_partIndex', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ])
        ?>
    </div>

</div>
