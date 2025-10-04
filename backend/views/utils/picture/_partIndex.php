<?php
    
    use backend\assets\TopScriptAsset;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Utils\Photo;
    use core\edit\search\Utils\PhotoSearch;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel PhotoSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $galleries core\edit\entities\Utils\Gallery[] */
    
    const UTILS_PICTURE_PARTINDEX_LAYOUT = '#utils_picture_partIndex';
    echo PrintHelper::layout(UTILS_PICTURE_PARTINDEX_LAYOUT);

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
                        'attribute'      => 'color',
                        'value'          => static function (
                            Photo $model,
                        ) {
                            return
                                Html::img
                                (
                                    $model->getImageUrl(3),
                                
                                );
                            
                        },
                        'label'          => 'Изображение',
                        'format'         => 'raw',
                        'contentOptions' => [
                            'style' => 'width: 130px',
                        ],
                    ],
                    [
                        'attribute' => 'name',
                        'label'     => 'Название',
                        'value'     => static function (
                            Photo $model,
                        ) {
                            return Html::a(
                                Html::encode
                                (
                                    $model->name ?? 'Без названия - ' . $model->id,
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
                    //'site_id',
                    
                    [
                        'attribute' => 'parent_id',
                        'label'     => 'Галерея',
                        'filter'    =>
                            $searchModel->galleriesList(),
                        'value'     => static function (
                            Photo $model,
                        ) {
                            return Html::a(
                                Html::encode
                                (
                                    $model->gallery->name,
                                ),
                                [
                                    'utils/gallery/view',
                                    'id' => $model->parent_id,
                                ],
                            );
                            
                        },
                        'format'    => 'raw',
                    ],
                    //'photo',
                    //'description',
                    //'reference',
                    [
                        'attribute' => 'status',
                        'label'     => 'статус',
                        'filter'    =>
                            StatusHelper::statusList(),
                        'value'     => static function (Photo $model) {
                            return
                                StatusHelper::statusLabel($model->status)
                                . '<hr>' .
                                StatusHelper::activation($model->id, $model->status);
                        },
                        'format'    => 'raw',
                    ],
                    //'color',
                    //'sort',
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
            'GridView-widget ', UTILS_PICTURE_PARTINDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
