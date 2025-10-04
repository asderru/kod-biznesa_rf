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
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel ContentSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const TOOLS_DESCRIPTION_PARTINDEX_LAYOUT = '#tools_description_partIndex';
    echo PrintHelper::layout(TOOLS_DESCRIPTION_PARTINDEX_LAYOUT);
    
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
                        'attribute' => 'status',
                        'label'     => 'статус',
                        'filter'    => StatusHelper::statusList(),
                        'value'     => static function (Content $model) {
                            return
                                StatusHelper::faviconStatusList($model->status);
                        },
                        'format'    => 'raw',
                    ],
                    [
                        'attribute' => 'name',
                        'label'     => 'Название',
                        'value'     => static function (
                            Content $model,
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
                    [
                        'attribute' => 'description',
                        'label'     => 'Мета-описание',
                    ],
                    'id',
                    [
                        'attribute' => 'text_type',
                        'label'     => 'Тип источника',
                        'filter'
                                    =>
                            $searchModel::contentTypesList(),
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
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'GridView-widget ', TOOLS_DESCRIPTION_PARTINDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
