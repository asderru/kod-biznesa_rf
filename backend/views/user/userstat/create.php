<?php
    
    use core\edit\entities\User\UserStatistic;
    use yii\bootstrap5\Html;
    
    /** @var yii\web\View $this */
    /** @var UserStatistic $model */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    $this->title                   = 'Create User Log';
    $this->params['breadcrumbs'][] = ['label' => 'User Logs', 'url' => ['index']];
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
    );

?>
<div class="user-log-create">

    <h1><?= Html::encode($this->title)
        ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
