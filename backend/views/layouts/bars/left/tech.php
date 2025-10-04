<?php
    
    use yii\bootstrap5\Html;
    
    /* @var $context string */

?>
<li class='sidebar-item'>
    <a data-bs-target='#onpage-seo-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-chart-line'></i>
        <span class='align-middle'>On-Page SEO</span>
    </a>

    <ul id='onpage-seo-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>


        <li class="sidebar-item<?= ($context === 'tech/log') ? ' active' : null ?>">
            <?= Html::a(
                'Просмотр логов',
                '/tech/log',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

    </ul>

</li>
