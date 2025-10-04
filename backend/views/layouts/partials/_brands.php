<?php
    
    use core\edit\entities\Shop\Brand;
    use core\edit\forms\Shop\BrandsForm;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $model BrandsForm */
    /* @var $form ActiveForm */
    /* @var $siteId int */
    
    $brands = Brand::brandsList();
    
    if (empty($brands)) {
        echo $form->field($model, 'existing')->hiddenInput(['value' => ''])->label(false);
    }
    else {
        ?>
        <div class='card-header bg-light'>
            <strong>Бренды</strong>
        </div>
        <div class='card-body'>
            <?= $form->field($model, 'existing')
                     ->inline()
                     ->checkboxList(
                         $brands,
                         ['id' => 'brands-existing'],
                     )
                     ->label('Отметить бренды:') ?>
        </div>
        <?php
    }
?>
