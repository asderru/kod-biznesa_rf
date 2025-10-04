<?php
    
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use core\read\readers\Admin\SiteModeReader;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\jui\DatePicker;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\User\PersonForm */
    /* @var $user core\edit\entities\User\User */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    
    const LAYOUT_ID = '#user_person_create';
    
    $this->title = 'Добавить профиль для пользователя ' .
                   $user->username;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Пользователи',
        'url'   => [
            'user/usr/index',
        ],
    ];
    $this->params['breadcrumbs'][] = ['label' => 'Профили', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
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
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_createHeader',
        [
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>
    <div class='card-body'>
        <div class='row'>

            <div class='col-xl-6'>

                <div class='card h-100'>
                    <div class='card-header bg-light'>
                        <strong>
                            Персональная информация
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?= ButtonHelper::hidden(
                            $model, 'gender',
                            1,
                        )
                            ?>
                        <?= ButtonHelper::hidden(
                            $model, 'countryId',
                            1,
                        )
                        ?>
                        <?= ButtonHelper::hidden(
                            $model, 'userId',
                            $user->id,
                        )
                        ?>
                        
                        <?= ButtonHelper::hidden(
                            $model, 'site_id',
                            $user->site_id,
                        )
                        ?>
                        
                        <?= $form->field($model, 'firstName')
                                 ->textInput(['maxlength' => true])
                                 ->label('Имя')
                        ?>
                        
                        <?= $form->field($model, 'lastName')
                                 ->textInput(['maxlength' => true])
                                 ->label('Фамилия')
                        ?>
                        
                        <?= $form->field($model, 'name')
                                 ->textInput(['maxlength' => true])
                                 ->label(
                                     'Псевдоним (английскими буквами)',
                                 )
                        ?>
                        
                        <?php
                            try {
                                echo $form
                                    ->field($model, 'dateOfBirth')
                                    ->widget(DatePicker::classname(), [
                                        'language'   => 'ru', // Задайте язык, если требуется
                                        'dateFormat' => 'yyyy-MM-dd', // Формат даты, измените при необходимости
                                        'options'    => [
                                            'class' => 'form-control', // Убедитесь, что опции правильно переданы
                                        ],
                                    ])
                                    ->label('Условная дата рождения (необязательно)')
                                ;
                            }
                            catch (Exception|Throwable $e) {
                                PrintHelper::exception(
                                    $actionId, 'DatePicker дата рождения. ' . LAYOUT_ID, $e,
                                );
                            }
                        ?>
                    </div>

                    <div class="card-footer">
                                <?= SelectHelper::status($form, $model) ?>
                        <hr>
                        
                        <?= ButtonHelper::submit()
                        ?>

                    </div>

                </div>

            </div>

            <div class='col-xl-6'>
                <div class='card h-100'>
                    <div class='card-header bg-light'>
                        <strong>
                            Прочее
                        </strong>
                    </div>
                    <div class='card-body'>
                        
                        <?= $form->field($model, 'place')
                                 ->textInput(['maxlength' => true])
                        ?>
                        
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
                                 ->textarea()
                                 ->label('Хобби, интересы')
                        ?>


                    </div>
                </div>
            </div>

        </div>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
    TinyHelper::getText();
