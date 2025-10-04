<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\User\Person;
    use core\edit\search\User\PersonSearch;
    use core\helpers\IconHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $url string */
    /* @var $searchModel PersonSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const USER_PERSON_PARTINDEX_LAYOUT = '#user_person_partIndex';
    echo PrintHelper::layout(USER_PERSON_PARTINDEX_LAYOUT);


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
                    'class' => 'bg-secondary text-white p-2',
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
                    ['class' => SerialColumn::class],
                    [
                        'value'          => static function (Person $model) {
                            return
                                Html::img(
                                    $model->getImageUrl(3),
                                    [
                                        'class' => 'img-fluid',
                                    ],
                                )
                                ??
                                IconHelper::biEyeFill('смотреть');
                        },
                        'label'          => 'Фото',
                        'format'         => 'raw',
                        'contentOptions' => [
                            'style' => 'width: 100px',
                        ],
                    ],
                    [
                        'attribute' => 'name',
                        'label'     => 'Имя',
                        'value'     => static function (
                            Person $model,
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
                    
                    'id',
                    //'user_id',
                    //'site_id',
                    'first_name',
                    'last_name',
                    //'name',
                    //'date_of_birth',
                    'place',
                    //'country_id',
                    //'photo',
                    //'color',
                    //'description:ntext',
                    //'status',
                    //'sort',
                    //'created_at',
                    //'updated_at',
                    
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
            'GridView-widget ', USER_PERSON_PARTINDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
