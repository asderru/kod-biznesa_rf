<?php
    
    use backend\widgets\NoteWidget;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $model Model */
    /* @var $title string */
    /* @var $true bool */
    
    const NOTE_WIDGET_LAYOUT = '#layouts_widgets_noteWidget';
    echo PrintHelper::layout(LAYOUT_ID);
    
    try {
        echo
        NoteWidget::widget(
            [
                'parent' => $model,
                'title'  => $title,
                'true'   => $true,
            ],
        );
    }
    catch (Exception|Throwable $e) {
        PrintHelper::exception(
            ' NoteWidget::widget ', NOTE_WIDGET_LAYOUT
            , $e,
        );
    }
