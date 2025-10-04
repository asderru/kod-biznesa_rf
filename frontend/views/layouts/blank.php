<?php
    
    use frontend\assets\AppAsset;
    use yii\helpers\Url;
    use yii\web\View;
    
    
    /* @var $this View */
    /* @var $imgBackground string */
    /* @var $schemaData array */
    /* @var $content string */
    
    
    AppAsset::register($this);
    
$model = $this->params['siteModel'];
    
    $contactAddress = json_decode($model['contact_address'], true);
    $contactPhones  = json_decode($model['contact_phones'], true);
    
    // Получаем отдельные значения из адреса
    $postalCode      = $contactAddress['postalCode'];
    $streetAddress   = $contactAddress['streetAddress'];
    $addressCountry  = $contactAddress['addressCountry'];
    $addressLocality = $contactAddress['addressLocality'];
    
    $this->beginContent('@app/views/layouts/main.php');
?>

<div id='home'></div>
<!--==============================
Sidemenu
============================== -->
<div class='sidemenu-wrapper sidemenu-info '>
    <div class='sidemenu-content'>
        <button class='closeButton sideMenuCls'><i class='fas fa-times'></i></button>
        <div class='widget  '>
            <div class='th-widget-about'>
                <div class='about-logo'>
                    <a href='<?= Url::home() ?>'><img alt='sv-partner' src='/assets/img/logo.png'></a>
                </div>
                <p class='about-text'></p>
            </div>
        </div>
        <div class='side-info mb-30'>
            <div class='contact-list mb-20'>
                <h4>Адрес офиса</h4>
                <p><?= $postalCode ?>, <?= $addressLocality ?></p>
                <p><?= $streetAddress ?></p>
                <p><?= $addressCountry ?></p>
            </div>

            <div class='contact-list mb-20'>
                <h4>Номер телефона</h4>
                <?php
                    foreach ($contactPhones as $phone): ?>
                        <p class='mb-0'><?= $phone['label'] ?>: <?= $phone['number'] ?></p>
                    <?php
                    endforeach; ?>
            </div>

            <div class='contact-list mb-20'>
                <h4>Адрес электронной почты</h4>
                <?php
                    if (!empty($model['contact_email'])): ?>
                        <p class='mb-0'><?= $model['contact_email'] ?></p>
                    <?php
                    endif; ?>
            </div>

        </div>
    </div>
</div>

<!--==============================
Mobile Menu
============================== -->
<div class='mobile-menu-wrapper'>
    <div class='mobile-menu-area'>
        <div class='mobile-logo'>
            <a href='<?= Url::home() ?>'><img alt='Сила  Возрождения' src='/assets/img/logo.png'></a>
            <button class='menu-toggle'><i class='fa fa-times'></i></button>
        </div>
        <div class='mobile-menu'>
            <ul>
                        <li>
                            <a class='main-menu_nav-link' href='<?= Url::home(true) ?>#home'>Благотворительный
                                Фонд</a>
                        </li>
                        <li>
                            <a class='main-menu_nav-link' href='<?= Url::home(true) ?>#finance'>Финансы</a>
                        </li>
                        <li>
                            <a class='main-menu_nav-link' href='<?= Url::home(true) ?>#events'>Мероприятия</a>
                        </li>
                        <li>
                            <a class='main-menu_nav-link' href='<?= Url::home(true) ?>#feedback'>Обратная связь</a>
                        </li>
                        <li>
                            <a class='main-menu_nav-link' href='<?= Url::home(true) ?>#contact'>Контакты</a>
                        </li>
            </ul>
        </div>

    </div>
