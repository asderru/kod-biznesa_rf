<?php
    
    use brussens\yii2\extensions\trumbowyg\TrumbowygWidget;
    use core\helpers\PrintHelper;
    use core\edit\forms\ModelEditForm;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model ModelEditForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    
    try {
                echo
                $form->field($model, 'text')->widget(TrumbowygWidget::className());
            }
            catch (Exception $e) {
                PrintHelper::exception(
                    $actionId, 'TrumbowygWidget' . LAYOUT_ID, $e,
                );
            }
