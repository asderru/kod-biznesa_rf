<?php
    
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Column */
    
    const UTILS_ROW_PART_LAYOUT = '#utils_row_partView';
    echo PrintHelper::layout(UTILS_ROW_PART_LAYOUT);

?>
<div class='row'>

    <div class='col-xl-6'>
        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Строка таблицы <?= Html::encode($model->table->name)
                    ?>
                </strong>
            </div>
            <div class='card-body'>
                <div class='table-responsive'>
                    <div class='table table-striped'>
                        
                        <?php
                            try {
                                echo DetailView::widget(
                                    [
                                        'model'      => $model,
                                        'attributes' => [
                                            'id',
                                            'table_id',
                                            'name',
                                            'sort',
                                            'status',
                                        ],
                                    ],
                                );
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'DetailView-widget ', UTILS_ROW_PART_LAYOUT, $e,
                                );
                            } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class='col-xl-6'>
        </div>
    </div>

</div>
