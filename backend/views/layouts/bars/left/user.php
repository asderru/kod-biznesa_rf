<?php
    
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $appType int */
    /* @var $context string */


?>

<li class='sidebar-item'>
    <a data-bs-target='#user-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-users'></i>
        <span class='align-middle'>Пользователи</span>
    </a>

    <ul id='user-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>


        <li class="sidebar-item<?= ($context === 'user/user') ? ' active' : null ?>">
            
            <?= Html::a(
                'Пользователи',
                '/user/user',
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

        <li class="sidebar-item<?= ($context === 'user/feedback') ? ' active' : null ?>">
            <?= Html::a(
                'Уведомления',
                '/user/feedback',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>
        
        <?php
            if ($appType < Constant::APP_STANDART): ?>
                <li class="sidebar-item<?= ($context === 'user/userstat') ? ' active' : null ?>">
                    <?= Html::a(
                        'Статистика',
                        '/user/userstat',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>
                <li class="sidebar-item<?= ($context === 'user/blacklist') ? ' active' : null ?>">
                    <?= Html::a(
                        'Черный список',
                        '/user/blacklist',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>
            
            <?php
            endif; ?>
    </ul>
</li>
