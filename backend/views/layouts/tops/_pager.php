<?php
    
    use core\helpers\IconHelper;
    use core\helpers\ModelHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $id int */
    /* @var $status int */
    /* @var $title string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $model Model */
    /* @var $folder bool */
    
    
    $true = (isset($folder));
    
    $class = 'text-secondary p-2';
    
    $prev = $model->getPrevModel(Constant::STATUS_ROOT);
    
    if ($prev) {
        $prevUrl = ($folder ?? false)
            ?
            Html::a(
                Html::encode($prev->name),
                [
                    'view',
                    'id' => $prev->id,
                ],
            )
            :
            Html::a(
                Html::encode($prev->name),
                ModelHelper::getView($prev),
                [
                    'class' => $class,
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
    
    $next = $model->getNextModel();
    
    if ($next) {
        $nextUrl = ($folder ?? false)
            ?
            Html::a(
                Html::encode($next->name),
                [
                    'view',
                    'id' => $next->id,
                ],
            )
            :
            Html::a(
                Html::encode($next->name),
                ModelHelper::getView($prev),
                [
                    'class' => $class,
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

<div class='bg-light mb-1 px-2 rounded-2'>

    <div class='d-flex justify-content-between border-bottom'>
        <div class='p-1 text-secondary'>

            <i class='bi bi-caret-left'></i>
            
            <?= $prevUrl ?>
            
            <?php
                if ($prev) { ?>

                    <button
                            type='button' class='btn btn-sm btn-outline-dark mb-1'
                            data-bs-toggle='modal'
                            data-bs-target='#prevModal'
                    >
                        <?= IconHelper::biChatLeftFill('Смотреть в модальном окне') ?>
                    </button>
                    
                    <?php
                } ?>

        </div>

        <div class="p-1 text-secondary">
            
            <?php
                if ($nextUrl) { ?>

                    <button
                            type='button' class='btn btn-sm
								btn-outline-dark mb-1'
                            data-bs-toggle='modal'
                            data-bs-target='#nextModal'
                    >
                        <?= IconHelper::biChatLeftFill('Смотреть в модальном окне') ?>
                    </button>
                    <?php
                } ?>
            
            
            <?= $nextUrl ?>

            <i class='bi bi-caret-right'></i>

        </div>
    </div>
</div>
