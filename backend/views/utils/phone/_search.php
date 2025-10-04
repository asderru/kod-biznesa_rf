<?php
    
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    
    /** @var yii\web\View $this */
    /** @var \core\edit\search\Utils\PhoneSearch $model */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="phones-search">
    
    <?php
        $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
    
    <?= $form->field($model, 'id') ?>
    
    <?= $form->field($model, 'site_id') ?>
    
    <?= $form->field($model, 'text_type') ?>
    
    <?= $form->field($model, 'parent_id') ?>
    
    <?= $form->field($model, 'name') ?>
    
    <?php
        // echo $form->field($model, 'country_code') ?>
    
    <?php
        // echo $form->field($model, 'phone') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>
    
    <?php
        ActiveForm::end(); ?>

</div>
