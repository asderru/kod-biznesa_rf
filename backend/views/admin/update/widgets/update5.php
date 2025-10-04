<?php
    
    use core\helpers\PrintHelper;
    use vova07\imperavi\Widget;
    use core\edit\forms\ModelEditForm;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model ModelEditForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    
    
    try {
        echo
        $form->field($model, 'text')->widget(Widget::className(), [
            'settings' => [
                'lang'      => 'ru',
                'minHeight' => 200,
                'plugins'   => [
                    'clips',
                    'fullscreen',
                ],
                'clips'     => [
                    ['Lorem ipsum...', 'Lorem...'],
                    ['red', '<span class="label-red">red</span>'],
                    ['green', '<span class="label-green">green</span>'],
                    ['blue', '<span class="label-blue">blue</span>'],
                ],
            ],
        ]);
    }
    catch (Exception $e) {
        PrintHelper::exception(
            $actionId, 'Widget ' . LAYOUT_ID, $e,
        );
    }
