<?php
    
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $appType int */
    /* @var $context string */


?>

<?php
    if ($appType < Constant::APP_SERVER): ?>
        <li class='sidebar-item'>
            <a data-bs-target='#admin-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
                <i class='fa-solid fa-server'></i>
                <span class='align-middle'>Сервер</span>
            </a>

            <ul id='admin-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>


                <li class="sidebar-item<?= ($context === 'admin/information') ? ' active' : null ?>">
                    
                    <?= Html::a(
                        'Сайты',
                        '/admin/information',
                        [
                            'class'  => 'sidebar-link',
                            'target' => '_blank',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'admin/structure') ? ' active' : null ?>">
                    
                    <?= Html::a(
                        'Структура сервера',
                        '/admin/structure',
                        [
                            'class'  => 'sidebar-link',
                            'target' => '_blank',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'admin/edit') ? ' active' : null ?>">
                    <?= Html::a(
                        'Правка',
                        '/admin/edit',
                        [
                            'class'  => 'sidebar-link',
                            'target' => '_blank',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'admin/tariff') ? ' active' : null ?>">
                    <?= Html::a(
                        'Тарифы',
                        '/admin/tariff',
                        [
                            'class'  => 'sidebar-link',
                            'target' => '_blank',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'admin/period') ? ' active' : null ?>">
                    <?= Html::a(
                        'Периоды оплаты',
                        '/admin/period',
                        [
                            'class'  => 'sidebar-link',
                            'target' => '_blank',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'admin/text-type') ? ' active' : null ?>">
                    <?= Html::a(
                        'Типы контента',
                        '/admin/text-type',
                        [
                            'class'  => 'sidebar-link',
                            'target' => '_blank',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'admin/site-mode') ? ' active' : null ?>">
                    <?= Html::a(
                        'Типы сайтов',
                        '/admin/site-mode',
                        [
                            'class'  => 'sidebar-link',
                            'target' => '_blank',
                        ],
                    )
                    ?>
                </li>
            </ul>
        </li>
    
    <?php
    endif;
?>
