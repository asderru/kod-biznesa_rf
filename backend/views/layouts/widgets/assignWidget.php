<?php
    
    use backend\widgets\select\SelectContentWidget;
    use backend\widgets\select\SelectedContentWidget;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $model Model */
    
    const ASSIGN_WIDGET_LAYOUT = '#layouts_widgets_assignWidget';
    echo PrintHelper::layout(ASSIGN_WIDGET_LAYOUT);

?>

<!--###### Slider #################################################-->


<div class='card mb-3'>

    <div
            class='card-header bg-light d-flex justify-content-between'
    >
        <h5>
            Связь с контентом
        </h5>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <button
                    class='btn btn-sm btn-secondary'
                    type='button'
                    data-bs-toggle='collapse'
                    data-bs-target='#collapsSlides'
                    aria-expanded='false'
                    aria-controls='collapsSlides'
            >Связать
            </button>
        </div>
    </div>

    <div class='card-body'>
        
        <?php
            try {
                echo
                SelectedContentWidget::widget
                (
                    [
                        'model' => $model,
                    ],
                );
            }
            catch (Exception|Throwable $e) {
                PrintHelper::exception(
                    'SelectSlideWidget ', ASSIGN_WIDGET_LAYOUT, $e,
                );
            }
        ?>
        <hr>
        <div class='collapse' id='collapsSlides'>
            <?php
                try {
                    echo
                    SelectContentWidget::widget
                    (
                        [
                            'model' => $model,
                        ],
                    );
                }
                catch (Exception|Throwable $e) {
                    PrintHelper::exception(
                        'SelectSlideWidget', ASSIGN_WIDGET_LAYOUT, $e,
                    );
                }
            ?>
        </div>
    </div>
</div>
