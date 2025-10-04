<?php
    
    use core\edit\search\User\UserDataSearch;
    use yii\bootstrap5\Html;
    use yii\widgets\ActiveForm;
    
    /** @var yii\web\View $this */
    /** @var UserDataSearch $model */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="user-data-search">
    
    <?php
        $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
    
    <?= $form->field($model, 'id')
    ?>
    
    <?= $form->field($model, 'ip_address')
    ?>
    
    <?= $form->field($model, 'user_id')
    ?>
    
    <?= $form->field($model, 'user_name')
    ?>
    
    <?= $form->field($model, 'country')
    ?>
    
    <?php
        // echo $form->field($model, 'operating_system')
    ?>
    
    <?php
        // echo $form->field($model, 'browser')
    ?>
    
    <?php
        // echo $form->field($model, 'browser_version')
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-sm btn-primary'])
        ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-sm btn-outline-secondary'])
        ?>
    </div>
    
    <?php
        ActiveForm::end(); ?>

</div>
