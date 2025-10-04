<?php
    
    use core\edit\entities\Admin\Country;
    use yii\base\Model;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\MaskedInput;
    
    /** @var yii\web\View $this */
    /** @var core\edit\entities\Utils\Phone $model */
    /** @var Model $parent */
    /** @var yii\widgets\ActiveForm $form */
?>

<div class="phones-form">
    
    <?php
        $form = ActiveForm::begin(); ?>
    <?php
        echo $form->field($model, 'site_id')
                  ->hiddenInput(['value' => $parent->site_id])->label(false)
        ;
        echo $form->field($model, 'siteMode')
                  ->hiddenInput(['value' => $parent->siteMode])->label(false)
        ;
        echo $form->field($model, 'textType')
                  ->hiddenInput(['value' => $parent->textType])->label(false)
        ;
        echo $form->field($model, 'parentId')
                  ->hiddenInput(['value' => $parent->id])->label(false)
        ;
    ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'country_code')->dropDownList(
        Country::getCountryCodesList(),
        ['prompt' => 'Выберите страну'],
    ) ?>
    
    <?php
        try {
            echo
            $form->field($model, 'phone')->widget(MaskedInput::class, [
                'mask'    => '(999)999-99-99',
                'options' => [
                    'class'       => 'form-control',
                    'placeholder' => '(999)999-99-99',
                ],
            ]);
        }
        catch (Exception $e) {
        
        } ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php
        ActiveForm::end(); ?>

</div>
