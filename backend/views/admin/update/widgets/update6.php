<?php
    
    use core\helpers\PrintHelper;
    use yii2jodit\JoditWidget;
    use core\edit\forms\ModelEditForm;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model ModelEditForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    
            try {
                echo
                JoditWidget::widget([
                    'model'     => $model,
                    'attribute' => 'text',
                ]);
            }
            catch (Throwable $e) {
                PrintHelper::exception(
                    $actionId, 'JoditWidget' . LAYOUT_ID, $e,
                );
            }
