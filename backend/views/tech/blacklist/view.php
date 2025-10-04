<?php
    
    use core\edit\entities\Tech\BlackList;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\web\YiiAsset;
    use yii\widgets\DetailView;
    
    
    YiiAsset::register($this);
    
    /** @var yii\web\View $this */
    /** @var BlackList $model */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#tech_blacklist_view';
    
    $this->title = $model->name;
    
    $buttons                       = [
    
    ];
    
    $this->params['breadcrumbs'][] = ['label' => 'Black Lists', 'url' => ['index']];
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

?>
<div class="black-list-view">

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
                    'name',
                ],
            ]);
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                LAYOUT_ID, $e,
            );
        } ?>

</div>
