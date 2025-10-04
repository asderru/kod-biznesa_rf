<?php
    
    use core\helpers\StatusHelper;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $nextModel array */
    /* @var $prevModel array */
    
    $class = 'text-secondary p-2';
    
    if ($prevModel) {
        $prevUrl = StatusHelper::icon($prevModel['status'])
                   . Html::a(
                Html::encode($prevModel['name']),
                [
                    'view',
                    'id' => $prevModel['id'],
                ],
            );
    }
    else {
        $prevUrl = Html::a(
            'На главную', 'index',
            [
                'class' => $class,
            ],
        );
    }
    
    if ($nextModel) {
        $nextUrl = StatusHelper::icon($nextModel['status'])
                   . Html::a(
                Html::encode($nextModel['name']),
                [
                    'view',
                    'id' => $nextModel['id'],
                ],
            );
    }
    else {
        $nextUrl = Html::a(
            'На главную', 'index',
            [
                'class' => $class,
            ],
        );
    }
?>

<div class='bg-primary-subtle d-flex justify-content-between border-bottom'>
    <!-- Previous navigation -->
    <div class='p-1 text-secondary'>
        <i class='bi bi-caret-left'></i>
        <?= $prevUrl ?>
    </div>

    <!-- Next navigation -->
    <div class="p-1 text-secondary">
        <?= $nextUrl ?>
        <i class='bi bi-caret-right'></i>
    </div>
</div>
