<?php
    
    use core\edit\entities\Admin\PhotoRatio;
    use core\helpers\PrintHelper;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model PhotoRatio */
    
    const ADMIN_RATIO_PART_LAYOUT = '#admin_ratio_partView';
    echo PrintHelper::layout(ADMIN_RATIO_PART_LAYOUT);
    
    try {
        echo DetailView::widget(
            [
                'model'      => $model,
                'attributes' => [
                    'ratio1',
                    'ratio2',
                    'ratio3',
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'DetailView-widget ', ADMIN_RATIO_PART_LAYOUT, $e,
        );
    }
