<?php
    
    use yii\web\View;
    
    /* @var $this View */
    /* @var $textType int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $actionId string */
    /* @var $layoutId string */

?>

<div class='small bg-info-subtle d-flex justify-content-between px-2 py-1'>
    <div>
        '#<?= $textType ?>. <?= $label ?> : <?= $prefix ?>';
    </div>
    <div>
        '<?= $actionId ?>';
    </div>
    <div>
        '<?= $layoutId ?>';
    </div>
</div>
