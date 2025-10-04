<?php
    
    use backend\assets\TopScriptAsset;
    use core\helpers\PrintHelper;
    use yii\grid\SerialColumn;
    use core\edit\entities\User\UserData;
    use core\edit\search\User\UserDataSearch;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    TopScriptAsset::register($this);
    
    /** @var yii\web\View $this */
    /** @var UserDataSearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_data_index';
    
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

<div class="user-data-index">

    <h1><?= Html::encode($this->title)
        ?></h1>

    <p>
        <?= Html::a('Create User Data', ['create'], ['class' => 'btn btn-success'])
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
                            'user_id',
                            'user_name',
                            'country',
                            //'operating_system',
                            //'browser',
                            //'browser_version',
                            [
                                'class'      => ActionColumn::className(),
                                'urlCreator' => static function ($action, UserData $model) {
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
                    
                } ?>
        </div>
    </div>


</div>
