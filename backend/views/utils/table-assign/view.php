<?php
    
    use backend\widgets\PagerWidget;
    use core\edit\assignments\TableAssignment;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $parent Model */
    /* @var $models TableAssignment[] */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_tableAssign_view';
    
    $this->title = 'Таблицы для ' . $parent?->name;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Таблицы',
        'url'   => [
            '/utils/table/index',
        ],
    ];
        $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
            
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
                'model'  => $parent,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
    
    }

?>

<div class='card'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= Html::a(
                'Сортировка',
                [
                    'resort',
                    'textType' => $parent::TEXT_TYPE,
                    'parentId' => $parent->id,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-secondary mr-1 mb-1',
                ],
            )
            ?>
            <?= Html::a(
                'Вернуться на ' . $parent->name,
                [
                    $parent->viewUrl,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-dark mr-1 mb-1',
                ],
            )
            ?>
        </div>

    </div>

    <div class="card-body">
        
        <?= $this->render(
            '@app/views/utils/table-assign/_partView',
            [
                'models' => $models,
            ],
        )
        ?>

    </div>
</div>
