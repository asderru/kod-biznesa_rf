<?php

    use core\helpers\types\TypeHelper;
    use core\helpers\types\TypeUrlHelper;
    use yii\bootstrap5\Html;

    /* @var $textType int */
    /* @var $parent array */

?>

<?php
    if ($parent):
        ?>
        <div class='p-1 mb-1 '>
            <?= TypeHelper::getName($textType, 1, false, true) ?> к <?= TypeHelper::getName($parent['array_type'], 3) ?>
            <strong><?= Html::a(
                        Html::encode($parent['name']),
                        [
                                TypeUrlHelper::getLongEditUrl($parent['array_type']) . 'view',
                                'id' => $parent['id'],
                        ],
                        ['target' => '_blank'],
                );
                ?></strong>
        </div>
    <?php
    endif; ?>
