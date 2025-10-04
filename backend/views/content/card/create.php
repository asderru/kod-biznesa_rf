<?php
    
    use core\edit\entities\Content\ContentCard;
    use core\edit\forms\Content\CardForm;
    use core\helpers\types\TypeHelper;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /**
     * @var View        $this
     * @var CardForm    $model
     * @var ContentCard $card
     * @var Model       $parent
     * @var int         $parentType
     * @var array       $cardFields
     * @var ActiveForm  $form
     * @var string      $actionId
     * @var string      $label
     * @var string      $prefix
     * @var int         $textType
     */
    
    // Константы и основные переменные
    const LAYOUT_ID = '#content_card_create';
    
    // Формирование заголовка
    $this->title = $label;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Все карточки',
        'url'   => ['content/card/index'],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $this->title,
    ];
    
    // Рендеринг компонентов
    echo $this->render(
        '/layouts/tops/_infoHeader', [
        'label'    => $label,
        'textType' => $textType,
        'prefix'   => $prefix,
        'actionId' => $actionId,
        'layoutId' => LAYOUT_ID,
    ],
    );
?>

<div class="card">
    <div class='card-header bg-light'>
        <h5> Карточка контента. <?= TypeHelper::getName($parentType, 1, false, true ) ?>
                        &laquo;<strong><?= $parent->name ?></strong>&raquo;.
        </h5>
    </div>

    <div class="card-body">
        
        <?php
            $form = ActiveForm::begin(
                [
                    'id'          => 'cardfieldform',
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

        <div class='row mb-3'>

            <div class='col-xl-6'>

                <div class='card'>
                    <div class='card-body'>
                        <div class='p-2'><strong>Название карточки</strong>
                            <?= $form->field($model, 'name')
                                     ->textInput()
                                     ->label(false)
                            ?>
                        </div>
                        <div class="p-2"><strong>Тема контента, желательно ~150 знаков</strong>
                            <?= $form->field($model, 'description')
                                     ->textarea(
                                                     [
                                                         'rows' => 8,
                                                     ],
                                                 )
                                     ->label(false)
                            ?>
                        </div>

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
                        <!--###### Массив имен полей модели ########################################-->

                    </div>

                </div>

            </div>

            <!--###### Конец колонки с массивом имен полей модели ##########################-->

            <div class='col-xl-6'>

                <div class='card'>

                    <div class='card-header bg-light'>
    <strong>Дополнительные заметки</strong> Дополнительные поля записываются в таблице модели в JSON-столбец added_field
</div>
<div class='card-body' id='new-fields'>
    <!--###### Массив дополнительных имен ####-->
    <?php
    // Начинаем с 5-го элемента (индекс 4) и до конца массива $cardFields
    for ($i = 4; $i < count($cardFields); $i++):
        $field = $cardFields[$i]; // Получаем текущее поле
        ?>
        <div class='added-field mb-3'>
            <!-- Поле для названия (label) -->
            <?= $form->field($model, "addedField[$i][label]")
                     ->textInput(['value' => $field['name']]) // Указываем название поля из $cardFields
                     ->label(false)
            ?>
            <!-- Поле для содержимого (subject) -->
            <?= $form->field($model, "addedField[$i][subject]")
                     ->textArea(['rows' => 8])
                     ->label(false)
            ?>
            <!-- Кнопка удаления поля -->
            <button type="button" class="btn btn-outline-danger btn-sm remove-field">
                <i class="fas fa-trash"></i> Удалить
            </button>
        </div>
    <?php endfor; ?>
</div>
                    
                    <div class="card-footer d-flex justify-content-between">
                        <!-- Кнопка для добавления новых полей через JavaScript -->
                        <button type='button' class='btn btn-outline-success btn-sm'
                                id='add-more-fields'>
                            <i class='fas fa-plus'></i> Добавить еще заметку
                        </button>
                    
                    
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-primary']) ?>
                    
                    </div>
                </div>

            </div>

            <!--###### Конец колонки с массивом дополнительных имен ##########################-->

        </div>
        
        <?php
            ActiveForm::end();
        ?>
    </div>

</div>
<!-- Шаблон для JavaScript, чтобы добавлять новые поля -->
<template id='new-field-template'>
    <div class='added-field mb-3'>
        <input type='text' class='form-control mb-3'
               name='Model[addedField][{index}][label]'>

        <textarea type='text' class='form-control'
                  name='Model[addedField][{index}][subject]'
                  rows='6'></textarea>
        <button type='button'
                class='btn btn-outline-danger btn-sm remove-field mt-1'>
            <i class='fas fa-times'></i> Удалить
        </button>
    </div>
</template>

<script>
    // Функция для добавления новых полей
    document.getElementById('add-more-fields').addEventListener('click', function () {
        var newFieldsContainer = document.getElementById('new-fields');
        var index = newFieldsContainer.querySelectorAll('.added-field').length;

        var newField = document.createElement('div');
        newField.className = 'added-field mb-3';
        newField.innerHTML = `
            <?= $form->field($model, 'addedField[${index}][label]')
                     ->textInput()
                     ->label(false)
                     ->render()
        ?>
            
            <?= $form->field($model, 'addedField[${index}][subject]')
                     ->textArea([
                         'rows' => 8,
                     ])
                     ->label(false)
                     ->render()
        ?>
            <!-- Кнопка удаления поля -->
            <button type="button" class="btn btn-outline-danger btn-sm remove-field">
                <i class="fas fa-trash"></i> Удалить
            </button>
        `;

        newFieldsContainer.appendChild(newField);
    });

    // Функция для удаления полей
    document.addEventListener('click', function (event) {
        if (event.target && event.target.classList.contains('remove-field')) {
            var fieldToRemove = event.target.closest('.added-field');
            if (fieldToRemove) {
                fieldToRemove.remove();
            }
        }
    });
</script>
