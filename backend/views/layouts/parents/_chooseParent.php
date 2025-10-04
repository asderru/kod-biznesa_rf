<?php
    
    
    use core\helpers\types\TypeHelper;
    use core\read\readers\Admin\InformationReader;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\db\ActiveRecord;
    
    
    /* @var $this yii\web\View */
    /* @var $form ActiveForm */
    /* @var $model Model */
    /* @var $parent ActiveRecord */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const CHOOZE_PARENT_LAYOUT = '#layouts_parents_chooseParent';
    
    if ($parent) {
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
    
    echo $form
        ->field($model, 'site_id')
        ->dropDownList(
            InformationReader::getDropDownFilter(0),
            [
                'prompt'   => ' == ',
                'onchange' => 'updateTypeList(this.value);',
            ],
        )
        ->label('Выбрать сайт')
    ;
    
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
        ->label('Выбрать тему')
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
        ->label($label . '. Выбрать модель-источник')
    ;
