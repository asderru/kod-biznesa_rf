<?php
    
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Alert;
    use yii\web\View;
    
    /* @var $this View */
    
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        if (
            Yii::$app->getSession()
                     ->hasFlash($key)
        ) {
            try {
                echo Alert::widget(
                    [
                        'options' =>
                            [
                                'class' => (
                                in_array(
                                    $key,
                                    [
                                        'success', 'info', 'warning', 'danger',
                                    ],
                                ) ?
                                    'alert-' . $key
                                    : 'alert-info'
                                ),
                            ],
                        'body'    => $message,
                    ],
                );
            }
            catch (Exception|Throwable $e) {
                PrintHelper::exception(
                    'AlertWidget ', '#layouts_partials_messages', $e,
                );
            }
        }
        
    }
