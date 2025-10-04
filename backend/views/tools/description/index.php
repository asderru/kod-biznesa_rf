<?php
    
    use core\helpers\ButtonHelper;
    use core\tools\Constant;
    
    /* @var $this yii\web\View */
    /* @var $searchModel core\edit\search\Content\ContentSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $contents core\edit\entities\Content\Content[] */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_description_index';
    
    $label    = 'Мета-описания';
    $textType = Constant::DESCRIPTION_TYPE;
    
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

            <?= $this->render('@app/views/content/description/_partIndex', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'actionId' => $actionId,
            ])
            ?>
    </div>

</div>
