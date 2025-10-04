<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\Utils\Data;
    use yii\grid\SerialColumn;
    use core\edit\search\Utils\DataSearch;
    use core\helpers\PrintHelper;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\bootstrap5\Html;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel DataSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    
    const UTILS_DATA_PARTINDEX_LAYOUT = '#utils_data_partIndex';
    echo PrintHelper::layout(UTILS_DATA_PARTINDEX_LAYOUT);
    
    $label = 'Ячейки таблиц';

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
                            Data $model,
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
                    'col_id',
                    'row_id',
                    //'value',
                    //'updated_at',
                    
                    
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
            'GridView-widget ', UTILS_DATA_PARTINDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
