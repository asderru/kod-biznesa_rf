<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\helpers\ModalHelper;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\IconHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    use yii\helpers\HtmlPurifier;
    
    /* @var $this yii\web\View */
    /* @var $book Book */
    /* @var $books Book[] */
    /* @var $searchModel core\edit\search\Library\ChapterSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $model core\edit\forms\SortForm */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_chapter_resort';
    $title = 'Сортировка книги ' . $book->name;
    
    $this->title = $label;
    $this->title = 'Сортировка книги. Книга ' . $book->name . '. #' . $book->id;
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::BOOK_TYPE);
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::CHAPTER_TYPE);
    $this->params['breadcrumbs'][] = [
        'label' => $book->name,
        'url'   => [
            '/library/book/view',
            'id' => $book->id,
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => 'Главы в книге ' . $book->name,
        'url'   => [
            '/library/chapter/index',
            'id' => $book->id,
        ],
    ];
    $this->params['breadcrumbs'][] = $label;
    
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

<div class='card bg-light mb-3'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($title)
            ?>
        </div>
        <div>
            <?= ButtonHelper::create('Добавить')
            ?>
            <?= ButtonHelper::clearCache($book->site_id, $textType) ?>
            <?= ButtonHelper::clearSort($book->id)
            ?>
            <?= ButtonHelper::collapse()
            ?>
        </div>
    </div>
    <div class='small pb-1 px-2'>Сортировке подлежат только активированные тексты! Черновики и архивы не сортируются.
    </div>
    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?= ButtonHelper::urlTo(
            'Экспресс-панель ' . $label,
            '/express/chapter/index',
            'btn-outline-warning text-dark',
        )
        ?>
        <?=
            ButtonHelper::structure($textType, null, 'Структура текстов')
        ?>
        <hr>
        <span class="small strong">
            Упорядочить главы в других книгах:
        </span>
        <?php
            foreach ($books as $book) {
                echo ButtonHelper::resort(
                    $book->id,
                    $book->name . ' (' . $book->site_id . ')',
                );
            }
        ?>
    </div>

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
                    SortableGridView::widget(
                        [
                            'pager' => [
                                'firstPageLabel' => 'в начало',
                                'lastPageLabel'  => 'в конец',
                            ],
                            
                            'dataProvider' => $dataProvider,
                            'layout'       => "{summary}\n{pager}\n{items}\n{pager}",
                            'tableOptions' => ['class' => 'table table-striped table-bordered'],
                            'columns'      => [
                                [
                                    'attribute' => 'sort',
                                    'label'     => 'Номер по порядку',
                                    'value'     => static function (Chapter $model) {
                                        return $model->sort . ' -
                                        <button 
                                                type = "button" 
                                                class="badge bg-primary" 
                                                data-bs-toggle = "modal" 
                                                data-bs-target = "#sortModal"
                                                data-bs-whatever=' . $model->id . '
                                                > 
                                                изменить вручную 
                                                </button >';
                                    },
                                    'format'    => 'raw',
                                ],
                                [
                                    'value'          => static function (Chapter $model) {
                                        return
                                            Html::img(
                                                $model->getImageUrl(1),
                                                [
                                                    'class' => 'img-fluid',
                                                ],
                                            )
                                            ??
                                            IconHelper::biEyeFill('смотреть');
                                    },
                                    'label'          => 'Изображение',
                                    'format'         => 'raw',
                                    'contentOptions' => [
                                        'style' => 'width: 130px',
                                    ],
                                ],
                                [
                                    'attribute' => 'name',
                                    'label'     => 'Название',
                                    'value'     => static function (Chapter $model) {
                                        return Html::a(
                                            HtmlPurifier::process($model->name),
                                            [
                                                'view',
                                                'id' => $model->id,
                                            ],
                                        );
                                        
                                    },
                                    'format'    => 'raw',
                                ],
                                'id',
                            ],
                        ],
                    );
                }
                catch (Exception|Throwable $e) {
                    PrintHelper::exception(
                        $actionId, 'Widget GridView ' . LAYOUT_ID,
                        $e,
                    );
                } ?>
        </div>
    </div>

</div>


<!-- Modal -->
<?= ModalHelper::setSort($model)
?>
