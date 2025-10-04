<?php
    
    use backend\widgets\TableWidget;
    use core\edit\entities\Utils\Table;
    use core\helpers\PrintHelper;
    
    /* @var $this yii\web\View */
    /* @var $model Table */
    
    const UTILS_TABLE_PART_LAYOUT = '#utils_table_partView';
    echo PrintHelper::layout(UTILS_TABLE_PART_LAYOUT);

?>
<?php
    try {
        echo
        TableWidget::widget(
            [
                'table' => $model,
            ],
        );
    }
    catch (Exception|Throwable $e) {
        PrintHelper::exception(
            'TableWidget '
            , UTILS_TABLE_PART_LAYOUT, $e,
        );
    }
?>
