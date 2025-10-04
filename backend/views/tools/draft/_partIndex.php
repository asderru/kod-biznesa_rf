<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\Tools\Draft;
    use core\helpers\ButtonHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\read\readers\Admin\SiteModeReader;
    use core\read\readers\Admin\InformationReader;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel core\edit\search\Tools\DraftSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $roots array */
    /* @var $files array */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const TOOLS_DRAFT_PARTINDEX_LAYOUT = '#tools_draft_partIndex';
    echo PrintHelper::layout(TOOLS_DRAFT_PARTINDEX_LAYOUT);
    
    $label = 'Черновики';
    
    $this->title                   = $label;
    $this->params['breadcrumbs'][] = $this->title;

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
                        'label'  => false,
                        'value'  => static function (Draft $model) {
                            return ButtonHelper::delete($model);
                        },
                        'format' => 'raw',
                    ],
                    'id',
                    [
                        'attribute' => 'name',
                        'label'     => 'Название',
                        'value'     => static function (
                            Draft $model,
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
                        'label'     => 'Тип текста',
                        'value'     => static function () {
                            return TypeHelper::getName(Draft::TEXT_TYPE);
                        },
                        'format'    => 'raw',
                        'filter' => SiteModeReader::getTextTypesMap(),
                    ],
                    [
                        'label'  => 'Источник',
                        'value'  => static function (Draft $model) {
                            $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
                            $url = TypeHelper::getView($model->text_type, $model->parent_id);
                            return
                                ($model->text_type !== 0 && $model->parent_id !== 0) ? Html::a(
                                    $parent?->name,
                                    $url,
                                ) : null;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format'    => 'dateTime',
                    ],
                    'description:ntext',
                    [
                        'attribute' => 'site_id',
                        'label'     => 'Сайт',
                        'value'     => static function (Draft $model) {
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
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'GridView_widget ', TOOLS_DRAFT_PARTINDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
