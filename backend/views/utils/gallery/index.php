<?php
    
    use core\edit\entities\Admin\Information;
    use core\helpers\ButtonHelper;
    
    /* @var $this yii\web\View */
    /* @var $sites Information[] */
    /* @var $roots core\edit\entities\Utils\Gallery[] */
    /* @var $searchModel core\edit\search\Utils\GallerySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_gallery_index';
    
    $this->title = $label;
    
    $buttons = [
        ButtonHelper::expressType($textType, null, 'Экспресс-правка'),
        ButtonHelper::structure($textType, null, 'Структура галерей'),
        ButtonHelper::import($textType),
        ButtonHelper::export($textType),
    ];
    
    $buttons[] = '<hr> Смена сайта для галерей: ';
    foreach ($sites as $changedSite) {
        $buttons[] = ButtonHelper::changeSite(
            $changedSite['id'],
            $textType,
            $changedSite['name'],
        );
    }
    
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
