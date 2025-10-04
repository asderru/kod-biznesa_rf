<?php
    
    use core\helpers\AppHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $context string */
    
    if (!AppHelper::showMagazin()) {
        return null;
    }

?>

<!--###### Magazin ##################################-->

<li class='sidebar-item'>
    <a data-bs-target='#magazin-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-regular fa-newspaper'></i>
        <span class='align-middle'>Журналы</span>
    </a>
    <ul id='magazin-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>

        <li class="sidebar-item<?= ($context === 'magazin/section') ? ' active' : null ?>">
            <?= Html::a(
                'Рубрики / ' . Constant::SECTION_LABEL,
                '/magazin/section',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'magazin/article') ? ' active' : null ?>">
            <?= Html::a(
                Constant::ARTICLE_LABEL,
                '/magazin/article',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

    </ul>

</li>
