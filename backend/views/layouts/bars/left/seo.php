<?php
    
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $context string */

?>
<li class='sidebar-item'>
    <a data-bs-target='#seo-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-chart-line'></i>
        <span class='align-middle'>Продвижение</span>
    </a>

    <ul id='seo-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>

        <li class="sidebar-item<?= ($context === 'seo/anons') ? ' active' : null ?>">
            <?= Html::a(
                Constant::ANONS_LABEL,
                '/seo/anons',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'seo/faq') ? ' active' : null ?>">
            
            <?= Html::a(
                Constant::FAQ_LABEL,
                '/seo/faq',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'seo/footnote') ? ' active' : null ?>">
            
            <?= Html::a(
                Constant::FOOTNOTE_LABEL,
                '/seo/footnote',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'seo/news') ? ' active' : null ?>">
            
            <?= Html::a(
                Constant::NEWS_LABEL,
                '/seo/news',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'seo/material') ? ' active' : null ?>">
            <?= Html::a(
                Constant::MATERIAL_LABEL,
                '/seo/material',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'utils/gallery') ? ' active' : null ?>">
            <?= Html::a(
                'Галереи',
                '/utils/gallery',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'addon/panel') ? ' active' : null ?>">
            <?= Html::a(
                'Панели',
                '/addon/panel',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>
    </ul>

</li>
