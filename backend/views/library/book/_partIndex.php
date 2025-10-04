<?php
    
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Library\Book;
    use core\edit\search\Library\BookSearch;
    use core\helpers\FaviconHelper;
    use core\helpers\IconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\InformationReader;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    /* @var $this yii\web\View */
    /* @var $searchModel BookSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $url string */
    /* @var $full bool */
    
    const BOOK_INDEX_LAYOUT = '#library_book_partIndex';
    echo PrintHelper::layout(BOOK_INDEX_LAYOUT);

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
                    'caption'        => 'Книги',
                    'captionOptions' => [
                        'class' => 'text-start p-2',
                    ],
                    'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                    'summary'        => 'показаны значения с {begin} по {end} из <b>{totalCount}</b>',
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
                            'attribute' => 'id',
                            'label'     => 'ID',
                            'value'     => 'id',
                            'filter'    => true,
                        ],
                        [
                            'value'          => static function (Book $model) {
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
                            'label'     => ParametrHelper::isServer() ?
                                'Название (id сайта)' : 'Название',
                            'value'     => static function (
                                Book $model,
                            ) use ($url) {
                                $name = ParametrHelper::isServer() ?
                                    $model->name . ' #id' . $model->id . '(' . $model->site_id . ')'
                                    : $model->name;
                                return Html::a(
                                        $name,
                                        [
                                            $url . 'view',
                                            'id' => $model->id,
                                        ],
                                    ) . '<hr>' . FaviconHelper::panelSmall($model);
                                
                            },
                            'format'    => 'raw',
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
                            'visible'   => $full,
                        ],
                        [
                            'attribute' => 'depth',
                            'label'     => 'Глубина вложенности',
                            'filter' => Book::getDepthAmount(),
                            'visible'   => $full,
                        ],
                        [
                            'attribute' => 'site_id',
                            'label'     => 'Сайт',
                            'value'     => static function (Book $model) {
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
                            'visible'   => $full,
                        ],
                        [
                            'attribute' => 'status',
                            'label'     => 'статус',
                            'filter'    => StatusHelper::statusList(),
                            'value'     => static function (Book $model) {
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
                'GridView-widget ', BOOK_INDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
