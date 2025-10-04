<?php
    
    use core\helpers\PrintHelper;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Data */
    
    const UTILS_DATA_PART_LAYOUT = '#utils_data_partView';
    echo PrintHelper::layout(UTILS_DATA_PART_LAYOUT);

?>

<div class='row'>

    <div class='col-xl-6'>
        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Информация
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
                                            'site_id',
                                            'table_id',
                                            'col_id',
                                            'row_id',
                                            'value',
                                            'updated_at',
                                        ],
                                    ],
                                );
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'DetailView-widget ', UTILS_DATA_PART_LAYOUT, $e,
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
