<?php
    
    use core\helpers\PrintHelper;
    use tangniyuqi\simplemde\SimpleMDE;
    use core\edit\forms\ModelEditForm;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model ModelEditForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    
    try {
        echo $form->field($model, 'text')->widget(
            SimpleMDE::className(),
            [
                'options' => [
                    'toolbar' => null,
                ],
            ],
        );
    }
    catch (Exception $e) {
        PrintHelper::exception(
            $actionId, 'Summernote::class' . LAYOUT_ID, $e,
        );
    }
