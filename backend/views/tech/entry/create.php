<?php
    
    use core\edit\entities\Tech\Entry;
    use yii\bootstrap5\Html;
    
    /** @var yii\web\View $this */
    /** @var Entry $model */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    $this->title = 'Create Entry';
    
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

?>
<div class="entry-create">

    <h1><?= Html::encode($this->title)
        ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
