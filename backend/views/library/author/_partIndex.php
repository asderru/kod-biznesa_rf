<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\arrays\User\UserEditor;
    use core\edit\entities\Library\Author;
    use core\edit\search\Library\AuthorSearch;
    use core\helpers\IconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    /* @var $this yii\web\View */
    /* @var $searchModel AuthorSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $url string */
    
    const AUTHOR_PARTINDEX_LAYOUT = '#library_author_partIndex';
    echo PrintHelper::layout(AUTHOR_PARTINDEX_LAYOUT);

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
                    'caption'        => Html::encode($this->title),
                    'captionOptions' => [
                        'class' => 'text-end p-2',
                    ],
                    'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                    'summaryOptions' => [
                        'class' => 'bg-secondary text-white p-1',
                    ],
                    'tableOptions'   => ['class' => 'table table-striped table-bordered'],
                    'columns'        => [
                        [
                            'class' =>
                                SerialColumn::class,
                        ],
                        [
                            'value'          => static function (Author $model) {
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
                            'value'     => static function (
                                Author $model,
                            ) use ($url) {
                                return Html::a(
                                    Html::encode
                                    (
                                        $model->name,
                                    ),
                                    [
                                        $url . 'view',
                                        'id' => $model->id,
                                    ],
                                );
                                
                            },
                            'format'    => 'raw',
                        ],
                        [
                            'class'    => ActionColumn::class,
                            'template' => '{update} {delete}',
                        ],
                    ],
                ],
            );
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                'GridView-widget ', AUTHOR_PARTINDEX_LAYOUT, $e,
            );
        }
    
    ?>
</div>
