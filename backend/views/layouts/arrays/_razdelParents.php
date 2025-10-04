<?php

    use core\helpers\types\TypeHelper;
    use core\helpers\types\TypeUrlHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;

    /* @var $model array */
    /* @var $parents array */
    /* @var $textType int */
?>

<?php
    if ($model['depth'] >= Constant::THIS_FIRST_NODE && !empty($parents)) { ?>
        <div class='p-1 mb-1 border-bottom'>
            Родительские модели (<?= TypeHelper::getName($textType, 1, true) ?>): <br>
            <?php
                foreach ($parents as $index => $parent) { ?>
                    <span class="parent-level-<?= $parent['depth'] ?>">
                <?= str_repeat('&nbsp;&nbsp;', $index) ?>
                <?= Html::a(
                    Html::encode($parent['name']),
                    [
                        TypeUrlHelper::getLongEditUrl($parent['array_type']) . 'view',
                        'id' => $parent['id'],
                    ],
                    ['target' => '_blank'],
                ) ?>
                <?= $index < count($parents) - 1 ? ' →' : '' ?>
            </span>
                <?php
                } ?>
        </div>
    <?php
    } ?>
