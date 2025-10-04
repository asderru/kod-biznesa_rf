<?php
    
    use core\edit\search\Tech\BlackListSearch;
    use yii\bootstrap5\Html;
    use yii\widgets\ActiveForm;
    
    /** @var yii\web\View $this */
    /** @var BlackListSearch $model */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="black-list-search">
    
    <?php
        $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
    
    <?= $form->field($model, 'id')
    ?>
    
    <?= $form->field($model, 'ip_address')
    ?>
    
    <?= $form->field($model, 'name')
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary'])
        ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary'])
        ?>
    </div>
    
    <?php
        ActiveForm::end(); ?>

</div>
