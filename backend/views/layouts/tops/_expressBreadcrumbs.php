<?php
    
    use core\edit\widgets\Breadcrumbs;
    use yii\web\View;
    
    /* @var $this View */
    
    try {
        echo
        Breadcrumbs::widget(
            [
                'homeLink' => [
                    'label' => 'Главная',
                    'url'   => '@backHost',
                    'title' => 'Стандартная панель управления',
                ],
                'links'    => $this->params['breadcrumbs'] ?? [],
                'options'  => [
                    'class' => 'm-0 ',
                ],
            ],
        );
    }
    catch (Exception|Throwable $e) {
        Yii::$app->errorHandler->logException($e);
        Yii::$app->session->
        setFlash(
            'danger',
            'Что-то пошло не так.
                    Случилась ошибка. Попробуйте еще раз!',
        );
    }
