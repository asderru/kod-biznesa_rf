<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\Admin\PhotoSize;
    use core\edit\search\Admin\PhotoSizeSearch;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel PhotoSizeSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const ADMIN_PHOTO_PARTINDEX_LAYOUT = '#admin_photo_partIndex';
    echo PrintHelper::layout(ADMIN_PHOTO_PARTINDEX_LAYOUT);

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
                        
                        //'id',
                        [
                            'attribute' => 'site.name',
                            'label'     => 'Сайт',
                        ],
                        [
                            'attribute' => 'text_type',
                            'label'     => 'Тип источника',
                            'filter'    => $searchModel::typesList(),
                            'value'     => static function (PhotoSize $model) {
                                return
                                    Html::a(
                                        TypeHelper::getLabel
                                        (
                                            $model->text_type,
                                        ),
                                        [
                                            'update',
                                            'id' => $model->id,
                                        ],
                                    );
                            },
                            'format'    => 'raw',
                        ],
                        'width',
                        'height',
                        
                        [
                            'class'    => ActionColumn::class,
                            'template' => '{delete}',
                        ],
                    ],
                ],
            );
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                'GridView-widget ', ADMIN_PHOTO_PARTINDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
