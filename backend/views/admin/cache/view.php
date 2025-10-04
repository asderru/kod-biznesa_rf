<?php
    
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $key string */
    /* @var $value mixed */
    /* @var $message string|null */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID                = '#admin_cache_index';
    echo PrintHelper::layout(LAYOUT_ID);
    
    $this->title = 'Cache Keys';
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
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
    
    echo '<div class="card">';
    
    echo Html::tag('h1', Html::encode($this->title));
    
    if ($message): ?>
        <p><?= $message ?></p>
    <?php
    else: ?>
        <h2>Value for key: <?= $key ?></h2>
        <pre><?php
                var_dump($value); ?></pre>
    <?php
    endif;
    
    echo '</div>';
