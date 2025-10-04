<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Content\Note;
    use core\edit\entities\Content\Tag;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Seo\Anons;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Seo\Footnote;
    use core\edit\entities\Seo\Material;
    use core\edit\entities\Seo\News;
    use core\edit\entities\Shop\Brand;
    use core\edit\entities\Tools\Draft;
    use core\edit\entities\User\Person;
    use core\edit\search\Blog\PostSearch;
    use core\edit\search\Forum\ThreadSearch;
    use core\edit\search\Library\ChapterSearch;
    use core\edit\search\Magazin\ArticleSearch;
    use core\edit\search\Shop\ProductSearch;
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
    /* @var $searchModel ProductSearch|ArticleSearch|ChapterSearch|PostSearch|ThreadSearch| */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $textType int */
    /* @var $full bool */
    
    const MODELS_INDEX_LAYOUT = '#layouts_models_index';
    echo PrintHelper::layout(MODELS_INDEX_LAYOUT);
    
    $caption = TypeHelper::getName($textType, null, false, true);
    
    $img = match ($textType) {
        Constant::NEWS_TYPE     => static function (News $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
        Constant::BRAND_TYPE    => static function (Brand $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
        Constant::AUTHOR_TYPE   => static function (Author $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
        Constant::ANONS_TYPE    => static function (Anons $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
        Constant::PERSON_TYPE   => static function (Person $model) {
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
        Constant::FOOTNOTE_TYPE => static function (Footnote $model) {
            return
                Html::img(
                    $model->getImageUrl(1),
                    [
                        'class' => 'img-fluid',
                    ],
                );
        },
        default                 => null,
    };
    
    $name = match ($textType) {
        Constant::NEWS_TYPE     => static function (
            News $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'seo/news/view' : 'express/news/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
        Constant::MATERIAL_TYPE => static function (
            Material $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'seo/material/view' : 'express/material/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
        Constant::BRAND_TYPE    => static function (
            Brand $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'shop/brand/view' : 'express/brand/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
        Constant::AUTHOR_TYPE   => static function (
            Author $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'library/author/view' : 'express/author/view',
                        
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
        Constant::ANONS_TYPE    => static function (
            Anons $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'seo/anons/view' : 'express/anons/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
        Constant::TAG_TYPE      => static function (
            Tag $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'content/tag/view' : 'express/tag/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
        Constant::NOTE_TYPE     => static function (
            Note $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'content/note/view' : 'express/note/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
        Constant::DRAFT_TYPE    => static function (
            Draft $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'tools/draft/view' : 'express/draft/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
        Constant::PERSON_TYPE   => static function (
            Person $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'user/person/view' : 'express/person/view',
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
        Constant::FOOTNOTE_TYPE => static function (
            Footnote $model,
        ) use ($full) {
            $name = ParametrHelper::isServer() ?
                $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
            return Html::a(
                    Html::encode
                    (
                        $name,
                    ),
                    [
                        $full ? 'seo/footnote/view' : 'express/footnote/view',
                        'id' => $model->id,
                    ],
                ) . '<hr>' . FaviconHelper::panelSmall($model);
            
        },
        
        default                 => null,
        
    };
    
    $site = null;
    
    try {
        $site = match ($textType) {
            Constant::NEWS_TYPE     =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (News $model) {
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
            Constant::MATERIAL_TYPE =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Material $model) {
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
            Constant::BRAND_TYPE    =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (
                    Brand $model,
                ) {
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
            Constant::AUTHOR_TYPE   =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Author $model) {
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
            
            Constant::ANONS_TYPE    =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Anons $model) {
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
            
            Constant::TAG_TYPE      =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Tag $model) {
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
            
            Constant::NOTE_TYPE     =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Note $model) {
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
            
            Constant::DRAFT_TYPE    =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
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
                'visible'   => ParametrHelper::isServer() && $full,
            ],
            
            Constant::PERSON_TYPE   =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Person $model) {
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
                            '/seo/faq/view',
                            'id' => $model->site_id,
                        ],
                    );
                },
                'format'    => 'raw',
                'visible'   => ParametrHelper::isServer() && $full,
            ],
            
            Constant::FOOTNOTE_TYPE =>
            [
                'attribute' => 'site_id',
                'label'     => 'Сайт',
                'filter'    => InformationReader::getDropDownFilter(0),
                'value'     => static function (Footnote $model) {
                    return Html::a(
                        ParametrHelper::getSiteName($model->site_id),
                        [
                            '/seo/footnote/view',
                            'id' => $model->site_id,
                        ],
                    );
                },
                'format'    => 'raw',
                'visible'   => ParametrHelper::isServer() && $full,
            ],
            
            default                 => null,
        };
    }
    catch (Exception $e) {
    }
    
    $status = null;
    try {
        $status = match ($textType) {
            Constant::NEWS_TYPE     =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (News $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::MATERIAL_TYPE =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Material $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::BRAND_TYPE    =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Brand $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::AUTHOR_TYPE   =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Author $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::ANONS_TYPE    =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Anons $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::TAG_TYPE      =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Tag $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::NOTE_TYPE     =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Note $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::DRAFT_TYPE    =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Draft $model) {
                    return
                        StatusHelper::statusLabel($model->status)
                        . '<hr>' .
                        StatusHelper::activation($model->id, $model->status);
                },
                'format'    => 'raw',
            ],
            Constant::PERSON_TYPE   =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Person $model) {
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
            
            Constant::FOOTNOTE_TYPE =>
            [
                'attribute' => 'status',
                'label'     => 'статус',
                'filter'    => StatusHelper::statusList(),
                'value'     => static function (Footnote $model) {
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
        PrintHelper::exception(
            'status = match', MODELS_INDEX_LAYOUT, $e,
        );
    }

?>

<?= $this->render(
    '/layouts/partials/_pageSize',
);
?>

<div class='table-responsive'>
    <?php
    try {
        echo GridView::widget(
            [
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
                        'attribute' => 'updated_at',
                        'label'     => 'Время редактирования',
                        'format'    => 'dateTime',
                    ],
                    [
                        'attribute' => 'sort',
                        'label'     => 'Сортировка',
                    ],
                    $site,
                    $status,
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'GridView_widget', MODELS_INDEX_LAYOUT, $e,
        );
        
    }
    ?>
</div>
