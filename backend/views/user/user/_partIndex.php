<?php
    
    use backend\assets\TopScriptAsset;
    use backend\helpers\StatusHelper;
    use core\edit\entities\User\User;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $url string */
    /* @var $searchModel core\edit\search\User\UserSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const USER_PARTINDEX_LAYOUT = '#user_user_partIndex';
    echo PrintHelper::layout(USER_PARTINDEX_LAYOUT);
    
    $label = 'Пользователи';

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
                    'class' => 'bg-dark text-white p-1',
                ],
                'tableOptions'   => ['class' => 'table table-striped table-bordered'],
                
                'columns' => [
                    'id',
                    [
                        'attribute' => 'login',
                        'value'     => static function (User $model)
                        use ($url) {
                            return Html::a(
                                Html::encode($model->username),
                                [
                                    $url . 'view',
                                    'id' => $model->id,
                                ],
                            );
                        },
                        'format'    => 'raw',
                        'label'     => 'Пользователь',
                    ],
                    [
                        'attribute' => 'login',
                        'value'     => static function (User $model) {
                            return ($model->hasPerson())
                                ?
                                implode(
                                    ', ', ArrayHelper::getColumn
                                (
                                    $model->persons, 'name',
                                ),
                                )
                                :
                                Html::a(
                                    'Добавить',
                                    [
                                        'user/person/create',
                                        'id' => $model->id,
                                    ],
                                );
                        },
                        'format'    => 'raw',
                        'label'     => 'Профили',
                    ],
                    [
                        'attribute' => 'created_at',
                        'filter'    => $this->render(
                            '/layouts/partials/_date_filter', [
                            'model'          => $searchModel,
                            'attribute_from' => 'created_at_from',
                            'attribute_to'   => 'created_at_to',
                        ],
                        ),
                        'label'     => 'Время создания',
                        'format'    => ['date', 'php:Y-m-d'],
                    ],
                    'email:email',
                    [
                        'attribute' => 'status',
                        'value'
                                    => static
                        function (
                            User $model,
                        ) {
                            return
                                StatusHelper::statusLabel($model->status)
                                . '<hr>' .
                                StatusHelper::activation($model->id, $model->status);
                        },
                        'filter'    => StatusHelper::simpleList(),
                        'format'    => 'raw',
                        'label'     => 'статус',
                    ],
                    [
                        'class' => ActionColumn::class, 'template' => '{delete}',
                    ],
                ],
            ],
        );
    }
    catch (Exception|Throwable $e) {
        PrintHelper::exception(
            'Widget GridView ', USER_PARTINDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
