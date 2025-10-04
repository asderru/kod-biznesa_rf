<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Addon\Panel */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#addon_panel_view';
    
    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Рекламные панели', 'url' => ['index']];
        $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        Html::a(
            'Поменять баннеры',
            [
                'insert',
                'id' => $model->id,
            ],
            [
                'class' => 'btn btn-sm btn-info',
            ],
        ),
        ButtonHelper::create('Добавить панель'),
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
            '/addon/panel/_partView',
            [
                'model' => $model,
                'actionId' => $actionId,
            ],
        )
        ?>

    </div>

<?php echo '</div>';
