<?php
    
    use backend\assets\TopScriptAsset;
    use yii\grid\SerialColumn;
    use core\edit\assignments\TableAssignment;
    use core\edit\search\Content\TableAssignmentSearch;
    use core\helpers\PrintHelper;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\bootstrap5\Html;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel TableAssignmentSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const UTILS_TABLEASSIGN_PARTINDEX_LAYOUT = '#utils_tableAssign_partIndex';
    echo PrintHelper::layout(UTILS_TABLEASSIGN_PARTINDEX_LAYOUT);

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
                            'label'     => 'Название',
                            'value'     => static function (
                                TableAssignment $model,
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
                        
                        'id',
                        'site_id',
                        'table_id',
                        'text_type',
                        'parent_id',
                        //'name',
                        //'description',
                        //'status',
                        //'updated_at',
                        //'sort',
                        
                        
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
                'GridView-widget ', UTILS_TABLEASSIGN_PARTINDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
