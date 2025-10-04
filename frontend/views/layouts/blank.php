<?php
    
    use frontend\assets\AppAsset;
    use yii\helpers\HtmlPurifier;
    use yii\helpers\Url;
    use yii\web\View;

    
    /* @var $this View */
    /* @var $imgBackground string */
    /* @var $schemaData array */
    /* @var $content string */
    
    AppAsset::register($this);

    $this->beginContent('@app/views/layouts/main.php');
?>

<div id='home'></div>


<!--==============================
Mobile Menu
============================== -->
<div class='mobile-menu-wrapper'>
    <div class='mobile-menu-area'>
        <div class='mobile-logo'>
            <a href='<?= Url::home() ?>'><img alt='Сила  Возрождения' src='/img/logo.webp'></a>
            <button class='menu-toggle'><i class='fa fa-times'></i></button>
        </div>
        <div class='mobile-menu'>
            <ul>
                        <li>
                            <a class='main-menu_nav-link' href='<?= Url::home(true) ?>#home'>Главная</a>
                        </li>
                        <li>
                            <a class='main-menu_nav-link' href='<?= Url::home(true) ?>#finance'>Финансы</a>
                        </li>
                        <li>
                            <a class='main-menu_nav-link' href='<?= Url::home(true) ?>#team'>Команда</a>
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
                        <img alt='logo' src='/img/logo.webp'>
                    </a>
                    <!-- Logo End -->
                </div>
                <div class='col-auto'>
                    <nav class='main-menu d-none d-lg-inline-block'>
                        <nav class='main-menu d-none d-lg-inline-block'>
                        <ul>
                            <li>
                                <a class="main-menu_nav-link" href='<?=Url::home(true)?>#home'>Главная</a>
                            </li>
                            <li>
                                <a class='main-menu_nav-link' href='<?=Url::home(true)?>#finance'>Финансы</a>
                            </li>
                            <li>
                                <a class='main-menu_nav-link' href='<?=Url::home(true)?>#team'>Команда</a>
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
