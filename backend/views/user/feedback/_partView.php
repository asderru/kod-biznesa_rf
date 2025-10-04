<?php
    
    use core\helpers\PrintHelper;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\User\Feedback */
    
    const USER_FEEDBACK_PART_LAYOUT = '#user_feedback_partView';
    echo PrintHelper::layout(USER_FEEDBACK_PART_LAYOUT);

?>

<div class='row'>

    <div class='col-xl-6'>

        <div class='card mb-3'>

            <div class='card-header bg-gray'>
                <strong>
                    Информация
                </strong>
            </div>
            <div class='card-body'>

                <div class='table-responsive'>
                    
                    <?php
                        try {
                            echo DetailView::widget(
                                [
                                    'model'      => $model,
                                    'attributes' => [
                                        'id',
                                        'site.name',
                                        [
                                            'attribute' =>
                                                'type.name',
                                            'label'
                                                        => 'Тип текста',
                                        ],
                                        'parent_id',
                                        [
                                            'attribute' =>
                                                'created_at',
                                            'format'
                                                        => 'dateTime',
                                        ],
                                        [
                                            'attribute' =>
                                                'updated_at',
                                            'format'
                                                        => 'dateTime',
                                        ],
                                        'notes',
                                        'status',
                                    ],
                                ],
                            );
                        }
                        catch (Throwable $e) {
                            PrintHelper::exception(
                                'DetailView-widget ', USER_FEEDBACK_PART_LAYOUT, $e,
                            );
                        } ?>
                </div>
            </div>
        </div>

    </div>

    <div class='col-xl-6'>

        <div class='card mb-3'>

            <div class='card-header bg-gray'>
                <strong>
                    Обращение
                </strong>
            </div>
            <div class='card-body'>

                <div class='table-responsive'>
                    
                    <?php
                        try {
                            echo DetailView::widget(
                                [
                                    'model'      => $model,
                                    'attributes' => [
                                        'name',
                                        'subject',
                                        'body',
                                        'email:email',
                                        'phone',
                                    ],
                                ],
                            );
                        }
                        catch (Throwable $e) {
                            PrintHelper::exception(
                                'DetailView-widget ', USER_FEEDBACK_PART_LAYOUT, $e,
                            );
                        } ?>
                </div>
            </div>
        </div>

    </div>
</div>
