<?php

    use backend\helpers\SelectHelper;
    use core\edit\editors\Admin\InformationEditor;
    use core\helpers\ButtonHelper;
    use core\helpers\IconHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\db\ActiveRecord;

    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Seo\AnonsForm */
    /* @var $parent ActiveRecord */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_review_create';
    
    $this->title = ($parent)
        ? 'Добавить обзор к теме "' . $parent->name . '". ' . TypeHelper::getName($parent::TEXT_TYPE) . '.'
        : 'Создать обзор';
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Новый обзор';
    
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
    );?>
  <div class='card'>
    <?= $this->render(
        '/layouts/tops/_createHeader',
        [
            'title'    => $this->title,
            'textType' => $textType,
        ],
    );
        if ($model->hasErrors()): ?>
            <div class="container alert alert-danger p-4">
                <?= Html::errorSummary($model) ?>
            </div>
        <?php
        endif; ?>
    <div class='card-body'>

        <div class='row'>
            <?php
                if (!$parent): ?>
                    <div class='col-xl-3 col-lg-6'>
                        <div class='card h-100'>
                            <div class='card-header bg-light '>
                                Обзор для <?= TypeHelper::getName($parent->textType, 2) ?>
                                <strong><?= $parent->name ?></strong>.
                                <button type='button'
                                        class='btn btn-sm btn-outline-dark'
                                        data-bs-toggle='modal'
                                        data-bs-target='#contentModal'>
                                    <?= IconHelper::biEye('Смотреть в модальном окне')?>
                                </button>
                            </div>
                            <div class='card-body'>
                                <?php
                                    try {
                                        echo $form
                                            ->field($model, 'site_id')
                                            ->dropDownList(
                                                InformationEditor::getDropDownFilter(0),
                                                [
                                                    'prompt'   => ' == ',
                                                    'onchange' => 'updateTypeList(this.value);',
                                                ],
                                            )
                                            ->label('Выбрать сайт')
                                        ;
                                    }
                                    catch (Exception $e) {
                                        PrintHelper::exception(
                                            $actionId, 'InformationEditor_getDropDownFilter ' . LAYOUT_ID, $e,
                                        );
                                    }
                                    echo $form
                                        ->field($model, 'textType')
                                        ->dropDownList(
                                            TypeHelper::getTypesMap(),
                                            [
                                                'prompt'   => ' == ',
                                                'onchange' => '
                                                    var siteId = $("#' . Html::getInputId($model, 'site_id') . '").val();
                                                    $.post("lists?siteId=" + siteId + "&textType=" + $(this).val(), function(data) {
                                                        $("select#selectedModel").html(data);
                                                    });
                                                ',
                                            ],
                                        )
                                        ->label('Выбрать тему для обзора')
                                    ;
                                    echo $form
                                        ->field($model, 'parentId')
                                        ->dropDownList(
                                            [],
                                            [
                                                'prompt' => ' -- ',
                                                'id'     => 'selectedModel',
                                            ],
                                        )
                                        ->label($label . '. Выбрать модель')
                                    ;
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                else: {
                    
                    echo $form->field($model, 'site_id')
                              ->hiddenInput(['value' => $parent->site_id])->label(false)
                    ;
                    
                    echo $form->field($model, 'textType')
                              ->hiddenInput(['value' => $parent::TEXT_TYPE])->label(false)
                    ;
                    
                    echo $form->field($model, 'parentId')
                              ->hiddenInput(['value' => $parent->id])->label(false)
                    ;
                }
                
                endif; ?>

            <div class='col-xl-3 col-lg-6'>

                <div class='card '>

                    <div class='card-header bg-light'>
                        <strong>
                            Общая информация
                        </strong>
                    </div>

                    <div class='card-body'>
                        
                        <?php
                            try {
                                echo
                                SelectHelper::getPersons($form, $model);
                            }
                            catch (Exception $e) {
                                PrintHelper::exception(
                                    $actionId, 'Выбор профиля ' . LAYOUT_ID, $e,
                                );
                            } ?>
                        
                        <?= $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        )
                                 ->label('Заголовок обзора (обязательно)')
                        ?>
                        
                        <?= $form->field($model, 'vote')->textInput([
                            'maxlength' => true,
                            'type'      => 'number',
                            'value'     => 1,
                            'min'       => 1,
                            'max'       => 5,
                            'step'      => 1,
                        ])
                                 ->label('Оценка от 1 до 5 (обязательно)')
                        ?>

                    </div>
                </div>

            </div>
            <div class='col-xl-6 col-lg-12'>

                <div class='card mb-3'>

                    <div class='card-header bg-light'>
                        <strong>
                            Обзор
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?= $form->field($model, 'text')->textarea(
                            [
                                'rows' =>
                                    10,
                            ],
                        )
                                 ->label(false)
                        ?>
                    </div>
                    <div class='card-footer'>
                        <?= ButtonHelper::submit()
                        ?>
                    </div>

                </div>


            </div>

        </div>

    </div>
</div>

<?php
    
    ActiveForm::end();

?>

<div class='modal' id='contentModal' tabindex='-1' aria-labelledby='contentModal' aria-hidden='true'>
    <div class='modal-dialog modal-xl modal-dialog-centered'>
        <div class='modal-content'>
            <?= $this->render(
                '/layouts/modals/_modalContent',
                [
                    'model' => $parent,
                ],
            )
            ?>
        </div>
    </div>
</div>
