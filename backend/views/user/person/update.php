<?php
    
    use backend\tools\TinyHelper;
    use backend\widgets\PagerWidget;
    use core\read\readers\Admin\SiteModeReader;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\User\PersonForm */
    /* @var $person core\edit\entities\User\Person */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_person_update';
    
    $this->title = 'Правка профиля: ' . $person->name;
        $form           = ActiveForm::begin(
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
    );

?>
    <div class="card">
        <div class='card-body'>

            <div class='row mb-3'>

                <div class='col-xl-6'>

                    <div class='card h-100'>
                        <div class='card-header bg-light'>
                            <strong>
                                Общая информация
                            </strong>
                        </div>
                        <div class='card-body'>
                            <?= $form->field($model, 'firstName')->textInput(['maxlength' => true])
                            ?>
                            
                            <?= $form->field($model, 'lastName')->textInput(['maxlength' => true])
                            ?>
                            
                            <?= $form->field($model, 'name')->textInput(
                                [
                                    'maxlength' => true,
                                    'required'  => true,
                                ],
                            )
                                     ->label(
                                         'Псевдоним, которым буду подписываться тексты на форуме. Только латинские
                                 буквы и цифры! Больше 5 знаков.',
                                     )
                            ?>
                            
                            <?= $form->field($model, 'dateOfBirth')->textInput()
                            ?>
                            
                            <?= $form->field($model, 'place')->textInput(['maxlength' => true])
                            ?>
                        </div>
                        <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                            <?= ButtonHelper::submit()
                            ?>
                        </div>

                    </div>
                </div>

                <div class='col-xl-6'>

                    <div class='card mb-3'>

                        <div class='card-header bg-light'>
                            <strong>
                                О себе
                            </strong>
                        </div>

                        <div class='card-body'>
                            
                            <?= $form->field($model, 'position')->textInput(['maxlength' => true])
                            ?>
                            
                            <?= $form->field($model, 'company')->textInput(['maxlength' => true])
                            ?>
                            
                            
                            <?= $form->field(
                                $model, 'description',
                                [
                                    'inputOptions' => [
                                        'id' => 'description-edit-area',
                                    ],
                                ],
                            )
                                ->textarea(
                                        [
                                                'rows' => 5,
                                        ]
                                )
                            ?>
                        </div>

                    </div>

                </div>

            </div>
        </div>
<?php
    echo '</div>';
    
    echo Html::activeHiddenInput(
    $model, 'site_id',
    [
        'value' => $model->site_id,
    ],
    );
    
    echo Html::activeHiddenInput(
    $model, 'user_id',
    [
        'value' => $model->userId,
    ],
    );
    
    echo Html::activeHiddenInput(
    $model, 'status',
    [
        'value' => $model->status,
    ],
    );
    echo Html::activeHiddenInput(
    $model, 'country',
    [
        'value' => 185,
    ],
    );
    echo Html::activeHiddenInput(
        $model, 'slug',
        [
            'value' => $model->slug,
        ],
    );
    ActiveForm::end();
