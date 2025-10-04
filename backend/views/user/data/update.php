<?php
    
    use core\edit\entities\User\UserData;
    use yii\bootstrap5\Html;
    
    /** @var yii\web\View $this */
    /** @var UserData $model */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_data_update';
    
    $this->title                   = 'Update User Data: ' . $model->id;
    $this->params['breadcrumbs'][] = ['label' => 'User Datas', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Update';
    
    echo $this->render(
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

<div class="user-data-update">

    <h1><?= Html::encode($this->title)
        ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
