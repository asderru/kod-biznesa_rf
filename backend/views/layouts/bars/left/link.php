<?php
    
    use yii\bootstrap5\Html;
    
    /* @var $context string */

?>
<li class='sidebar-item'>
    <a data-bs-target='#link-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-link'></i>
        <span class='align-middle'>Ссылки</span>
    </a>

    <ul id='link-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>
        <li class="sidebar-item<?= ($context === 'link/gazer') ? ' active' : null ?>">
            <?= Html::a(
                'LinkGazer',
                '/link/gazer',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>
        <li class="sidebar-item<?= ($context === 'link/master') ? ' active' : null ?>">
            <?= Html::a(
                'Проверка ссылок',
                '/link/master',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'link/internal') ? ' active' : null ?>">
            <?= Html::a(
                'Внутренние ссылки',
                '/link/internal',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

    </ul>

</li>
