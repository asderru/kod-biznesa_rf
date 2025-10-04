<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Forum\Thread;
    use core\edit\entities\Magazin\Article;
    use core\edit\search\Blog\PostSearch;
    use core\edit\search\Forum\ThreadSearch;
    use core\edit\search\Library\ChapterSearch;
    use core\edit\search\Magazin\ArticleSearch;
    use core\edit\search\Shop\ProductSearch;
    use core\helpers\FaviconHelper;
    use core\helpers\IconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\ReadHelper;
    use core\helpers\types\TypeHelper;
    use core\read\readers\Admin\InformationReader;
    use core\read\readers\Forum\GroupReader;
    use core\read\readers\Magazin\SectionReader;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\data\ActiveDataProvider;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $searchModel ProductSearch|ArticleSearch|ChapterSearch|PostSearch|ThreadSearch| */
    /* @var $dataProvider ActiveDataProvider */
    /* @var $textType int */
    /* @var $url string */
    /* @var $full bool */
    
    const PRODUCT_INDEX_LAYOUT = '#layouts_products_index';
    
    echo PrintHelper::layout(PRODUCT_INDEX_LAYOUT);
    
    $caption = TypeHelper::getName($textType, null, false, true);
    // Вспомогательные функции
    function generateImage($model): string
    {
        return Html::img($model->getImageUrl(1), ['class' => 'img-fluid']);
    }
    
    function generateNameLink($model): string
    {
        $name = ParametrHelper::isServer() ? $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
        return Html::a(Html::encode($name), ['view', 'id' => $model->id]) . '<hr>' . FaviconHelper::panelSmall($model);
    }
    
    function getWordCount($model): int
    {
        return ReadHelper::getWordCount($model->text);
    }
    
    /**
     * @throws Exception
     */
    function getParentConfig($textType, $full): ?array
    {
        $config = [
            Constant::ARTICLE_TYPE =>
                [
                    'attribute' => 'section_id',
                    'label'     => 'Рубрика',
                    'filter'    =>
                        SectionReader::getDropDownFilter(
                            Constant::THIS_FIRST_NODE,
                            null, null, Constant::STATUS_DRAFT,
                        ),
                    'value'     => static function (Article $model) use ($full) {
                        return
                            $model->section?->getDepthReference(!$full);
                    },
                    'format'    => 'raw',
                    'visible'   => $full,
                ],
            Constant::POST_TYPE    =>
                [
                    'attribute' => 'category_id',
                    'label'     => 'Блог',
                    'filter'    =>
                        CategoryReader::getDropDownFilter(
                            Constant::THIS_FIRST_NODE,
                            null, null, Constant::STATUS_DRAFT,
                        ),
                    'value'     => static function (Post $model) use ($full) {
                        return
                            $model->category?->getDepthReference(!$full);
                    },
                    'format'    => 'raw',
                    'visible'   => $full,
                ],
            Constant::THREAD_TYPE  =>
                [
                    'attribute' => 'group_id',
                    'label'     => 'Форум',
                    'filter'    =>
                        GroupReader::getDropDownFilter(
                            Constant::THIS_FIRST_NODE,
                            null, null, Constant::STATUS_DRAFT,
                        ),
                    'value'     => static function (Thread $model) use ($full) {
                        return
                            $model->group?->getDepthReference(!$full);
                    },
                    'format'    => 'raw',
                    'visible'   => $full,
                ],
        ];
        return $config[$textType] ?? null;
    }
    
    /**
     * @throws Exception
     */
    function getSiteConfig($textType, $full): ?array
    {
        $config = [
            Constant::ARTICLE_TYPE =>
                [
                    'attribute' => 'site_id',
                    'label'     => 'Сайт',
                    'filter'    => InformationReader::getDropDownFilter(0),
                    'value'     => static function (Article $model) {
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
            Constant::POST_TYPE    =>
                [
                    'attribute' => 'site_id',
                    'label'     => 'Сайт',
                    'filter'    => InformationReader::getDropDownFilter(0),
                    'value'     => static function (Post $model) {
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
            Constant::THREAD_TYPE  =>
                [
                    'attribute' => 'site_id',
                    'label'     => 'Сайт',
                    'filter'    => InformationReader::getDropDownFilter(0),
                    'value'     => static function (Thread $model) {
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
        ];
        return $config[$textType] ?? null;
    }
    
    function getStatusConfig($textType): ?array
    {
        $config = [
            Constant::ARTICLE_TYPE =>
                [
                    'attribute' => 'status',
                    'label'     => 'статус',
                    'filter'    => StatusHelper::marketStatusList(),
                    'value'     => static function (Article $model) {
                        return
                            StatusHelper::marketStatusLabel($model->status)
                            . '<hr>' .
                            StatusHelper::activation($model->id, $model->status);
                    },
                    'format'    => 'raw',
                ],
            Constant::POST_TYPE    =>
                [
                    'attribute' => 'status',
                    'label'     => 'статус',
                    'filter'    => StatusHelper::marketStatusList(),
                    'value'     => static function (Post $model) {
                        return
                            StatusHelper::marketStatusLabel($model->status)
                            . '<hr>' .
                            StatusHelper::activation($model->id, $model->status);
                    },
                    'format'    => 'raw',
                ],
            Constant::THREAD_TYPE  =>
                [
                    'attribute' => 'status',
                    'label'     => 'статус',
                    'filter'    => StatusHelper::marketStatusList(),
                    'value'     => static function (Thread $model) {
                        return
                            StatusHelper::marketStatusLabel($model->status)
                            . '<hr>' .
                            StatusHelper::activation($model->id, $model->status);
                    },
                    'format'    => 'raw',
                ],
        ];
        return $config[$textType] ?? null;
    }
    
    $img = match ($textType) {
        Constant::ARTICLE_TYPE,
        Constant::POST_TYPE,
        Constant::THREAD_TYPE => static function ($model) {
            return generateImage($model);
        },
        default               => null,
    };
    
    $name = match ($textType) {
        Constant::ARTICLE_TYPE => static function (Article $model) use ($full) {
            return generateNameLink($model);
        },
        Constant::POST_TYPE    => static function (Post $model) use ($full) {
            return generateNameLink($model);
        },
        Constant::THREAD_TYPE  => static function (Thread $model) use ($full) {
            return generateNameLink($model);
        },
    };
    
    $words = match ($textType) {
        Constant::ARTICLE_TYPE, Constant::POST_TYPE, Constant::THREAD_TYPE => static function ($model) {
            return ReadHelper::getWordCount($model->text);
        },
        default                                                            => null,
    };
    
    try {
        $parent = getParentConfig($textType, $full);
    }
    catch (Exception $e) {
        PrintHelper::exception(
            'getParentConfig', PRODUCT_INDEX_LAYOUT, $e,
        );
    }
    
    try {
        $site = getSiteConfig($textType, $full);
    }
    catch (Exception $e) {
        PrintHelper::exception(
            'getSiteConfig', PRODUCT_INDEX_LAYOUT, $e,
        );
    }
    
    $status = getStatusConfig($textType);

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
                        'label' => 'Слов в тексте',
                        'value' => $words,
                    ],
                    [
                        'attribute' => 'updated_at',
                        'label'     => 'Время редактирования',
                        'format'    => 'dateTime',
                    ],
                    [
                        'attribute' => 'id',
                        'label'     => 'ID',
                    ],
                    [
                        'attribute' => 'sort',
                        'label'     => 'Сортировка',
                    ],
                    $parent ?? null,
                    $site ?? null,
                    $status ?? null,
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            ' GridView::widget', PRODUCT_INDEX_LAYOUT, $e,
        );
        
    }
    ?>
</div>
