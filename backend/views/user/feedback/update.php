<?php
    
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\User\FeedbackForm */
    /* @var $feedback core\edit\entities\User\Feedback */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_feedback_update';
    
    $this->title                   = 'Правка уведомления: ' . $model->name;
    $this->params['breadcrumbs'][] = [
        'label' => 'Уведомления',
        'url'   => ['index'],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $feedback->name,
        'url'   => [
            'view',
            'id' => $feedback->id,
        ],
    ];
    $this->params['breadcrumbs'][] = 'Правка';
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    )
?>

<div class='card bg-light'>

    <div class='card-header bg-gray'>
        <h3>
            <?= Html::encode($this->title)
            ?>
        </h3>
    </div>


    <div class='card-body'>
        <div class='card-header bg-light'>
            <strong>
                Общая информация
            </strong>
        </div>
        <div class='card-body'>

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
                                                'model'      => $feedback,
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
                                            'DetailView-widget ', LAYOUT_ID, $e,
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
                                                'model'      => $feedback,
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
                                            'DetailView-widget ', LAYOUT_ID, $e,
                                        );
                                    } ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <?php
                $form = ActiveForm::begin(
                    [
                        'options'     => [
                            'class' => 'active__form',
                        ],
                        'fieldConfig' => [
                            'errorOptions' => [
                                'encode' => false,
                                'class'  => 'help-block',
                            ],
                        ],
                    ],
                )
            ?>
            
            <?= $form->field($model, 'notes')->textarea(
                [
                    'rows' => 6,
                ],
            )
                ->label('Редактировать заметку')
            ?>
            
            <?= ButtonHelper::submit()
            ?>
            
            <?php
                ActiveForm::end(); ?>


        </div>
    </div>
</div>
