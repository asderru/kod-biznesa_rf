<?php
    
    use core\edit\entities\Tech\BlackList;
    use core\edit\search\Tech\BlackListSearch;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    use yii\helpers\Url;
    
    /** @var yii\web\View $this */
    /** @var BlackListSearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#tech_blacklist_index';
    
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
    )
?>

<div class="black-list-index">

    <h1><?= Html::encode($this->title)
        ?></h1>

    <p>
        <?= Html::a('Create Black List', ['create'], ['class' => 'btn btn-success'])
        ?>
    </p>

    <div class='card-body'>

        <div class='table-responsive'>
            <?php
                try {
                    echo
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel'  => $searchModel,
                        'columns'      => [
                            ['class' => SerialColumn::class],
                            
                            'id',
                            'ip_address',
                            'name',
                            [
                                'class'      => ActionColumn::className(),
                                'urlCreator' => static function ($action, BlackList $model) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                },
                            ],
                        ],
                    ]);
                }
                catch (Throwable $e) {
                } ?>
        </div>

    </div>
</div>
