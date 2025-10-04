<?php

    use core\edit\editors\Content\CardFieldEditor;
    use core\edit\forms\Content\CardForm;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;

    /* @var $model CardForm */
    /* @var $form ActiveForm */
    /* @var $parent [] int */
    /* @var $textType int */
    const CONTENT_CARD_FORM_LAYOUT = '#content_card__form_card';
    echo PrintHelper::layout(CONTENT_CARD_FORM_LAYOUT);
    
    $cardFields = CardFieldEditor::getArray($textType);
    
    if (!$cardFields) {
        $existedFields     = CardFieldEditor::getExistedArray();
        $existedCardFields = current($existedFields);
        $parentTextType    = $existedCardFields[0]['text_type'] ?? null;
    }

?>
<?php
if (!empty($cardFields)):
    ?>

    <div class='row mb-3'>
        <div class='col-xl-6'>
            <div class='card mb-2'>
                <div class="card-body">
                    <?= $form->field($model, 'name')
                             ->textInput(['maxlength' => true])
                    ?>
                    
                    <?= $form->field($model, 'description')->textarea(
                        [
                            'rows' => 8,
                        ]
                    ) ?>

                    <!--###### Массив имен полей модели ########################################-->
                    <?php
                        // Массив имен полей модели
                        $fieldNames = ['firstField', 'secondField', 'thirdField', 'fourthField'];
                        
                        // Проходим по первым 4 элементам массива $cardFields и отображаем соответствующие поля ввода
                        for ($index = 0; $index < min(count($fieldNames), count($cardFields)); $index++):
                        
                            ?>
                            <div class="p-2"><strong><?= $cardFields[$index]['name'] ?></strong>
                                <?= $form->field($model, $fieldNames[$index])
                                         ->textArea(
                                             [
                                                 'rows' => 8,
                                             ]
                                         )
                                         ->label(false)
                                ?>
                            </div>
                        <?php endfor;
                        
                        // Если элементов меньше чем в $fieldNames, добавляем скрытые поля для остальных
                        for ($i = count($cardFields); $i < count($fieldNames); $i++): ?>
                            <?= $form->field($model, $fieldNames[$i])->hiddenInput(['value' => null])->label(false) ?>
                        <?php endfor;
                    ?>
                </div>

                <div class='card-footer text-end'>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-success']) ?>
                </div>
            </div>
        </div>
        <div class='col-xl-6'>
            <div class='card'>
                <div class='card-header bg-light'>
                    <strong>Дополнительные поля</strong>
                </div>
                <div class='card-body'>
                    <?php
                    // Определим переменную $i для нумерации полей
                    $i = 0;
                    
                    // Если есть дополнительные поля в массиве $cardFields (начиная с 5-го элемента)
                    if (count($cardFields) > count($fieldNames)) {
                        // Получаем дополнительные поля (начиная с индекса 4)
                        $additionalFields = array_slice($cardFields, count($fieldNames));
                        
                        foreach ($additionalFields as $index => $field) {
                            $i++;
                            $key = isset($model->addedField[$index]) ? $index : $i - 1;
                            
                            ?>
                            <div class="card bg-body-tertiary mb-3">
                                <div class="card-header bg-body-secondary">Поле # <?= $i ?></div>
                                <div class="card-body">
                                    <?php
                                        // Предустановить значение метки на основе имени поля из $cardFields
                                        $labelValue = $field['name'] ?? '';
                                        
                                        echo $form->field($model, "addedField[$key][label]")
                                                  ->textInput(['value' => $labelValue])
                                                  ->label('Название');
                                                  
                                        echo $form->field($model, "addedField[$key][subject]")
                                                  ->textArea(['rows' => 8])
                                                  ->label('Содержимое');
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    
                    // Если у модели уже есть дополнительные поля, которых нет в $cardFields
                    if (is_array($model->addedField)) {
                        foreach ($model->addedField as $key => $field) {
                            // Пропускаем поля, которые уже были обработаны выше
                            if ($key < count($additionalFields ?? [])) {
                                continue;
                            }
                            
                            $i++;
                            ?>
                            <div class="card bg-body-tertiary mb-3">
                                <div class="card-header bg-body-secondary">Поле # <?= $i ?></div>
                                <div class="card-body">
                                    <?php
                                        echo $form->field($model, "addedField[$key][label]")->label('Название');
                                        echo $form->field($model, "addedField[$key][subject]")
                                                 ->textArea(['rows' => 8])
                                                 ->label('Содержимое');
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <!-- Контейнер для новых полей -->
                    <div id="new-fields-container"></div>

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
    // JavaScript для добавления новых полей
    $js = <<<JS
    let fieldCounter = $i;
    
    $('#add-more-fields').on('click', function() {
        fieldCounter++;
        
        const newFieldHtml = `
            <div class="card bg-body-tertiary mb-3">
                <div class="card-header bg-body-secondary">Поле # \${fieldCounter}</div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="control-label">Название</label>
                        <input type="text" id="model-addedfield-\${fieldCounter}-label" class="form-control" name="Model[addedField][\${fieldCounter}][label]">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Содержимое</label>
                        <textarea id="model-addedfield-\${fieldCounter}-subject" class="form-control" name="Model[addedField][\${fieldCounter}][subject]" rows="6"></textarea>
                    </div>
                </div>
            </div>
        `;
        
        $('#new-fields-container').append(newFieldHtml);
    });
    JS;
    
    $this->registerJs($js);
    ?>
    <?php
    else:
        ?>

        <div class='row mb-3'>
            <div class='col-xl-6'>
                <div class='card mb-2'>
                    <div class="card-header bg-warning-subtle">
                        Карточка контента для <?= TypeHelper::getName($textType, 2) ?>
                    </div>
                    <div class="card-body">
                        <p>Карточка контента не является обязательным аттрибутом текста, однако вам нужно обязательно
                            создать форму для карточки для <?= TypeHelper::getName($textType, 2, true) ?>!
                        </p>
                        <p class="p-4">
                            <?= Html::a(
                                'Создать форму',
                                [
                                    'content/card-field/create',
                                    'textType' => $textType,
                                ],
                                [
                                    'class'  => 'btn btn-primary',
                                    'target' => '_blank',
                                ],
                            ) ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <?php
                if (!empty($existedCardFields)): ?>
                    <div class='col-xl-6'>
                        <div class='card mb-2'>
                            <div class='card-header bg-info-subtle'>
                                Копировать карточку контента для <?= TypeHelper::getName($parent->textType, 2) ?>
                            </div>
                            <div class='card-body'>
                                <div class='list-group'>
                                    <?php
                                        foreach ($existedCardFields as $index => $item): ?>
                                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge bg-secondary me-2"><?= $index + 1 ?></span>
                                                    <strong>ID <?= $item['field_id'] ?>:</strong> <?= $item['name'] ?>
                                                </div>
                                                <div>
                                                    <?php
                                                        $statusClass = match ($item['status']) {
                                                            1       => 'bg-danger',
                                                            2       => 'bg-warning',
                                                            3       => 'bg-success',
                                                            default => 'bg-secondary'
                                                        };
                                                    ?>
                                                    <span class="badge <?= $statusClass ?>">Статус: <?= $item['status'] ?></span>
                                                    <small class="text-muted ms-2">Сортировка: <?= $item['sort'] ?></small>
                                                </div>
                                            </div>
                                        <?php
                                        endforeach; ?>
                                </div>
                                <div class='card-footer text-muted'>
                                    Всего элементов: <?= count($existedCardFields) ?>
                                </div>
                                <div class='card-footer'>
                                    <?= Html::a(
                                        'Скопировать поля',
                                        [
                                            'content/card-field/copy',
                                            'textType'       => $textType,
                                            'parentTextType' => $parent->textType,
                                        ],
                                        [
                                            'class'  => 'btn btn-outline-primary',
                                            'target' => '_blank',
                                        ],
                                    ) ?>
                                </div>

                            </div>

                        </div>
                    </div>
                
                <?php
                endif ?>
        </div>
    <?php
    endif ?>
