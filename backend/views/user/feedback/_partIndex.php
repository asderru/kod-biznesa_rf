<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\User\Feedback;
    use core\edit\search\User\FeedbackSearch;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $url string */
    /* @var $searchModel FeedbackSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const USER_FEEDBACK_PARTINDEX_LAYOUT = '#user_feedback_partIndex';
    echo PrintHelper::layout(USER_FEEDBACK_PARTINDEX_LAYOUT);

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
                    ['class' => SerialColumn::class],
                    
                    [
                        'attribute' => 'name',
                        'label'     => 'Имя',
                        'value'     => static function (
                            Feedback $model,
                        ) use ($url) {
                            return Html::a(
                                Html::encode
                                (
                                    $model->name,
                                ),
                                [
                                    $url . 'update',
                                    'id' => $model->id,
                                ],
                            );
                            
                        },
                        'format'    => 'raw',
                    ],
                    
                    'id',
                    'site_id',
                    //'name',
                    'subject',
                    'body',
                    //'email:email',
                    //'phone',
                    //'text_type',
                    //'parent_id',
                    //'created_at',
                    //'updated_at',
                    //'notes',
                    //'status',
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
            'GridView-widget ', USER_FEEDBACK_PARTINDEX_LAYOUT, $e,
        );
    }
    ?>
</div>
