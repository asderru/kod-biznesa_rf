<?php
    
    use backend\assets\TopScriptAsset;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Utils\Gallery;
    use core\helpers\IconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\InformationReader;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    use yii\web\View;
    
    TopScriptAsset::register($this);
    
    /* @var $this View */
    /* @var $galleries core\edit\entities\Utils\Gallery[] */
    /* @var $searchModel core\edit\search\Utils\GallerySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const GALLERY_PART_INDEX_LAYOUT = '#utils_gallery_partIndex';
    echo PrintHelper::layout(GALLERY_PART_INDEX_LAYOUT);

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
                    
                    'id',
                    [
                        'value'          => static function (Gallery $model) {
                            return
                                Html::img(
                                    $model->getImageUrl(1),
                                    [
                                        'class' => 'img-fluid',
                                    ],
                                )
                                ??
                                IconHelper::biEyeFill('смотреть');
                        },
                        'label'          => 'Изображение',
                        'format'         => 'raw',
                        'contentOptions' => [
                            'style' => 'width: 130px',
                        ],
                    ],
                    //'site_id',
                    [
                        'attribute' => 'name',
                        'label'     => 'Название галереи',
                        'value'     =>
                            static
                            function (
                                Gallery $model,
                            ) {
                                return Html::a(
                                    Html::encode($model->name),
                                    [
                                        'view',
                                        'id' => $model->id,
                                    ],
                                );
                            },
                        'format'    => 'raw',
                    ],
                    [
                        'attribute' => 'site_id',
                        'label'     => 'Сайт',
                        'value'     => static function (Gallery $model) {
                            return Html::a(
                                ParametrHelper::getSiteName($model->site_id),
                                [
                                    '/admin/information/view',
                                    'id' => $model->site_id,
                                ],
                            );
                        },
                        'format'    => 'raw',
                        'filter'    => InformationReader::getDropDownFilter(0),
                        'visible'   => ParametrHelper::isServer(),
                    ],
                    [
                        'attribute' => 'status',
                        'label'     => 'статус',
                        'filter'    =>
                            StatusHelper::statusList(),
                        'value'     => static function (Gallery $model) {
                            return
                                StatusHelper::statusLabel($model->status)
                                . '<hr>' .
                                StatusHelper::activation($model->id, $model->status);
                        },
                        'format'    => 'raw',
                    ],
                    //'description:ntext',
                    //'reference',
                    //'photo',
                    //'status',
                    //'color',
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
            'GridView-widget ', GALLERY_PART_INDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
