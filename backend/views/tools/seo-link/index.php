<?php
    
    use backend\assets\TopScriptAsset;
    use core\read\readers\Admin\SiteModeReader;
    use core\read\readers\Admin\InformationReader;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Tools\SeoLink;
    use core\edit\search\Tools\SeoLinkSearch;
    use core\helpers\ParametrHelper;
    use core\helpers\ParentHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $sites Information */
    /* @var $searchModel SeoLinkSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $textTypes array */
    /* @var $code200 bool */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#tools_seolink_index';
    
    $this->title = $label;
    
    $buttons = [];
    
    $this->params['breadcrumbs'][] = $this->title;
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    );
?>

<div class='card'>
    
    <?= $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>

    <div class='card-body'>
        <?=
            // Форма выбора количества строк
            
            $this->render(
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
                            'caption'        => $label,
                            'captionOptions' => [
                                'class' => 'text-start p-2',
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
                                [
                                    'class' =>
                                        SerialColumn::class,
                                ],
                                'name',
                                [
                                    'attribute' => 'url',
                                    'label'     => 'Ссылка',
                                    'value'     => static function (SeoLink $model) {
                                        return Html::a(
                                            $model->url,
                                            $model->url,
                                        );
                                    },
                                    'format'    => 'raw',
                                ],
                                [
                                    'attribute' => 'site_id',
                                    'label'     => 'Сайт',
                                    'value'     => static function (SeoLink $model) {
                                        return ParametrHelper::getSiteName($model->site_id);
                                    },
                                    'format'    => 'raw',
                                    'filter'    => InformationReader::getDropDownFilter(0),
                                    'visible'   => ParametrHelper::isServer(),
                                ],
                                [
                                    'attribute' => 'text_type',
                                    'label'     => 'Тип текста',
                                    'value'     => static function (SeoLink $model) {
                                        return TypeHelper::getName($model->text_type);
                                    },
                                    'format'    => 'raw',
                                    'filter' => SiteModeReader::getTextTypesMap(),
                                ],
                                [
                                    'attribute' => 'parent_id',
                                    'label'     => 'ID текста',
                                    'format'    => 'raw',
                                ],
                                [
                                    'label'  => 'Источник',
                                    'value'  => static function (SeoLink $model) {
                                        $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
                                        $url = TypeHelper::getView($model->text_type, $model->parent_id);
                                        return
                                            Html::a(
                                                $parent?->name,
                                                $url,
                                                [
                                                    'target' => '_blank',
                                                ],
                                            );
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'class'    => ActionColumn::class,
                                    'template' => '{delete}',
                                ],
                            ],
                        ],
                    );
                }
                catch (Throwable $e) {
                }
            ?>

        </div>

    </div>
</div>
