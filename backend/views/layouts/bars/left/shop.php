<?php
    
    use core\helpers\AppHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $context string */
    if (!AppHelper::showShop()) {
        return null;
    }

?>

<li class='sidebar-item'>
    <a data-bs-target='#shop-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-cart-shopping'></i><span class='align-middle'>E-commerce</span>
    </a>

    <ul id='shop-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>


        <li class="sidebar-item<?= ($context === 'shop/razdel') ? ' active' : null ?>">
            <?= Html::a(
                'Разделы /' . Constant::RAZDEL_LABEL,
                '/shop/razdel',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'shop/product') ? ' active' : null ?>">
            <?= Html::a(
                'Продукты /' . Constant::PRODUCT_LABEL,
                '/shop/product',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'shop/brand') ? ' active' : null ?>">
            <?= Html::a(
                Constant::BRAND_LABEL,
                '/shop/brand',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>


        <li class="sidebar-item<?= ($context === 'shop/characteristic') ? ' active' : null ?>">
            <?= Html::a(
                'Характеристики',
                '/shop/characteristic',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>


        <li class="sidebar-item<?= ($context === 'shop/modification') ? ' active' : null ?>">
            <?= Html::a(
                'Модификации',
                '/shop/modification',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'shop/action') ? ' active' : null ?>">
            <?= Html::a(
                'Акции',
                '/shop/action',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'shop/discount') ? ' active' : null ?>">
            <?= Html::a(
                'Скидки',
                '/shop/discount',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

    </ul>

</li>
<!--###### Order ##################################-->
