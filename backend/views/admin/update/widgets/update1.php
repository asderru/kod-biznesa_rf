<?php
    
    use core\helpers\PrintHelper;
    use yii2mod\markdown\MarkdownEditor;
    use core\edit\forms\ModelEditForm;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model ModelEditForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    
    try {
        echo $form->field($model, 'text')->widget(MarkdownEditor::class, [
            'name'          => 'text-edit',
            'editorOptions' => [
                'showIcons' => ['code', 'table'],
            ],
        ]);
    }
    catch (Exception $e) {
        PrintHelper::exception(
            $actionId, 'MarkdownEditor::class' . LAYOUT_ID, $e,
        );
    }
