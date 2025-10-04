<?php
    
    use core\edit\entities\Tech\BlackList;
    use yii\bootstrap5\Html;
    use yii\widgets\ActiveForm;
    
    /** @var yii\web\View $this */
    /** @var BlackList $model */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="black-list-form">
    
    <?php
        $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'name')->textInput(
        [
            'maxlength' => true,
            'required'  => true,
        ],
    )
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success'])
        ?>
    </div>
    
    <?php
        ActiveForm::end(); ?>

</div>
