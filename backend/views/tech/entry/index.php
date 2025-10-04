<?php
    
    use core\edit\entities\Tech\Entry;
    use core\edit\search\Tech\EntrySearch;
    use core\helpers\PrintHelper;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /** @var yii\web\View $this */
    /** @var EntrySearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#tech_entry_index';
    
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

<div class="entry-index">

    <h1><?= Html::encode($this->title)
        ?></h1>

    <p>
        <?= Html::a('Create Entry', ['create'], ['class' => 'btn btn-success'])
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
                            ['class' => 'yii\grid\SerialColumn'],
                            
                            'id',
                            'host',
                            'client_ip',
                            'timestamp',
                            'request_method',
                            //'request_url:ntext',
                            //'http_version',
                            //'status_code',
                            //'response_size',
                            //'referer:ntext',
                            //'user_agent:ntext',
                            //'created_at',
                            [
                                'class'      => ActionColumn::className(),
                                'urlCreator' => function ($action, Entry $model) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                },
                            ],
                        ],
                    ]);
                }
                catch (Throwable $e) {
                    PrintHelper::exception(
                        'GridView-widget ', LAYOUT_ID, $e,
                    );
                }
            ?>
        </div>
    </div>
</div>
