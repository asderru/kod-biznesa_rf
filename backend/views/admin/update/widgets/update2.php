<?php
    
    use bizley\quill\Quill;
    use core\edit\forms\ModelEditForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model ModelEditForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    
    try {
        echo $form->field($model, 'text')->widget(Quill::class, [
            'options'        => ['id' => 'text-edit-area'],
            'theme'          => Quill::THEME_SNOW,
            'toolbarOptions' => [
                ['header' => [1, 2, 3, false]],
                'bold', 'italic', 'underline', 'strike',
                ['list' => 'ordered'], ['list' => 'bullet'],
                ['indent' => '-1'], ['indent' => '+1'],
                ['align' => []],
                'link', 'image', 'video',
                'clean',
            ],
        ])->label('текст');
    }
    catch (Exception $e) {
        PrintHelper::exception(
            $actionId, 'Quill::class' . LAYOUT_ID, $e,
        );
    }
