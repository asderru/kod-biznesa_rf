<?php
    
    use core\edit\entities\User\UserData;
    use yii\bootstrap5\Html;
    use yii\widgets\ActiveForm;
    
    /** @var yii\web\View $this */
    /** @var UserData $model */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="user-data-form">
    
    <?php
        $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'id')->textInput()
    ?>
    
    <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'user_id')->textInput()
    ?>
    
    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'country')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'operating_system')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'browser')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'browser_version')->textInput()
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-sm btn-success'])
        ?>
    </div>
    
    <?php
        ActiveForm::end(); ?>

</div>
