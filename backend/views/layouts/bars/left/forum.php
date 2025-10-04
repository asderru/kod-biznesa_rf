<?php
    
    use core\helpers\AppHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $context string */
    
    if (!AppHelper::showForum()) {
        return null;
    }

?>

<!--###### Forum ##################################-->
<li class='sidebar-item'>
    <a data-bs-target='#forum-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-users-between-lines'></i>
        <span class='align-middle'>Форумы</span>
    </a>
    <ul id='forum-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>

        <li class="sidebar-item<?= ($context === 'forum/group') ? ' active' : null ?>">
            
            <?= Html::a(
                Constant::GROUP_LABEL,
                '/forum/group',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'forum/thread') ? ' active' : null ?>">
            <?= Html::a(
                Constant::THREAD_LABEL,
                '/forum/thread',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'user/person') ? ' active' : null ?>">
            <?= Html::a(
                'Профили',
                '/user/person',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

    </ul>

</li>
