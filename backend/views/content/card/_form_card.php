<?php

    use core\edit\editors\Content\CardFieldEditor;
    use core\edit\forms\Content\CardForm;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;

    /* @var $model CardForm */
    /* @var $form ActiveForm */
    /* @var $textType int */
    /* @var $parentId int */
    
    const CONTENT_CARD_FORM_LAYOUT = '#content_card__form_card';
    echo PrintHelper::layout(CONTENT_CARD_FORM_LAYOUT);
    
    $cardFields        = CardFieldEditor::getArray($textType);
    $existedFields     = CardFieldEditor::getExistedArray();
    $existedCardFields = current($existedFields);
    $parentTextType    = $existedCardFields[0]['text_type'] ?? null;
    
    //PrintHelper::print($existedCardFields);

?>
<?php
    if (!empty($model->name)): ?>
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
    elseif (!empty($cardFields) && $parentId !== null): ?>
        <div class='row mb-3'>
            <div class='col-xl-6'>
                <div class='card mb-2'>
                    <div class='card-header bg-warning-subtle'>
                        Карточка контента отсутствует.
                    </div>
                    <div class="card-body">
                        <?= Html::a(
                            'Создать карточку контента',
                            [
                                'content/card/create',
                                'textType' => $textType,
                                'parentId' => $parentId,
                            ],
                            [
                                'class'  => 'btn btn-primary',
                                'target' => '_blank',
                            ],
                        ) ?>
                    </div>
                    <?php
                        echo $form->field($model, 'site_id')->hiddenInput(['value' => Parametr::siteId()])->label
                        (
                            false,
                        );
                        echo $form->field($model, 'parentId')->hiddenInput(['value' => Constant::STATUS_ROOT])
                                  ->label(false)
                        ;
                        echo $form->field($model, 'name')->hiddenInput(['value' => ''])
                                  ->label(false)
                        ;
                        echo $form->field($model, 'description')->hiddenInput(['value' => ''])
                                  ->label(false)
                        ;
                        echo $form->field($model, 'firstField')->hiddenInput(['value' => ''])
                                  ->label(false)
                        ;
                        echo $form->field($model, 'secondField')->hiddenInput(['value' => ''])
                                  ->label(false)
                        ;
                        echo $form->field($model, 'thirdField')->hiddenInput(['value' => ''])
                                  ->label(false)
                        ;
                        echo $form->field($model, 'thirdField')->hiddenInput(['value' => ''])
                                  ->label(false)
                        ;
                        echo $form->field($model, 'addedFields')->hiddenInput(['value' => ''])
                                  ->label(false)
                        ;
                    ?>

                </div>
            </div>
        </div>
    <?php
    else: ?>
        <div class='row mb-3'>
            <div class='col-xl-6'>
                <div class='card mb-2'>
                    <div class="card-header bg-warning-subtle">
                        Карточка контента для <?= TypeHelper::getName($textType, 2) ?> отсутствует.
                    </div>
                    <div class="card-body">
                        <p>Карточка контента не является обязательным аттрибутом текста. Если
                            собираетесь заниматься
                            продвижением этого текста и
                            других <?= TypeHelper::getName($textType, 2, true) ?>,
                            можете создать форму для карточки.
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
                                Копировать карточку контента для <?= TypeHelper::getName($parentTextType, 2) ?>
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
                                            'parentTextType' => $parentTextType,
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
