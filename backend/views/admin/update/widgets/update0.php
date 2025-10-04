<?php
    
    use backend\tools\TinyHelper;
    use core\edit\forms\ModelEditForm;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model ModelEditForm */
    /* @var $form ActiveForm */
    
    echo $form->field(
        $model, 'text',
        [
            'inputOptions' => [
                'id' => 'text-edit-area',
            ],
        ],
    )
              ->textarea()
              ->label(false)
    ;
    
    TinyHelper::getUnstickyText();
