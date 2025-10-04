<?php
    
    use backend\widgets\select\SelectContentWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $model Model[] */
    /* @var $label string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const PARTIALS_ASSIGNED_LAYOUT = '#layouts_partials_assigned';
    echo PrintHelper::layout(PARTIALS_ASSIGNED_LAYOUT);

?>

<div class="card-header bg-body-secondary">
    
    <?= ButtonHelper::collapse($label ?? 'Связанный контент', '#assignedButtons')
    ?>

</div>

<div class='card-body mb-2 collapse btn-group-sm gap-2' id='assignedButtons'>
    <?php
            echo
            SelectContentWidget::widget(
                [
                    'model' => $model,
                ],
            );
    ?>

</div>
