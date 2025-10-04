<?php
    
    use core\helpers\PrintHelper;
    use mihaildev\ckeditor\CKEditor;
    use core\edit\forms\ModelEditForm;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model ModelEditForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    
    try {
                echo
                $form->field($model, 'text')->widget(CKEditor::className(), [
                    'editorOptions' => [
                        'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                        'inline' => false, //по умолчанию false
                    ],
                ]);
            }
            catch (Exception $e) {
                PrintHelper::exception(
                    $actionId, 'CKEditor' . LAYOUT_ID, $e,
                );
            }
