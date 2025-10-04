<?php
    
    use backend\widgets\PagerWidget;
    use core\edit\entities\Utils\Table;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    
    /* @var $this yii\web\View */
    /* @var $model Table */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_table_view';
    
    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Таблицы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        ButtonHelper::activation($model),
        ButtonHelper::create(),
        ButtonHelper::indexColumns($model->id),
        ButtonHelper::indexRows($model->id),
        ButtonHelper::columnCreate($model->id),
        ButtonHelper::rowCreate($model->id),
        ButtonHelper::copy($model->id, 'Копировать таблицу'),
        ButtonHelper::columnResort($model->id),
        ButtonHelper::rowResort($model->id),
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

    <div class="card-body">
        
        <?= $this->render(
            '/utils/table/_partView',
            [
                'model'    => $model,
                'actionId' => $actionId,
            ],
        )
        ?>
        
    </div>

<?php
    echo '</div>';
