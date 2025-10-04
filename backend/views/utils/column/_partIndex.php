<?php
    
    use backend\assets\TopScriptAsset;
    use yii\grid\SerialColumn;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Utils\Column;
    use core\edit\search\Utils\ColumnSearch;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $table core\edit\entities\Utils\Table */
    /* @var $searchModel ColumnSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    
    const UTILS_COLUMN_PARTINDEX_LAYOUT = '#utils_column_partIndex';
    echo PrintHelper::layout(UTILS_COLUMN_PARTINDEX_LAYOUT);

?>

<?= $this->render(
    '/layouts/partials/_pageSize',
);
?>

<div class='table-responsive'>
    <?php
    try {
        echo
        GridView::widget(
            [
                'pager'          => [
                    'firstPageLabel' => 'в начало',
                    'lastPageLabel'  => 'в конец',
                ],
                'dataProvider'   => $dataProvider,
                'filterModel'    => $searchModel,
                'caption'        => Html::encode($this->title),
                'captionOptions' => [
                    'class' => 'text-end p-2',
                ],
                'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                'summaryOptions' => [
                    'class' => 'bg-secondary text-white p-1',
                ],
                
                'tableOptions' => [
                    'id'    => 'point-of-grid-view',
                    'class' => 'table table-striped table-bordered',
                ],
                'columns'      => [
                    ['class' => SerialColumn::class],
                    
                    [
                        'attribute' => 'name',
                        'label'     => 'Название колонки',
                        'value'     => static function (
                            Column $model,
                        ) {
                            return Html::a(
                                Html::encode
                                (
                                    $model->name,
                                ),
                                [
                                    'view',
                                    'id' => $model->id,
                                ],
                            );
                            
                        },
                        'format'    => 'raw',
                    ],
                    
                    //'id',
                    //'table_id',
                    //'col_id',
                    //'name',
                    //'updated_at',
                    //'sort',
                    [
                        'attribute' => 'status',
                        'label'     => 'статус',
                        'filter'    => StatusHelper::statusList(),
                        'value'     => static
                        function (
                            Column $model,
                        ) {
                            return
                                StatusHelper::statusLabel($model->status)
                                . '<hr>' .
                                StatusHelper::activation($model->id, $model->status);
                        },
                        'format'    => 'raw',
                    ],
                    [
                        'value'  => static
                        function (
                            Column $model,
                        ) {
                            return
                                StatusHelper::activation($model->id, $model->status);
                        },
                        'format' => 'raw',
                    ],
                    
                    
                    [
                        'class'    => ActionColumn::class,
                        'template' => '{update} {delete}',
                    ],
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'GridView-widget ', UTILS_COLUMN_PARTINDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
