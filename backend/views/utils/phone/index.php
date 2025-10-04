<?php
    
    use core\edit\search\Utils\PhonesSearch;
    use core\helpers\ButtonHelper;
    use yii\helpers\Url;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    
    /** @var yii\web\View $this */
    /** @var PhonesSearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_phone_index';
    
    $this->title = 'Телефоны';
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        ButtonHelper::expressType($textType, null, 'Экспресс-правка'),
        ButtonHelper::import($textType),
        ButtonHelper::export($textType),
    ];
    
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
    
    <?php
        try {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'columns'      => [
                    ['class' => 'yii\grid\SerialColumn'],
                    
                    'id',
                    'site_id',
                    'name',
                    'country_code',
                    'phone',
                    [
                        'class'      => ActionColumn::className(),
                        'urlCreator' => function ($action, Phones $model) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        },
                    ],
                ],
            ]);
        }
        catch (Throwable $e) {
        
        } ?>


</div>
