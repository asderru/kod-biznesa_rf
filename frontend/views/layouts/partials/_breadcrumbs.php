<?php
    
    use core\helpers\PrintHelper;
    use core\edit\widgets\Breadcrumbs;

?>
<div id='breadcrumbs' class='breadcrumbs'>
    <?php
        try {
            echo
            Breadcrumbs::widget(
                [
                    'homeLink' => [
                        'label' => 'Главная',
                        'url'   => '@homepage',
                        'title' => Yii::$app->name,
                    ],
                    'links'    => $this->params['breadcrumbs'] ?? [],
                    'options'  => [
                        'id'    => 'br1',
                        'class' => 'breadcrumb-item',
                    ],
                ],
            );
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                'Breadcrumbs-widget ', $e,
            );
        } ?>
</div>
