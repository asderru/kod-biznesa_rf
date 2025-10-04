<?php
    
    use yii\helpers\Html;
    
    /** @var yii\web\View $this */
    /** @var core\edit\entities\Utils\Phone $model */
    
    const LAYOUT_ID = '#utils_phone_ipdate';
    
    $this->title                   = 'Update Phones: ' . $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Phones', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Update';
?>
<div class="phones-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
