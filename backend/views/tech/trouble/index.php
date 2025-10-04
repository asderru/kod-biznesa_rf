<?php
    
    use backend\assets\TopScriptAsset;
    use core\read\readers\Admin\SiteModeReader;
    use core\read\readers\Admin\InformationReader;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Tools\MasterLink;
    use core\edit\search\Tech\TroubleSearch;
    use core\helpers\ParametrHelper;
    use core\helpers\ParentHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $sites Information */
    /* @var $searchModel TroubleSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#link_trouble_index';
    
    $this->title = $label;
    
    $buttons = [];
    
    $appType   = Yii::$app->params[('appType')];
    $siteCheck = ($appType === 0) ? null : Parametr::siteId();
    
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
    )
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

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?= Html::a(
            'Раздел',
            [
                'admin/trouble/check',
                'textType' => Constant::RAZDEL_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
        <?= Html::a(
            'Примечания',
            [
                'admin/trouble/check',
                'textType' => Constant::PRODUCT_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
        <?= Html::a(
            'Бренды',
            [
                'admin/trouble/check',
                'textType' => Constant::BRAND_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
        <?= Html::a(
            'Книги',
            [
                'admin/trouble/check',
                'textType' => Constant::BOOK_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
        <?= Html::a(
            'Главы',
            [
                'admin/trouble/check',
                'textType' => Constant::CHAPTER_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
        <?= Html::a(
            'Страницы',
            [
                'admin/trouble/check',
                'textType' => Constant::PAGE_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
        <?= Html::a(
            'Блоги',
            [
                'admin/trouble/check',
                'textType' => Constant::CATEGORY_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
        <?= Html::a(
            'Посты',
            [
                'admin/trouble/check',
                'textType' => Constant::POST_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
        <?= Html::a(
            'Новости',
            [
                'admin/trouble/check',
                'textType' => Constant::NEWS_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
        <?= Html::a(
            'Метки',
            [
                'admin/trouble/check',
                'textType' => Constant::TAG_TYPE,
                'siteId'   => $siteCheck,
            ],
            [
                'class' => 'btn btn-sm btn-outline-dark',
            ],
        )
        ?>
    </div>
</div>

<div class='card-body'>

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
                        'caption'        => 'Битые ссылки',
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
                            [
                                'attribute' => 'url',
                                'label'     => 'Ссылка',
                                'value'     => static function (MasterLink $model) {
                                    $class = 'text-danger';
                                    return Html::a(
                                        $model->url,
                                        $model->url,
                                        [
                                            'class'  => $class,
                                            'target' => '_blank',
                                        ],
                                    );
                                },
                                'format'    => 'raw',
                            ],
                            [
                                'attribute' => 'status',
                                'label'     => 'Статус',
                            ],
                            [
                                'attribute' => 'site_id',
                                'label'     => 'Сайт',
                                'value'     => static function (MasterLink $model) {
                                    return ParametrHelper::getSiteName($model->site_id);
                                },
                                'format'    => 'raw',
                                'filter'    => InformationReader::getDropDownFilter(0),
                                'visible'   => ParametrHelper::isServer(),
                            ],
                            [
                                'attribute' => 'text_type',
                                'label'     => 'Тип текста',
                                'value'     => static function (MasterLink $model) {
                                    return TypeHelper::getName($model->text_type);
                                },
                                'format'    => 'raw',
                                'filter' => SiteModeReader::getTextTypesMap(),
                            ],
                            [
                                'label'  => 'Источник',
                                'value'  => static function (MasterLink $model) {
                                    $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
                                    $url = TypeHelper::getView($model->text_type, $model->parent_id);
                                    return
                                        Html::a(
                                            $parent?->name,
                                            $url,
                                            [
                                                'class'  => 'btn btn-sm btn-outline-dark',
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
