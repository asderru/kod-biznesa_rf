<?php
    
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Admin\SiteMode */
    
    const ADMIN_SITEMODE_PART_LAYOUT = '#admin_siteMode_partView';
    echo PrintHelper::layout(ADMIN_SITEMODE_PART_LAYOUT);

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
                    
                    <?php
                        try {
                            echo DetailView::widget(
                                [
                                    'model'      => $model,
                                    'attributes' => [
                                        'id',
                                        'name',
                                        'description',
                                    ],
                                ],
                            );
                        }
                        catch (Throwable $e) {
                            PrintHelper::exception(
                                'DetailView-widget ', ADMIN_SITEMODE_PART_LAYOUT, $e,
                            );
                        } ?>
                </div>
            </div>
        </div>
        <div class='col-xl-6'>
            <div class='card mb-3'>
                <div class='card-header text-white bg-secondary'>
                    <strong>
                        Описание
                    </strong>
                </div>
                <div class='card-body'>
                    <?= FormatHelper::asHtml($model->description)
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
