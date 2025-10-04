<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\Admin\PhotoRatio;
    use core\edit\search\Admin\PhotoRatioSearch;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\read\readers\Admin\InformationReader;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel PhotoRatioSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const ADMIN_RATIO_PARTINDEX_LAYOUT = '#admin_ratio_partIndex';
    echo PrintHelper::layout(ADMIN_RATIO_PARTINDEX_LAYOUT);

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
                            'attribute' => 'site_id',
                            'label'     => 'Сайт',
                            'value'     => static function (PhotoRatio $model) {
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
                            'attribute' => 'name',
                            'label'     => 'Название',
                            'value'     => static function (
                                PhotoRatio $model,
                            ) {
                                return Html::a(
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
                        [
                            'attribute' => 'ratio3',
                            'value'     => static function (
                                PhotoRatio $model,
                            ) {
                                return $model->ratio3 . ' / 10000';
                            },
                            'label'     => 'Соотношение сторон для квадратной иконки',
                        ],
                        [
                            'attribute' => 'ratio2',
                            'value'     => static function (
                                PhotoRatio $model,
                            ) {
                                return $model->ratio2 . ' / 10000';
                            },
                            'label'     => 'Соотношение сторон стандартной иконки',
                        ],
                        [
                            'attribute' => 'ratio1',
                            'value'     => static function (
                                PhotoRatio $model,
                            ) {
                                return $model->ratio1 . ' / 10000';
                            },
                            'label'     => 'Соотношение сторон  широкоформатной иконки',
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
                'GridView-widget ', ADMIN_RATIO_PARTINDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