</div>
<!--==============================
Header Area Start
==============================-->
<header class='my-header top-0 py-lg-0 py-3 header-one-wrapper nav-header header-layout4 border-bottom border-white-25'>
    <div class='sticky-wrapper'>
        <!-- Main Menu Area -->
        <div class='container custom-container--xl'>
            <div class='row align-items-center justify-content-between'>
                <div class='col-auto'>
                    <!-- Logo Start -->
                    <a class='' href='<?= Url::home() ?>'>
                        <img alt='logo' src='/assets/img/logo.png'>
                    </a>
                    <!-- Logo End -->
                </div>
                <div class='col-auto'>
                    <nav class='main-menu d-none d-lg-inline-block'>
                        <nav class='main-menu d-none d-lg-inline-block'>
                        <ul>
                            <li>
                                <a class="main-menu_nav-link" href='<?=Url::home(true)?>#home'>Благотворительный
                                    Фонд</a>
                            </li>
                            <li>
                                <a class='main-menu_nav-link' href='<?=Url::home(true)?>#finance'>Финансы</a>
                            </li>
                            <li>
                                <a class='main-menu_nav-link' href='<?=Url::home(true)?>#events'>Мероприятия</a>
                            </li>
                            <li>
                                <a class='main-menu_nav-link' href='<?=Url::home(true)?>#feedback'>Обратная связь</a>
                            </li>
                            <li>
                                <a class='main-menu_nav-link' href='<?=Url::home(true)?>#contact'>Контакты</a>
                            </li>
                        </ul>
                        </nav>
                    </nav>
                    <div class='navbar-right d-inline-flex d-lg-none'>
                        <button type='button' class='menu-toggle icon-btn'><i class='fas fa-bars'></i></button>
                    </div>
                    
                </div>
                <div class='col-auto d-lg-block d-none'>

                </div>
            </div>
        </div>
    </div>
</header>
<!--==============================
Header Area End
==============================-->


<?= $content ?>

<!-- ================================= Footer Section Start =============================== -->
<section class='homeCone-footer bg-img bg-overlay style-three position-relative z-index-3'
         data-background-image='/assets/img/HomeCone/footer-bg.png'
         id='contact'>

    <ul class='animation-line d-none d-md-flex justify-content-between'>
        <li class='animation-line__item'></li>
        <li class='animation-line__item'></li>
        <li class='animation-line__item'></li>
        <li class='animation-line__item'></li>
    </ul>

    <div class='container'>
        <div class=' space '>
            <div class='row gy-5'>
                <div class='col-lg-3'>
                    <div class=''>
                        <a class='d-inline-block mb-4' href='/'>
                            <img alt='' src='/assets/img/logo-white-2.png'>
                        </a>
                    </div>
                </div>
                <div class='col-lg-3'>
                    <div class='footer-item'>
                        <h5 class='title text-white mb-4'> Контактный email</h5>
                        <ul class='list-unstyled ps-0 d-flex flex-md-column gap-2'>
                            <li>
                                <a class='hover-action text-neutral-30 hover-text-base-two  text-lg'
                                   href='#'>info@sv-partner.ru</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class='col-lg-3'>
                    <div class='footer-item'>
                        <h5 class='title text-white mb-4'>Контактный телефон </h5>
                        <div class='d-flex flex-column gap-3'>
                            <div class='d-flex align-items-center gap-3 text-lg'>
                                <a class='text-neutral-30 hover-text-base-two' href='tel:316-555-0116'>+7 915
                                    185-24-34</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class='bottom-footer py-32-px d-flex align-items-center justify-content-between flex-wrap'>
            <p class='text-neutral-20'>Copyright &copy; 2025 <span
                        class='fw-semibold text-base-two'>Сила  Возрождения </span> . All rights reserved.</p>
        </div>
    </div>
</section>
<!-- ================================= Footer Section End =============================== -->

<!-- Scroll To Top -->
<div class='scroll-top'>
    <svg class='progress-circle svg-content' height='100%' viewBox='-1 -1 102 102' width='100%'>
        <path d='M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98'
              style='transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;'>
        </path>
    </svg>
</div>


<?php
    $this->endContent() ?>
