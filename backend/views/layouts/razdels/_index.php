<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Shop\Razdel;
    use core\edit\search\Blog\CategorySearch;
    use core\edit\search\Content\PageSearch;
    use core\edit\search\Forum\GroupSearch;
    use core\edit\search\Library\BookSearch;
    use core\edit\search\Magazin\SectionSearch;
    use core\edit\search\Shop\RazdelSearch;
    use core\helpers\FaviconHelper;
    use core\helpers\IconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\InformationReader;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $searchModel RazdelSearch|SectionSearch|BookSearch|CategorySearch|GroupSearch|PageSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $textType int */
    /* @var $full bool */
    
    const RAZDELS_INDEX_LAYOUT = '#layouts_razdels_index';
    echo PrintHelper::layout(RAZDELS_INDEX_LAYOUT);
    
    $caption = TypeHelper::getName($textType, null, false, true);
    
    $img = match ($textType) {
        Constant::SECTION_TYPE  => static function (Section $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
        Constant::CATEGORY_TYPE => static function (Category $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
        Constant::GROUP_TYPE    => static function (Group $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
        Constant::PAGE_TYPE     => static function (Page $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
        Constant::FAQ_TYPE      => static function (Faq $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
    };
    
    $name = match ($textType) {
        Constant::SECTION_TYPE  => static function (
            Section $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'magazin/section/view' : 'express/section/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        Constant::CATEGORY_TYPE => static function (
            Category $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'blog/category/view' : 'express/category/view',
                        
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        Constant::GROUP_TYPE    => static function (
            Group $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'forum/group/view' : 'express/group/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        Constant::PAGE_TYPE     => static function (
            Page $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'content/page/view' : 'express/page/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        Constant::FAQ_TYPE      => static function (
            Faq $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'seo/faq/view' : 'express/faq/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
    };
    
    $site = null;
    try {
        $site = match ($textType) {
            Constant::SECTION_TYPE  =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Section $model) {
                    return Html::a(
                        ParametrHelper::getSiteName($model->site_id),
                        [
                            '/admin/information/view',
                            'id' => $model->site_id,
                        ],
                    );
                },
                'format'    => 'raw',
                'visible'   => ParametrHelper::isServer() && $full,
            ],
            Constant::CATEGORY_TYPE =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Category $model) {
                    return Html::a(
                        ParametrHelper::getSiteName($model->site_id),
                        [
                            '/admin/information/view',
                            'id' => $model->site_id,
                        ],
                    );
                },
                'format'    => 'raw',
                'visible'   => ParametrHelper::isServer() && $full,
            ],
            Constant::GROUP_TYPE    =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Group $model) {
                    return Html::a(
                        ParametrHelper::getSiteName($model->site_id),
                        [
                            '/admin/information/view',
                            'id' => $model->site_id,
                        ],
                    );
                },
                'format'    => 'raw',
                'visible'   => ParametrHelper::isServer() && $full,
            ],
            Constant::PAGE_TYPE     =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Page $model) {
                    return Html::a(
                        ParametrHelper::getSiteName($model->site_id),
                        [
                            '/admin/information/view',
                            'id' => $model->site_id,
                        ],
                    );
                },
                'format'    => 'raw',
                'visible'   => ParametrHelper::isServer() && $full,
            ],
            Constant::FAQ_TYPE      =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Faq $model) {
                    return Html::a(
                        ParametrHelper::getSiteName($model->site_id),
                        [
                            '/admin/information/view',
                            'id' => $model->site_id,
                        ],
                    );
                },
                'format'    => 'raw',
                'visible'   => ParametrHelper::isServer() && $full,
            ],
            
        };
    }
    catch (Exception $e) {
    }
    
    $status = null;
    try {
        $status = match ($textType) {
            Constant::SECTION_TYPE  =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Section $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::CATEGORY_TYPE =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Category $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::GROUP_TYPE    =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Group $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::PAGE_TYPE     =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Page $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::FAQ_TYPE      =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Faq $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            
        };
    }
    catch (Exception $e) {
    }
    $priceVisible = match ($textType) {
        Constant::RAZDEL_TYPE => true,
        default               => false,
    };


?>

<?= $this->render(
    '/layouts/partials/_pageSize',
);
?>

<div class='table-responsive'>
    <?php
    try {
        echo GridView::widget([
            'pager'          => [
                'firstPageLabel' => 'в начало',
                'lastPageLabel'  => 'в конец',
            ],
            'dataProvider'   => $dataProvider,
            'filterModel'    => $searchModel,
            'caption'        => $caption,
            'captionOptions' => [
                'class' => 'text-start p-2',
            ],
            'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
            'summary'        => 'показаны значения с {begin} по {end} из <b>{totalCount}</b>',
            'summaryOptions' => [
                'class' => 'bg-success-subtle text-black px-2',
            ],
            'tableOptions'   => [
                'id'    => 'point-of-grid-view',
                'class' => 'table table-striped table-bordered',
            ],
            'columns'        => [
                [
                    'class' => SerialColumn::class,
                ],
                [
                    'attribute' => 'id',
                    'label'     => 'ID',
                    'value'     => 'id',
                    'filter'    => true,
                ],
                [
                    'attribute' => 'lft',
                    'label'     => 'поле Lft',
                    'value'     => 'lft',
                    'filter'    => '<div class="row g-2">' .
                                   '<div class="col py-2">' .
                                   Html::activeTextInput($searchModel, 'lft_from', [
                                       'placeholder' => 'От',
                                       'class'       => 'form-control',
                                   ]) .
                                   '</div>' .
                                   '<div class="col py-2">' .
                                   Html::activeTextInput($searchModel, 'lft_to', [
                                       'placeholder' => 'До',
                                       'class'       => 'form-control',
                                   ]) .
                                   '</div>' .
                                   '</div>',
                ],
                [
                    'value' => $img ?? IconHelper::biEyeFill('смотреть'),
                    'label'          => 'Изображение',
                    'format'         => 'raw',
                    'contentOptions' => [
                        'style' => 'width: 130px',
                    ],
                    'visible'        => $full,
                ],
                [
                    'attribute' => 'name',
                    'label'     => ParametrHelper::isServer() ? 'Название (id сайта)' : 'Название',
                    'value'     => $name,
                    'format'    => 'raw',
                ],
                [
                    'attribute' => 'depth',
                    'label'     => 'Глубина вложенности',
                    'filter'    => $searchModel::depthAmountList(),
                ],
                [
                    'attribute' => 'site_id',
                    'label'     => 'Сайт',
                    'value'     => static function (Razdel $model) {
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
                    'attribute' => 'updated_at',
                    'label'     => 'Время редактирования',
                    'format'    => 'dateTime',
                ],
                $site,
                $status,
            ],
        ]);
    }
    catch (Throwable $e) {
    
    }
    ?>
</div>
