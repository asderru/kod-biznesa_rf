<?php
    
    use backend\assets\SummernoteAsset;
    use core\edit\forms\ModelEditForm;
    use yii\bootstrap5\ActiveForm;
    use yii\web\View;
    
    SummernoteAsset::register($this);
    
    /* @var $this View */
    /* @var $model ModelEditForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */

?>

<?= $form->field(
    $model, 'text',
    [
        'inputOptions' => [
            'id'   => 'summernote',
            'name' => 'editordata',
            ],
    ],
)
         ->textarea()
         ->label(false)
;
?>


<script>
    $('#summernote').summernote({
        placeholder: 'Hello Bootstrap 5',
        tabsize: 2,
        height: 400
    });
</script>
