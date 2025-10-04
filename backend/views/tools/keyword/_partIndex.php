<?php
    
    use backend\assets\TopScriptAsset;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Tools\Keyword;
    use core\edit\search\Tools\KeywordSearch;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\read\readers\Admin\InformationReader;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel KeywordSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    
    const TOOLS_KEYWORDS__PARTINDEX_LAYOUT = '#tools_keyword_partIndex';
    echo PrintHelper::layout(TOOLS_KEYWORDS__PARTINDEX_LAYOUT);
    
    $label = 'Ключевые слова';
    
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
                    'id',
                    'sort',
                    [
                        'attribute' => 'name',
                        'label'     => 'Ключевое слово',
                        'value'     => static function (
                            Keyword $model,
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
                        'attribute' => 'text_type',
                        'label'     => 'Тип источника',
                        'filter'
                                    =>
                            $searchModel::shopTypesList(),
                        'value'     => static function (Keyword $model) {
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
                        'value'     => static function (Keyword $model) {
                            return Html::a(
                                Html::encode
                                (
                                    $model->parent->name,
                                ),
                                [
                                    TypeHelper::getLongEditUrl($model->text_type) . 'view',
                                    'id' => $model->parent_id,
                                ],
                            );
                        },
                        'format'    => 'raw',
                    ],
                    [
                        'attribute' => 'site_id',
                        'label'     => 'Сайт',
                        'value'     => static function (Keyword $model) {
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
                        'value'     =>
                            static
                            function (
                                Keyword $model,
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
            $actionId, 'GridView-widget ' . TOOLS_KEYWORDS__PARTINDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
