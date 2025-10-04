<?php
    
    use backend\widgets\PagerWidget;
    use core\edit\entities\Tech\Entry;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\web\YiiAsset;
    use yii\widgets\DetailView;
    
    YiiAsset::register($this);
    
    /** @var yii\web\View $this */
    /** @var Entry $model */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#tech_entry_view';
    
    $this->title = $model->id;
    
    $buttons                       = [
    
    ];
    
    $this->params['breadcrumbs'][] = ['label' => 'Entries', 'url' => ['index']];
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
                    'host',
                    'client_ip',
                    'timestamp',
                    'request_method',
                    'request_url:ntext',
                    'http_version',
                    'status_code',
                    'response_size',
                    'referer:ntext',
                    'user_agent:ntext',
                    'created_at',
                ],
            ]);
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                LAYOUT_ID, $e,
            );
        } ?>

</div>
