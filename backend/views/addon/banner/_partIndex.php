<?php
    
    use backend\assets\TopScriptAsset;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Addon\Banner;
    use core\edit\search\Addon\BannerSearch;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\InformationReader;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel BannerSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const ADDON_BANNER_PARTINDEX_LAYOUT = '#addon_banner_partIndex';
    echo PrintHelper::layout(ADDON_BANNER_PARTINDEX_LAYOUT);

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
                    'id'    => 'bannerList',
                    'pager' => [
                        'firstPageLabel' => 'в начало',
                        'lastPageLabel'  => 'в конец',
                    ],
                    'dataProvider'   => $dataProvider,
                    'filterModel'    => $searchModel,
                    'caption'        => Html::encode($this->title),
                    'captionOptions' => [
                        'class' => 'bg-secondary text-white p-2',
                    ],
                    'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                    'summaryOptions' => [
                        'class' => 'bg-secondary text-white p-1',
                    ],
                    'tableOptions'   => [
                        'id'    => 'point-of-grid-view',
                        'class' => 'table table-striped table-bordered',
                    ],
                    'columns'        => [
                        ['class' => SerialColumn::class],
                        
                        [
                            'label'  => 'Изображение',
                            'value'  => static function (
                                Banner $model,
                            ) {
                                return Html::img(
                                    $model->picture,
                                    [
                                        'class' => 'img-fluid',
                                        'style' => 'max-width:150px',
                                    ],
                                );
                                
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'name',
                            'label'     => 'Название',
                            'value'     => static function (
                                Banner $model,
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
                        //'site_mode',
                        //'text_type',
                        //'parent_id',
                        //'name',
                        //'description',
                        //'picture',
                        //'reference',
                        //'created_at',
                        //'updated_at',
                        //'rating',
                        [
                            'attribute' => 'site_id',
                            'label'     => 'Сайт',
                            'value'     => static function (Banner $model) {
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
                            'filter'    => StatusHelper::statusList(),
                            'value'     => static function (Banner $model) {
                                return
                                    StatusHelper::statusLabel($model->status)
                                    . '<hr>' .
                                    StatusHelper::activation($model->id, $model->status);
                            },
                            'format'    => 'raw',
                        ],
                        //'sort',
                        //'counts',
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
                'GridView-widget ', ADDON_BANNER_PARTINDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
