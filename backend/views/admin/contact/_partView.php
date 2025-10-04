<?php
    
    use core\helpers\PrintHelper;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Admin\Contact */
    
    const ADMIN_CONTACT_PART_LAYOUT = '#admin_contact_partView';
    echo PrintHelper::layout(ADMIN_CONTACT_PART_LAYOUT);

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
                <?php
                    try {
                        echo DetailView::widget(
                            [
                                'model'      => $model,
                                'attributes' => [
                                    'id',
                                    'site_id',
                                    'name',
                                    'website',
                                    'email',
                                    'zakaz_mail',
                                    'address',
                                    'analytics',
                                    'social_networks',
                                    'messengers',
                                    'work_hours',
                                    'phone',
                                    'languages',
                                ],
                            ],
                        );
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                            'DetailView-widget ', ADMIN_CONTACT_PART_LAYOUT, $e,
                        );
                    } ?>

            </div>
        </div>
        <div class='col-xl-6'>
            <!--Загрузка картинок-->

            <!--Конец загрузки-->
        </div>
    </div>
</div>
