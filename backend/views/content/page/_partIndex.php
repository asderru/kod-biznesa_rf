<?php


    use core\edit\entities\Content\Page;
    use core\edit\search\Content\PageSearch;
    use core\helpers\GridStatusHelper;
    use core\helpers\PrintHelper;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;

    /* @var $this yii\web\View */
    /* @var $searchModel PageSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $url string */
    /* @var $textType int */
    /* @var $full bool */

    const PAGE_INDEX_LAYOUT = '#content_page_partIndex';
    echo PrintHelper::layout(PAGE_INDEX_LAYOUT) .
         $this->render('/layouts/partials/_pageSize', ['url' => 'index']);
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
                            'caption'        => 'Страницы',
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
                                    GridStatusHelper::getImageColumnConfig(),
                                    GridStatusHelper::getNameColumnConfig($url),
                                    ...GridStatusHelper::getNestedColumnsConfig($searchModel, Page::class, $full),
                                    GridStatusHelper::getSiteColumnConfig(),
                                    [
                                            'attribute' => 'updated_at',
                                            'label'     => 'Время обновления',
                                            'format'    => 'dateTime',
                                            'visible'   => $full,
                                    ],
                                    GridStatusHelper::getStatusColumnConfig($textType),
                            ],
                    ],
            );
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                    'GridView-widget ', PAGE_INDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
