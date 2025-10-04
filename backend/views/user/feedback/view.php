<?php
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\User\Feedback */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    
    /* @var $textType int */
    
    use backend\widgets\PagerWidget;
    use core\helpers\PrintHelper;
    
    const LAYOUT_ID = '#user_feedback_view';
    
    $this->title                   = $model->name;
    
    
    $buttons = [
    
    ];
    
    $this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    );
    
    try {
        echo
        PagerWidget::widget(
            [
                'model'  => $model,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
    echo '<div class="card">';
    
echo $this->render(
    '/layouts/tops/_viewHeaderModel',
        [
            'model'    => $model,
            'textType' => $textType,
            'buttons'  => $buttons, // передаем массив кнопок
        ],
    );

echo $this->render(
    '/user/feedback/_partView', [
        'model' => $model,
        'actionId' => $actionId,
    ]);
    
    echo '</div>';
