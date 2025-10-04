<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\edit\entities\Content\Content;
    
    /* @var $this yii\web\View */
    /* @var $model Content */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_content_view';
    
    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = [
        'label' => 'Контент',
        'url'   => ['index'],
    ];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        ButtonHelper::contentEditChatgpt($model),
        ButtonHelper::contentEditBard($model),
        ButtonHelper::contentEditClaude($model),
    ];
    
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
    
    }
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_viewHeaderModel',
        [
            'model' => $model,
            'textType' => $textType,
            'buttons'  => $buttons, // передаем массив кнопок
        ],
    )
?>

    <div class='card-body'>
        
        <?= $this->render(
            '/content/content/_partView',
            [
                'model' => $model,
            ],
        )
        ?>

    </div>

<?php
    echo '</div>';
