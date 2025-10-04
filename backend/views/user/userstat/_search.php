<?php
    
    use core\edit\search\User\UserStatSearch;
    use yii\bootstrap5\Html;
    use yii\widgets\ActiveForm;
    
    /** @var yii\web\View $this */
    /** @var UserStatSearch $model */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="user-log-search">
    
    <?php
        $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
    
    <?= $form->field($model, 'id')
    ?>
    
    <?= $form->field($model, 'site_id')
    ?>
    
    <?= $form->field($model, 'edit')
    ?>
    
    <?= $form->field($model, 'text_type')
    ?>
    
    <?= $form->field($model, 'parent_id')
    ?>
    
    <?php
        // echo $form->field($model, 'page_url')
    ?>
    
    <?php
        // echo $form->field($model, 'entry_time')
    ?>
    
    <?php
        // echo $form->field($model, 'exit_time')
    ?>
    
    <?php
        // echo $form->field($model, 'ip_address')
    ?>
    
    <?php
        // echo $form->field($model, 'user_id')
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
