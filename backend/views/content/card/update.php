<?php

    use backend\helpers\BreadCrumbHelper;
    use core\edit\entities\Content\ContentCard;
    use core\edit\forms\Library\CardForm;
    use core\helpers\types\TypeHelper;
    use core\helpers\types\TypeUrlHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;

    /**
     * @var  $this       yii\web\View
     * @var  $model      CardForm
     * @var  $parent     Model
     * @var  $card       ContentCard
     * @var  $cardFields array
     * @var  $actionId   string
     * @var  $label      string
     * @var  $prefix     string
     * @var  $textType   int
     */
    
    const LAYOUT_ID = '#content_card_update';
    
    $this->title = $card->name . '. Правка';

    $parentUrl = TypeUrlHelper::getView($card->text_type, $card->parent_id);
    $parentName = $parent->name;
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::LIBRARY_CARD_TYPE);
    $this->params['breadcrumbs'][] = ['label' => $card->name, 'url' => ['view', 'id' => $card->id]];
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
    );
    
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
    );
?>

<div class="card">

    <div class='card-header bg-light d-flex justify-content-between'>
        <h5>
            <?= Html::encode($card->name) ?>
        </h5>
        <div>
            <?= Html::a($parentName, $parentUrl, [
                'class' => 'btn btn-sm btn-outline-secondary',
            ]) ?>
        </div>
    </div>
    <div class='card-body'>
        <div class="row mb-3">
            <div class="col-xl-6">
                <div class="card mb-2">
                    <div class='card-header bg-body-secondary d-flex justify-content-between'>
                        <div class="px-1">
                            <?= TypeHelper::getName($card->text_type); ?> <strong> <?= Html::encode
                                (
                                    $parentName,
                                ) ?></strong>
                        </div>
                        <button type='button' class='btn btn-sm btn-outline-dark' data-bs-toggle='modal'
                                data-bs-target='#contentModal'>
                            Смотреть
                        </button>
                    </div>
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
    </div>
</div>
    
    <?php
        ActiveForm::end();
    ?>

<script>
    document.getElementById('add-more-fields').addEventListener('click', function () {
    // Находим контейнер для всех полей (включая уже существующие)
    var allFieldsContainer = document.querySelectorAll('.card.bg-body-tertiary.mb-3, .added-field');
    var index = allFieldsContainer.length; // Индекс будет равен количеству всех полей

    var newField = document.createElement('div');
    newField.className = 'added-field mb-3';
    newField.innerHTML = `
        <div class="mb-3 field-cardform-addedfield-${index}-label">
            <label class="form-label" for="cardform-addedfield-${index}-label">Название</label>
            <input type="text" id="cardform-addedfield-${index}-label" class="form-control" name="CardForm[addedField][${index}][label]">
            <div class="help-block"></div>
        </div>
        <div class="mb-3 field-cardform-addedfield-${index}-description">
            <label class="form-label" for="cardform-addedfield-${index}-description">Содержимое</label>
            <textarea id="cardform-addedfield-${index}-subject" class="form-control" name="CardForm[addedField][${index}][subject]" rows="6"></textarea>
            <div class="help-block"></div>
        </div>
        <!-- Кнопка удаления поля -->
        <button type="button" class="btn btn-outline-danger btn-sm remove-field">
            <i class="fas fa-trash"></i> Удалить
        </button>
    `;

    // Добавляем новое поле в контейнер для новых полей
    document.getElementById('new-fields').appendChild(newField);
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
