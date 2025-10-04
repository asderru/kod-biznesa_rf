<?php
    
    use core\edit\entities\Tech\BlackList;
    use yii\bootstrap5\Html;
    
    /** @var yii\web\View $this */
    /** @var BlackList $model */
    
    $this->title                   = 'Update Black List: ' . $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Black Lists', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Update';
?>
<div class="black-list-update">

    <h1><?= Html::encode($this->title)
        ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
