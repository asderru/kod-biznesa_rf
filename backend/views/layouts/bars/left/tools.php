<?php
    
    use yii\bootstrap5\Html;
    
    /* @var $context string */

?>
<li class='sidebar-item'>
    <a data-bs-target='#tools-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-screwdriver-wrench'></i>
        <span class='align-middle'>Инструменты</span>
    </a>

    <ul id='tools-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>
        <li class="sidebar-item<?= ($context === 'tools/description') ? ' active' : null ?>">
            <?= Html::a(
                'Мета-описания',
                '/tools/description',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>
        <li class="sidebar-item<?= ($context === 'tools/keywords') ? ' active' : null ?>">
            <?= Html::a(
                'Ключевые слова',
                '/tools/keywords',
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


        <li class="sidebar-item<?= ($context === 'tools/seo-link') ? ' active' : null ?>">
            <?= Html::a(
                'Ссылки',
                '/tools/seo-link',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

    </ul>

</li>
