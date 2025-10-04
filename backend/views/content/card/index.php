<?php
    
    use core\edit\search\Content\CardSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use yii\bootstrap5\Html;
    use yii\data\ActiveDataProvider;
    
    /* @var $this yii\web\View */
    /* @var $searchModel CardSearch */
    /* @var $dataProvider ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_card_index';
    
    $this->title = $label;
    
    $buttons = [
        ButtonHelper::expressType($textType, null, 'Экспресс-правка'),
        ButtonHelper::structure($textType, null,'Структура карточек'),
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
    
<div class='card-header bg-light d-flex justify-content-between'>
    <div>
        <h4>
            <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($label)
            ?>
        </h4>
    </div>

    <div class='btn-group-sm d-grid gap-2 d-sm-block'>
        <?= ButtonHelper::refresh(); ?>
    </div>
</div>

    <div class='card-body'>
        
        <?= $this->render(
                '/content/card/_partIndex',
            [
                'dataProvider' => $dataProvider,
                'searchModel'  => $searchModel,
                'textType' => $textType,
            ],
        );
        ?>

    </div>

</div>
