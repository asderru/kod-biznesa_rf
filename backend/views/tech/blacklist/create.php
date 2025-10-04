<?php
    
    use core\edit\entities\Tech\BlackList;
    use yii\bootstrap5\Html;
    
    /** @var yii\web\View $this */
    /** @var BlackList $model */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    $this->title                   = 'Create Black List';
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

<div class="black-list-create">

    <h1><?= Html::encode($this->title)
        ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
