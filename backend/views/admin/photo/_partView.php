<?php
    
    use core\edit\entities\Admin\PhotoSize;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model PhotoSize */
    
    const ADMIN_PHOTO_PART_LAYOUT = '#admin_photo_partView';
    echo PrintHelper::layout(ADMIN_PHOTO_PART_LAYOUT);
    
    try {
        echo DetailView::widget(
            [
                'model'      => $model,
                'attributes' => [
                    //'id',
                    //'site_id',
                    [
                        'attribute' => 'text_type',
                        'label'     => 'Тип источника',
                        'value'     => static function (PhotoSize $model) {
                            return
                                TypeHelper::getLabel
                                (
                                    $model->text_type,
                                );
                        },
                        'format'    => 'raw',
                    ],
                    'width',
                    'height',
                    [
                        'attribute' => 'watermark',
                        'label'     => 'Ватермарк',
                        'value'     => static function (PhotoSize $model) {
                            return ($model->watermark === 0)
                                ?
                                'Отсутствует'
                                :
                                '#' . $model->watermark;
                        },
                        'format'    => 'raw',
                    ],
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'DetailView-widget ', ADMIN_PHOTO_PART_LAYOUT, $e,
        );
    }
