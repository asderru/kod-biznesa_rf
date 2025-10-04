<?php
    
    use yii\bootstrap5\Html;
    
    /* @var $context string */


?>

<li class='sidebar-item'>
    <a data-bs-target='#order-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-list-check'></i>
        <span class='align-middle'>Заказы</span>
    </a>

    <ul id='order-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>


        <li class="sidebar-item<?= ($context === 'order/order') ? ' active' : null ?>">
            <?= Html::a(
                'Заказы',
                '/order/order',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'order/delivery') ? ' active' : null ?>">
            <?= Html::a(
                'Доставка',
                '/order/delivery',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

    </ul>
</li>
