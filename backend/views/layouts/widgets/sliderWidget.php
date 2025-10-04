<?php
    
    use backend\widgets\select\SelectSlideWidget;
    use core\edit\forms\Utils\Gallery\SliderForm;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $model Model */
    /* @var $sliderForm SliderForm */
    
    const SLIDER_WIDGET_LAYOUT = '#layouts_widgets_sliderWidget';
    echo PrintHelper::layout(SLIDER_WIDGET_LAYOUT);

?>

<!--###### Slider #################################################-->


<div class='card mb-3'>

    <div
            class='card-header bg-light d-flex justify-content-between'
    >
        <h5>
            Слайды
        </h5>

    </div>
    <?php
        
        try {
            echo
            SelectSlideWidget::widget
            (
                [
                    'model' => $model,
                ],
            );
        }
        catch (Exception|Throwable $e) {
            PrintHelper::exception(
                'SelectSlideWidget ', SLIDER_WIDGET_LAYOUT, $e,
            );
        }
    ?>
</div>
