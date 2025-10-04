<?php
    
    use core\edit\entities\Tech\Entry;
    use yii\bootstrap5\Html;
    use yii\widgets\ActiveForm;
    
    /** @var yii\web\View $this */
    /** @var Entry $model */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="entry-form">
    
    <?php
        $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'host')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'client_ip')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'timestamp')->textInput()
    ?>
    
    <?= $form->field($model, 'request_method')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'request_url')->textarea(['rows' => 6])
    ?>
    
    <?= $form->field($model, 'http_version')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'status_code')->textInput()
    ?>
    
    <?= $form->field($model, 'response_size')->textInput()
    ?>
    
    <?= $form->field($model, 'referer')->textarea(['rows' => 6])
    ?>
    
    <?= $form->field($model, 'user_agent')->textarea(['rows' => 6])
    ?>
    
    <?= $form->field($model, 'created_at')->textInput()
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success'])
        ?>
    </div>
    
    <?php
        ActiveForm::end(); ?>

</div>
