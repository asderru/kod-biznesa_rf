<?php
    
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\data\ArrayDataProvider;
    
    /* @var $this yii\web\View */
    /* @var $keys array */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_cache_index';
    echo PrintHelper::layout(LAYOUT_ID);
    
    $this->title = 'Cache Keys';
    
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
<div class="card">
    
    <?= $this->render(
        '/layouts/partials/_pageSize',
    );
    ?>

    <div class='table-responsive'>
        <?php
            try {
                echo
                GridView::widget([
                    'dataProvider' => new ArrayDataProvider([
                        'allModels'  => array_map(function ($key) {
                            return ['key' => $key];
                        }, $keys),
                        'pagination' => [
                            'pageSize' => 20,
                        ],
                    ]),
                    'columns'      => [
                        [
                            'attribute' => 'key',
                            'format'    => 'raw',
                            'value'     => function ($model) {
                                return Html::a(Html::encode($model['key']), ['view', 'key' => $model['key']]);
                            },
                        ],
                    ],
                ]);
            }
            catch (Throwable $e) {
                PrintHelper::exception(
                    'GridView_widget', LAYOUT_ID, $e,
                );
            }
        ?>

    </div>

</div>
