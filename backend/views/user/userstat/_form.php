<?php
    
    use core\edit\entities\User\UserStatistic;
    use yii\bootstrap5\Html;
    use yii\widgets\ActiveForm;
    
    /** @var yii\web\View $this */
    /** @var UserStatistic $model */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="user-log-form">
    
    <?php
        $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'id')->textInput()
    ?>
    
    <?= $form->field($model, 'site_id')->textInput()
    ?>
    
    <?= $form->field($model, 'edit')->textInput()
    ?>
    
    <?= $form->field($model, 'text_type')->textInput()
    ?>
    
    <?= $form->field($model, 'parent_id')->textInput()
    ?>
    
    <?= $form->field($model, 'page_url')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'entry_time')->textInput()
    ?>
    
    <?= $form->field($model, 'exit_time')->textInput()
    ?>
    
    <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true])
    ?>
    
    <?= $form->field($model, 'user_id')->textInput()
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-sm btn-success'])
        ?>
    </div>
    
    <?php
        ActiveForm::end(); ?>

</div>
