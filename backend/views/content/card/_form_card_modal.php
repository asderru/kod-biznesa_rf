<?php
    
    use core\edit\editors\Content\CardFieldEditor;
    use core\edit\forms\Content\CardForm;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $model CardForm */
    /* @var $bookId int */
    
    $form = ActiveForm::begin(
        [
            'id'                   => 'card-edit-form',
            'enableAjaxValidation' => false,
        ],
    );
    
    $cardFields = CardFieldEditor::getArray($model->text_type);
?>
<div class='form-group'>
    <div class='form-group mt-3'>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'id' => 'save-card-btn']) ?>
        <?= Html::button('Отмена', ['class' => 'btn btn-secondary', 'data-bs-dismiss' => 'modals']) ?>
    </div>
</div>
<div class='row mb-3'>
    <div class='col-xl-6'>
        <div class='card mb-2'>
            <div class="card-body">
                <?= $form->field($model, 'name')
                         ->textInput(['maxlength' => true],
                         )
                ;
                ?>
                
                <?= $form->field($model, 'description')->textarea(
                    [
                        'rows' => 8,
                    ],
                );
                ?>

                <!--###### Массив имен полей модели ########################################-->
                <?php
                    // Массив имен полей модели
                    $fieldNames = ['firstField', 'secondField', 'thirdField', 'fourthField'];
                    
                    // Получаем количество элементов в массиве $cardFields
                    $countFields = count($cardFields);
                    
                    // Проходим по массиву $cardFields и отображаем соответствующие поля ввода
                    foreach ($cardFields as $index => $field):
                        // Проверяем, что индекс не выходит за пределы массива $fieldNames
                        if ($index < count($fieldNames)):
                            ?>
                            <div class="p-2"><strong><?= $field['name'] ?></strong>
                                <?= $form->field($model, $fieldNames[$index])
                                         ->textArea(
                                             [
                                                 'rows' => 8,
                                             ],
                                         )
                                         ->label(false)
                                ?>
                            </div>
                        <?php
                        endif;
                    endforeach;
                    
                    // Если элементов меньше чем в $fieldNames, добавляем скрытые поля для остальных
                    for ($i = $countFields; $i < count($fieldNames); $i++):
                        ?>
                        <?= $form->field($model, $fieldNames[$i])->hiddenInput(['value' => null])->label(false) ?>
                    <?php
                    endfor;
                ?>

            </div>

            <div class='card-footer text-end'>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-primary']) ?>
            </div>
        </div>
    </div>
    <div class='col-xl-6'>
        <div class='card'>
            <div class='card-header bg-light'>
                <strong>Дополнительные поля</strong>
            </div>
            <div class='card-body' id='new-fields'>
                <?php
                    $i = 0;
                    if (!empty($model->addedField) && is_array($model->addedField)) {
                        foreach ($model->addedField as $key => $field) {
                            $i++;
                            ?>
                            <div class="card bg-body-tertiary mb-3">
                                <div class="card-header bg-body-secondary"> Поле # <?= $i ?></div>
                                <div class="card-body">
                                    <?php
                                        echo $form->field($model, "addedField[$key][label]")->label('Название');
                                        echo $form->field($model, "addedField[$key][subject]")->textArea(
                                            [
                                                'rows' => 8,
                                            ],
                                        )->label('Содержимое');
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                ?>

                <!-- Контейнер для новых полей -->
                <div id="new-fields"></div>

                <div class="card-footer d-flex justify-content-between">
                    <!-- Кнопка для добавления новых полей через JavaScript -->
                    <button type='button' class='btn btn-outline-success btn-sm' id='add-more-fields'>
                        <i class='fas fa-plus'></i> Добавить еще заметку
                    </button>
                    
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    ActiveForm::end();
?>
