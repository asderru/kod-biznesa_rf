<?php
    
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $context string */

?>

<li class='sidebar-item'>
    <a data-bs-target='#content-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-pen-clip'></i>
        <span class='align-middle'>Контент</span>
    </a>
    <ul id='content-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>


        <li class="sidebar-item<?= ($context === 'content/page') ? ' active' : null ?>">
            <?= Html::a(
                'Cтраницы / ' . Constant::PAGE_LABEL,
                '/content/page',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'content/review') ? ' active' : null ?>">
            <?= Html::a(
                'Обзоры',
                '/content/review',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>
        <li class="sidebar-item<?= ($context === 'content/note') ? ' active' : null ?>">
            <?= Html::a(
                'Заметки',
                '/content/note',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>

        </li>

        <li class="sidebar-item<?= ($context === 'tools/draft') ? ' active' : null ?>">
            <?= Html::a(
                'Черновики',
                '/tools/draft',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>

        </li>

        <li class="sidebar-item<?= ($context === 'utils/table') ? ' active' : null ?>">
            <?= Html::a(
                'Таблицы',
                '/utils/table',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'content/tag') ? ' active' : null ?>">
            <?= Html::a(
                'Метки',
                '/content/tag',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>


        <li class="sidebar-item<?= ($context === 'content/content') ? ' active' : null ?>">
            <?= Html::a(
                'Контент',
                '/content/content',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'content/comment') ? ' active' : null ?>">
            <?= Html::a(
                'Комментарии',
                '/content/comment',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>


        <li class="sidebar-item<?= ($context === 'content/assign') ? ' active' : null ?>">
            <?= Html::a(
                'Связи',
                '/content/assign',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>
    </ul>
</li>
