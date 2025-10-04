<?php
    
    use backend\assets\TopScriptAsset;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Content\Content;
    use core\edit\search\Content\ContentSearch;
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
    /* @var $searchModel ContentSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const CONTENT_PARTINDEX_LAYOUT = '#content_content_partIndex';
    echo PrintHelper::layout(CONTENT_PARTINDEX_LAYOUT);
    
    $label = 'Контент';
    
    $this->title = $label;

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
                            'attribute' => 'title',
                            'label'     => 'Название',
                            'value'     => static function (
                                Content $model,
                            ) {
                                return Html::a(
                                    Html::encode
                                    (
                                        $model->title,
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
                        [
                            'attribute' => 'text_type',
                            'label'     => 'Тип источника',
                            'filter'
                                        =>
                                $searchModel::shopTypesList(),
                            'value'     => static function (Content $model) {
                                return
                                    TypeHelper::getLabel
                                    (
                                        $model->text_type,
                                    );
                            },
                            'format'    => 'raw',
                        ],
                        
                        [
                            'attribute' => 'parent_id',
                            'label'     => 'Имя источника',
                            'value'     => static function (Content $model) {
                                return Html::a(
                                    Html::encode
                                    (
                                        $model->parent->name,
                                    ),
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
                            'value'     => static function (Content $model) {
                                return Html::a(
                                    Html::encode($model->getSite()->name),
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
                            'value'     =>
                                static
                                function (
                                    Content $model,
                                ) {
                                    return
                                        StatusHelper::statusLabel($model->status)
                                        . '<hr>' .
                                        StatusHelper::activation($model->id, $model->status);
                                },
                            'filter'    => StatusHelper::statusList(),
                            'format'    => 'raw',
                            'label'     => 'статус',
                        ],
                        'view_count',
                        'comments_count',
                        //'text:ntext',
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
                'GridView-widget ', CONTENT_PARTINDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
