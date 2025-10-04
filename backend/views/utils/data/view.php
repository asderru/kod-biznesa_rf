<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Data */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_data_view';
    
    $this->title                   = $model->id;
    $this->params['breadcrumbs'][] = [
        'label' => 'Таблицы',
        'url'   => [
            'utils/table/index',
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $model->table->name,
        'url'   => [
            'utils/table/view',
            'id' => $model->table_id,
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => 'Данные таблиц',
        'url'   => ['index'],
    ];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        ButtonHelper::update($model->id, 'Редактировать'),
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
            '/utils/column/_partView', [
            'model'    => $model,
            'actionId' => $actionId,
        ],
        )
        ?>


    </div>

<?php
    echo '</div>';
