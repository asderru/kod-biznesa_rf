<?php
    
    use backend\widgets\BannerWidget;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $model Model */
    
    const BANNER_WIDGET_LAYOUT = '#layouts_widgets_bannerWidget';
    
    try {
        echo
        BannerWidget::widget(
            [
                'model' => $model,
                'title' => 'Рекламный баннер',
                'true'  => true,
            ],
        );
    }
    catch (Exception|Throwable $e) {
        PrintHelper::exception(
            ' BannerWidget::widget ', BANNER_WIDGET_LAYOUT
            , $e,
        );
    }
