<?php
    
    use core\edit\search\Tech\EntrySearch;
    use yii\bootstrap5\Html;
    use yii\widgets\ActiveForm;
    
    /** @var yii\web\View $this */
    /** @var EntrySearch $model */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="entry-search">
    
    <?php
        $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
    
    <?= $form->field($model, 'id')
    ?>
    
    <?= $form->field($model, 'host')
    ?>
    
    <?= $form->field($model, 'client_ip')
    ?>
    
    <?= $form->field($model, 'timestamp')
    ?>
    
    <?= $form->field($model, 'request_method')
    ?>
    
    <?php
        // echo $form->field($model, 'request_url')
    ?>
    
    <?php
        // echo $form->field($model, 'http_version')
    ?>
    
    <?php
        // echo $form->field($model, 'status_code')
    ?>
    
    <?php
        // echo $form->field($model, 'response_size')
    ?>
    
    <?php
        // echo $form->field($model, 'referer')
    ?>
    
    <?php
        // echo $form->field($model, 'user_agent')
    ?>
    
    <?php
        // echo $form->field($model, 'created_at')
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
