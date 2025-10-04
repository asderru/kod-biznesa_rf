<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Admin\TextType */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_textType_view';
    
    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Site Modes', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        ButtonHelper::update($model->id, 'Редактировать'),
        ButtonHelper::create(),
        ButtonHelper::clearCache($model->site_id, $textType, $model->id),
        ButtonHelper::delete($model),
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
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
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
            '/admin/text-type/_partView',
            [
                'model' => $model,
                'actionId' => $actionId,
            ],
        )
        ?>

    </div>

<?php
    echo '</div>';
