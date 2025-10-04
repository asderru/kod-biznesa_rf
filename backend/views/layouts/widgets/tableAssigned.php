<?php
    
    use backend\widgets\TableAssignedWidget;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $model Model */
    /* @var $title string */
    
    
    const TABLE_WIDGET_LAYOUT = '#layouts_widgets_tableAssigned';
    echo PrintHelper::layout(LAYOUT_ID);
    
    try {
        echo
        TableAssignedWidget::widget(
            [
                'model' => $model,
            ],
        );
    }
    catch (Exception|Throwable $e) {
        PrintHelper::exception(
            'TableAssignedWidget ', TABLE_WIDGET_LAYOUT, $e,
        );
    }
