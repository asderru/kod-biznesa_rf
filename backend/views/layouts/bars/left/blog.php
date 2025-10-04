<?php
    
    use core\helpers\AppHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $context string */
    
    if (!AppHelper::showBlog()) {
        return null;
    }

?>

<li class='sidebar-item'>
    <a data-bs-target='#blog-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-feather'></i>
        <span class='align-middle'>Блог</span>
    </a>
    <ul id='blog-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>


        <li class="sidebar-item<?= ($context === 'blog/category') ? ' active' : null ?>">
            <?= Html::a(
                Constant::CATEGORY_LABEL,
                '/blog/category',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'blog/post') ? ' active' : null ?>">
            <?= Html::a(
                Constant::POST_LABEL,
                '/blog/post',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

    </ul>

</li>
