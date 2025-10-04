<?php
    
    use backend\widgets\PagerWidget;
    use core\edit\entities\User\UserData;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /** @var yii\web\View $this */
    /** @var UserData $model */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_data_view';
    
    $this->title                   = $model->id;
    
    $buttons = [
    
    ];
    
    $this->params['breadcrumbs'][] = ['label' => 'User Datas', 'url' => ['index']];
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
                    'ip_address',
                    'user_id',
                    'user_name',
                    'country',
                    'operating_system',
                    'browser',
                    'browser_version',
                ],
            ]);
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                $actionId, 'DetailView ' . LAYOUT_ID, $e,
            );
            
        } ?>

</div>
