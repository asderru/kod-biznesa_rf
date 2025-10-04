<?php
    
    use core\edit\search\Seo\AnonsSearch;
    use yii\jui\DatePicker;
    use yii\web\View;
    
    /* @var $attribute_from string */
    /* @var $attribute_to string */
    /* @var $this View */
    /* @var $model AnonsSearch */

?>

<div>
    <?php
        try {
            echo DatePicker::widget([
                'model'      => $model,
                'attribute'  => $attribute_from,
                'dateFormat' => 'php:Y-m-d',
                'options'    => [
                    'class'       => 'form-control',
                    'placeholder' => 'От',
                ],
            ]);
        }
        catch (Throwable $e) {
        
        }
        try {
            echo
            DatePicker::widget([
                'model'      => $model,
                'attribute'  => $attribute_to,
                'dateFormat' => 'php:Y-m-d',
                'options'    => [
                    'class'       => 'form-control',
                    'placeholder' => 'До',
                ],
            ]);
        }
        catch (Throwable $e) {
        
        } ?>
</div>
