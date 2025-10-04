<?php
    
    use backend\assets\TopScriptAsset;
    use backend\helpers\BreadCrumbHelper;
    use backend\helpers\StatusHelper;
    use backend\widgets\PagerWidget;
    use core\edit\entities\Library\Chapter;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\search\Library\ChapterSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\IconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\ReadHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    use yii\helpers\Url;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Library\Book */
    /* @var $faqs core\edit\entities\Seo\Faq[] */
    /* @var $parents core\edit\entities\Library\Book[] */
    /* @var $editItems core\edit\entities\Admin\Edit */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $searchModel ChapterSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $roots array */
    
    const LAYOUT_ID                = '#library_book_view';
    
    $modelSeoLink = Url::to($model['link'], true);

// Формирование заголовка
    $this->title = $model->isRoot() ? $label : $model->name;
    
    $buttons = [
        ButtonHelper::indexType(Constant::CHAPTER_TYPE, 'Все главы'),
        ButtonHelper::fullActivationSet($model),
        ButtonHelper::update($model->id),
        ButtonHelper::expressType($textType, $model->id),
        ButtonHelper::structure($textType, null, 'Структура библиотеки'),
        ButtonHelper::updateHtml($model),
        ButtonHelper::keywords($model),
        ButtonHelper::content($model->id),
        ButtonHelper::contentView($model->content_id),
        ButtonHelper::contentUpdate($model->content_id),
        ButtonHelper::create('Добавить том'),
        (!$model->isRoot()) ? ButtonHelper::createType(Constant::CHAPTER_TYPE, $model->id, 'Добавить в том ' . $model->name) : null,
        (!$model->isRoot()) ? ButtonHelper::copy($model->id) : null,
        (!$model->isRoot()) ? ButtonHelper::copyToDraft($model->id) : null,
        (!$model->isRoot()) ? ButtonHelper::resort(
            $model->site_id,
            'Сортировать книги на сайте ' . $model->site->name,
        ) : null,
        (!$model->isRoot()) ? ButtonHelper::resortModel(
            Constant::CHAPTER_TYPE,
            $model->id,
            'Сортировать главы в книге ' . $model->name,
        ) : null,
        (!$model->isRoot()) ? ButtonHelper::onSite($model) : null,
        ButtonHelper::addPhone($textType, $model->id),
        ButtonHelper::changeParent($textType, $model->id, 'Сменить сайт'),
        ButtonHelper::color($model->id),
        ButtonHelper::bing($model->id),
        ButtonHelper::clearCache($model->site_id, $textType, $model->id),
        ButtonHelper::delete($model),
    ];
    $buttons[] = ButtonHelper::relationSet($model::TEXT_TYPE, $model->id);
    $buttons[] = ButtonHelper::editPanelsSet($model);
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::BOOK_TYPE);
    foreach ($parents as $parent) {
        if ($parent->depth > Constant::STATUS_ROOT) {
            $this->params['breadcrumbs'][] = [
                'label' => $parent->name,
                'url'   => [
                    '/library/book/view',
                    'id' => $parent->id,
                ],
            ];
        }
    }
    
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
    
    try {
        echo
        PagerWidget::widget(
            [
                'model'  => $model,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_viewHeaderModel',
        [
            'model' => $model,
            'textType' => $textType,
            'buttons'  => $buttons, // передаем массив кнопок
        ],
    )
?>

    <div class="card-body">
        
        <?= $this->render(
            '/layouts/razdels/_partView',
            [
                'model'      => $model,
                'editItems'  => $editItems,
                'uploadForm' => $uploadForm,
            ],
        )
        ?>
    </div>

    <div class='card-header bg-light'>
        <strong>
            Тексты в книге
        </strong>
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
                            'caption'        => 'Тексты',
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
                                    'value'          => static function (Chapter $model) {
                                        return Html::img(
                                            $model->getImageUrl(1),
                                            [
                                                'class' => 'img-fluid',
                                            ],
                                        ) ?? IconHelper::biEyeFill('смотреть');
                                    },
                                    'label'          => 'Изображение',
                                    'format'         => 'raw',
                                    'contentOptions' => [
                                        'style' => 'width: 130px',
                                    ],
                                ],
                                [
                                    'attribute' => 'id',
                                    'label'     => 'ID',
                                ],
                                [
                                    'attribute' => 'sort',
                                    'label'     => 'Сортировка',
                                ],
                                [
                                    'attribute' => 'name',
                                    'label'     => ParametrHelper::isServer() ? 'Название (id сайта)' : 'Название',
                                    'value'     => static function (
                                        Chapter $model,
                                    ) {
                                        $name = ParametrHelper::isServer() ?
                                            $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
                                        return Html::a(
                                                $name,
                                                [
                                                    'library/chapter/view',
                                                    'id' => $model->id,
                                                ],
                                            ) . '<hr>' . FaviconHelper::panel($model);
                                        
                                    },
                                    'format'    => 'raw',
                                ],
                                [
                                    'label'  => 'Слов в тексте',
                                    'value'  => static function (
                                        Chapter $model,
                                    ) {
                                        return ReadHelper::getWordCount($model->text);
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'label'     => 'Время редактирования',
                                    'format'    => 'dateTime',
                                ],
                                [
                                    'attribute' => 'status',
                                    'label'     => 'статус',
                                    'filter'    => StatusHelper::statusList(),
                                    'value'     => static function (Chapter $model) {
                                        return
                                            StatusHelper::statusLabel($model->status)
                                            . '<hr>' .
                                            StatusHelper::activation($model->id, $model->status);
                                    },
                                    'format'    => 'raw',
                                ],
                            ],
                        ],
                    );
                }
                catch (Throwable $e) {
                    PrintHelper::exception(
                        'GridView-widget ', LAYOUT_ID, $e,
                    );
                }
            ?>
        </div>
    </div>

<?php
    echo '</div>';
