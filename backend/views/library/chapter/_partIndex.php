<?php
    
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Library\Chapter;
    use core\edit\search\Library\BookSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\IconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\ReadHelper;
    use core\read\readers\Library\BookReader;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    
    /* @var $this yii\web\View */
    /* @var $searchModel BookSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $books array */
    /* @var $url string */
    /* @var $full bool */
    
    const CHAPTER_PARTINDEX_LAYOUT = '#library_chapter_partIndex';
    echo PrintHelper::layout(CHAPTER_PARTINDEX_LAYOUT);

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
                    'caption'        => 'Тексты',
                    'captionOptions' => [
                        'class' => 'text-start p-2',
                    ],
                    'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                    'summaryOptions' => [
                        'class' => 'bg-secondary text-white p-1',
                    ],
                    'tableOptions'   => [
                        'id'    => 'point-of-grid-view',
                        'class' => 'table table-striped table-bordered',
                    ],
                    'columns'        => [
                        [
                            'attribute' => 'id',
                            'label'     => 'ID',
                            'value'     => 'id',
                            'filter'    => true,
                        ],
                        [
                            'attribute' => 'sort',
                            'label'     => 'Глава',
                            'filter'    => true,
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
                            'attribute' => 'name',
                            'label'     => ParametrHelper::isServer() ? 'Название (id сайта)' : 'Название',
                            'value'     => static function (Chapter $model)
                            use ($url) {
                                $name = ParametrHelper::isServer() ?
                                    $model->name . ' #id' . $model->id . '(' . $model->site_id . ')' : $model->name;
                                return Html::a(
                                        $name,
                                        [
                                            $url . 'chapter/view',
                                            'id' => $model->id,
                                        ],
                                    ) . '<hr>' . FaviconHelper::panelSmall($model);
                                
                            },
                            'format'    => 'raw',
                        ],
                        [
                            'label'   => 'Слов в тексте',
                            'value'   => static function (
                                Chapter $model,
                            ) {
                                return ReadHelper::getWordCount($model->text);
                            },
                            'format'  => 'raw',
                            'visible' => $full,
                        ],
                        [
                            'attribute' => 'updated_at',
                            'label'     => 'Время редактирования',
                            'format'    => 'dateTime',
                            'visible'   => $full,
                        ],
                        [
                            'attribute' => 'book_id',
                            'label'     => 'Раздел',
                            'filter'    => BookReader::getDropDownFilter(
                                Constant::THIS_FIRST_NODE, null, null, Constant::THIS_FIRST_NODE,
                            ),
                            'value'     => static function (Chapter $model) use ($url) {
                                $book = $model->mainBook;
                                return Html::a(
                                        self::getModelDepthName(
                                            1, $book->depth, $book->id,
                                            $book->name,
                                        ),
                                        [
                                            $url . 'book/view',
                                            'id' => $model->book_id,
                                        ],
                                    )
                                       . '<br>'
                                       . ButtonHelper::modalProductBadge(Constant::BOOK_TYPE, $model->book_id);
                            },
                            'format'    => 'raw',
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
                        [
                            'attribute' => 'site_id',
                            'label'     => 'Сайт',
                            'filter'    => StatusHelper::statusList(),
                            'value'     => static function (Chapter $model) {
                                return $model->site->name;
                            },
                        ],
                    ],
                ],
            );
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                'GridView_widget', CHAPTER_INDEX_LAYOUT, $e,
            );
            
        }
    
    ?>
</div>

<?php
    foreach ($books as $book): ?>

        <!-- Modal -->
        <div class='modal fade' id="product-modal-<?= Constant::BOOK_TYPE ?>-<?= $book['id'] ?>" tabindex='-1'
             aria-labelledby="product-modal-label-<?= Constant::BOOK_TYPE ?>-<?= $book['id'] ?>" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'
                            id="product-modal-label-<?= Constant::BOOK_TYPE ?>-<?= $book['id'] ?>"><?= Html::encode($book['name']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <?= $this->render(
                            '/layouts/modal/_modalCat',
                            [
                                'model'    => $book,
                                'textType' => Constant::BOOK_TYPE,
                            ],
                        )
                        ?>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    
    <?php
    endforeach;
?>
