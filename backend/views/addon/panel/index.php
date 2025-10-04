<?php
    
    use core\edit\search\Addon\PanelSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\ParametrHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $searchModel PanelSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#addon_panel_index';
    
    $this->title = $label;
    
    $buttons = [];
    
    $this->params['breadcrumbs'][] = $this->title;
    
    $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    )
?>

<div class='card'>
    
    <?= $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?php
                if (!ParametrHelper::isServer()) { ?>
                    <?= ButtonHelper::resort
                    (
                        $siteId, 'Сортировать',
                    )
                    ?>
                    <?php
                }
            ?>
            <?= ButtonHelper::create('Добавить панель')
            ?>
            
            <?php
                echo
                ButtonHelper::refresh(); ?>
            <?= ButtonHelper::collapse()
            ?>
        </div>
    </div>

        <?= $this->render('@app/views/addon/panel/_partIndex', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ])
        ?>

</div>
