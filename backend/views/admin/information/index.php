<?php
    
    /* @var $this yii\web\View */
    /* @var $searchModel core\edit\search\Admin\InformationSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID                = '#admin_information_index';
    
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
    echo '<div class="card">';
    
    $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )

?>

    <div class='card-body'>
        
        <?= $this->render('_partIndex.php', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ])
        ?>

    </div>

<?php
    echo '</div>';
