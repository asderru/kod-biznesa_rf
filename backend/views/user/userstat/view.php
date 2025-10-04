<?php
    
    use backend\widgets\PagerWidget;
    use core\edit\entities\User\UserStatistic;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\web\YiiAsset;
    use yii\widgets\DetailView;
    
    YiiAsset::register($this);
    
    /** @var yii\web\View $this */
    /** @var UserStatistic $model */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_log_view';
    
    $this->title                   = $model->id;
    
    $buttons                       = [
    
    ];
    
    $this->params['breadcrumbs'][] = ['label' => 'User Logs', 'url' => ['index']];
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

?>

<div class='card'>

    <h1><?= Html::encode($this->title)
        ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])
        ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method'  => 'post',
            ],
        ])
        ?>
    </p>
    
    <?php
        try {
            echo
            DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    'id',
                    'site_id',
                    'edit',
                    'text_type',
                    'parent_id',
                    'page_url:url',
                    'entry_time:datetime',
                    'exit_time:datetime',
                    'ip_address',
                    'user_id',
                ],
            ]);
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                $actionId, 'DetailView ' . LAYOUT_ID, $e,
            );
            
        } ?>

</div>
