<?php

    use core\read\helpers\ImageReader;
    use frontend\extensions\forms\ContactForm;
    use yii\helpers\HtmlPurifier;
    use yii\helpers\Url;
    use yii\web\View;

    /* @var $this View */
    /* @var $model core\edit\entities\Admin\Information */
    /* @var $contactForm ContactForm */
    /* @var $rootPage array */
    /* @var $notes array */
    /* @var $firstPage array */
    /* @var $pageNotes1 array */
    /* @var $secondPage array */
    /* @var $thirdPage array */
    /* @var $pageNotes2 array */
    /* @var $photos array */
    /* @var $team array */
    /* @var $reviewsArray array */
    /* @var $textType int */

    $layoutId = '#frontend_views_site_index';

    $mainPage = $rootPage['model'];

?>

<!-- ================================= Banner Section Start =============================== -->
<section id="home" class='homeCone-banner bg-overlay gradient-overlay overflow-hidden bg-img'
         data-background-image="<?= Url::to('@static', true) . '/cache/14/110-kod-biznesa-rf_col-12.webp'; ?>">

    <h1
            class='text-outline-white writing-mode position-absolute top-50 translate-y-middle-rotate text-white text-opacity-25 text-uppercase margin-left-80 z-index-2'>
        группа компаний</h1>
    <img alt='' class='cross-shape position-absolute top-50 translate-middle-y end-20'
         src='/img/cross-shape.png'>
    <div class='container'>
        <div class='position-relative banner-one__inner'>
            <div class='row'>
                <div class='col-xxl-7 col-md-8'>
                    <div class='alert-area'>
                        <?= $this->render(
                                '@app/views/layouts/tops/_messages',
                        ) ?>
                    </div>
                    <div class='banner-content'>
                        <div class='d-flex align-items-center gap-2'>
                            <img alt='' class='' src='/img/arrow-icon.png'>
                            <h4 class='text-base mb-0'>группа компаний</h4>
                        </div>

                        <h2 class='sec-title fw-semibold style2 text-capitalize text-base mt-4 text-80 mb-4 pb-2'>
                            КОД БИЗНЕСА
                        </h2>
                        <div class="text-xl">
                            <?= $model['description'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================================= Banner Section End =============================== -->

<!-- ================================= First-Block Section Start =============================== -->
<section class='homeC-service space'>
    <div class='container'>
        <!--Alert Area  ##############################################-->
        <div class='section-heading max-w-804 mx-auto text-center mb-60'>
            <div class='d-inline-flex align-items-center gap-2 text-base mb-3'>
                <img alt='' class='' src='/img/arrow-icon-two.png'>
                <h4 class='mb-0 text-base'>группа компаний</h4>
            </div>
            <h2 class='mb-24'>КОД БИЗНЕСА</h2>
            <p class='mb-0'>
                <?= HtmlPurifier::process($notes[0]['description']) ?>
            </p>
        </div>


        <div class='homeC-service-slider'>
            <div class='px-3'>
                <div class='homeC-service-item p-32 radius-12-px border border-neutral-500 bg-neutral-20'>

                    <div class='my-40 d-flex align-items-center justify-content-between gap-1'>
                        <h4 class='text-base text-break'><?= HtmlPurifier::process($notes[1]['name']) ?></h4>
                    </div>
                    <?= HtmlPurifier::process($notes[1]['description']) ?>
                </div>
            </div>
            <div class='px-3'>
                <div class='homeC-service-item p-32 radius-12-px border border-neutral-500 bg-neutral-20'>

                    <div class='my-40 d-flex align-items-center justify-content-between gap-1'>
                        <h4 class='text-base text-break'>
                            <?= HtmlPurifier::process($notes[2]['name']) ?>
                        </h4>
                    </div>
                    <?= HtmlPurifier::process($notes[2]['description']) ?>


                </div>
            </div>
            <div class='px-3'>
                <div class='homeC-service-item p-32 radius-12-px border border-neutral-500 bg-neutral-20'>

                    <div class='my-40 d-flex align-items-center justify-content-between gap-1'>
                        <h4 class='text-base text-break'>
                            <?= HtmlPurifier::process($notes[3]['name']) ?>
                        </h4>
                    </div>
                    <?= HtmlPurifier::process($notes[3]['description']) ?>


                </div>
            </div>
            <div class='px-3'>
                <div class='homeC-service-item p-32 radius-12-px border border-neutral-500 bg-neutral-20'>

                    <div class='my-40 d-flex align-items-center justify-content-between gap-1'>
                        <h4 class='text-base text-break'>
                            <?= HtmlPurifier::process($notes[4]['name']) ?>
                        </h4>
                    </div>
                    <?= HtmlPurifier::process($notes[4]['description']) ?>


                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================================= Create new scratch file from selection Section End =============================== -->


<!-- ================================= Second-Block Us Section Start =============================== -->
<section id='finance' class='homeC-choose-us space bg-neutral-20 position-relative'>
    <div class='container text-center finance-heading'>
        <h3 class='text-base'>Привлечение финансирования</h3>
    </div>
    <h1 class='text-outline-neutral writing-mode position-absolute top-50 translate-middle-y text-white text-opacity-25 text-uppercase margin-right-80 z-index-2 h-100 text-center right-0'>
        группа компаний</h1>

    <div class='container'>
        <div class='row gy-4'>
            <div class='col-xxl-5 col-xl-6'>

                <div class='d-inline-flex align-items-center gap-2 text-base mb-3'>
                    <img alt='' class='' src='/img/arrow-icon-two.png'>
                    <h4 class='mb-0 text-base'>Группа компаний</h4>
                </div>
                <h2 class='mb-24 text-base'>КОД БИЗНЕСА</h2>

                <div class='position-relative'>
                    <div class='col-sm-8'>
                        <img alt='' class='choose-us-thumbs__one radius-16-px w-100'
                             src='/img/finance.jpg'>
                    </div>
                    <div class='col-12 py-2'>
                        <h6 class='p-4'>
                            <?= HtmlPurifier::process($notes[5]['name']) ?>
                        </h6>
                        <?= HtmlPurifier::process($notes[5]['description']) ?>
                    </div>
                </div>
            </div>
            <div class='col-xxl-1 d-xxl-block d-none'></div>


            <div class='col-xl-6'>

                <h3 class='mb-24 text-base text-2xl-center'>Мы предлагаем</h3>
                <ul>
                    <li><h5><?= HtmlPurifier::process($notes[6]['name']) ?></h5>
                        <?= HtmlPurifier::process($notes[6]['description']) ?>

                        <hr>
                    </li>

                    <li><h5><?= HtmlPurifier::process($notes[7]['name']) ?></h5>
                        <?= HtmlPurifier::process($notes[7]['description']) ?>

                        <hr>
                    </li>
                    <li><h5><?= HtmlPurifier::process($notes[8]['name']) ?></h5>
                        <?= HtmlPurifier::process($notes[8]['description']) ?>

                        <hr>
                    </li>
                    <li><h5><?= HtmlPurifier::process($notes[9]['name']) ?></h5>
                        <?= HtmlPurifier::process($notes[9]['description']) ?>

                        <hr>
                    </li>
                </ul>

                <?= HtmlPurifier::process($notes[10]['description']) ?>

            </div>
        </div>
    </div>


</section>
<!-- ================================= Marquee Section Start =============================== -->
<div class='marquee-area'>
    <div class='container-fluid p-0'>
        <div class='slider__marquee style2'>
            <div class='marquee_mode'>
                <div class='item gap-32'>
                    <img alt='img' src='/img/star-icon.png'>
                    <span class='text-120 d-inline-block fw-semibold text-outline-neutral text-uppercase'> группа
                    компаний</span>
                </div>
                <div class='item gap-32'>
                    <img alt='img' src='/img/star-icon.png'>
                    <span class='text-120 d-inline-block fw-semibold text-uppercase text-base'>КОД БИЗНЕСА</span>
                </div>
                <div class='item gap-32'>
                    <img alt='img' src='/img/star-icon.png'>
                    <span class='text-120 d-inline-block fw-semibold text-outline-neutral text-uppercase'>
                        группа компаний
                    </span>
                </div>
                <div class='item gap-32'>
                    <img alt='img' src='/img/star-icon.png'>
                    <span class='text-120 d-inline-block fw-semibold text-uppercase text-base'>КОД БИЗНЕСА </span>
                </div>
            </div>
        </div>
        <div class='slider__marquee mt-32'>
            <div class='marquee_mode2'>
                <div class='item gap-32'>
                    <img alt='img' src='/img/star-icon.png'>
                    <span class='text-120 d-inline-block fw-semibold text-outline-neutral text-uppercase'> КОД БИЗНЕСА</span>
                </div>
                <div class='item gap-32'>
                    <img alt='img' src='/img/star-icon.png'>
                    <span class='text-120 d-inline-block fw-semibold text-uppercase text-base'>группа компаний</span>
                </div>
                <div class='item gap-32'>
                    <img alt='img' src='/img/star-icon.png'>
                    <span class='text-120 d-inline-block fw-semibold text-outline-neutral text-uppercase'> КОД БИЗНЕСА</span>
                </div>
                <div class='item gap-32'>
                    <img alt='img' src='/img/star-icon.png'>
                    <span class='text-120 d-inline-block fw-semibold text-uppercase text-base'>группа
                    компаний </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ================================= Marquee Section End =============================== -->

<!-- ================================= Team Section Start =============================== -->
<section id='team' class='expert-team space py-120 bg-neutral-20 position-relative'>

    <h1 class='text-outline-neutral writing-mode position-absolute top-50 translate-y-middle-rotate text-white text-opacity-25 text-uppercase margin-left-80 z-index-2 h-100 text-center start-0'>
        команда экспертов</h1>

    <div class='container'>
        <div class='d-flex flex-wrap justify-content-between mb-60'>
            <div class='section-heading max-w-804 ms-0 text-start'>
                <div class='d-inline-flex align-items-center gap-2 text-base mb-3'>
                    <img alt='' class='' src='/img/arrow-icon-two.png'>
                    <h4 class='mb-0 text-base'>Наша команда</h4>
                </div>
                <h2 class='mb-0'>Сила совместной работы</h2>
            </div>
            <div class=''>
                <p class='mb-24 max-w-416'>Опыт. Знания. Решения — наши эксперты на вашей стороне.</p>

            </div>
        </div>


        <div class='expert-team-slider'>
<?php foreach ($team as $member): ?>
            <div class='expert-team-item mx-2'>
                <div class='expert-team-item__thumb pb-20 position-relative'>
                    <a class='d-block' href='team-details.html'>
                        <img alt='' class='radius-12-px fit-img'
                             src='<?= ImageReader::getModelImageSource($member, 12) ?>'>
                    </a>
                </div>
                <div class='mt-20-px'>
                    <h4 class='mb-3'>
                        <span class='hover-text-brand'><?= HtmlPurifier::process($member['title']) ?></span>
                    </h4>
                    <span class='text-neutral-500'></span>
                    <hr>
                    <p><?= HtmlPurifier::process($member['description']) ?>.</p></div>
            </div>
<?php endforeach; ?>

        </div>
    </div>
</section>
<!-- ================================= Team Section End =============================== -->

<!-- ================================= Second-Block Us Section End =============================== -->
<div class='container'>
    <!--Alert Area  ##############################################-->
    <div class='section-heading max-w-804 mx-auto text-center mb-60'>
        <div class='mb-60'></div>

        <?= HtmlPurifier::process($model['text']) ?>
    </div>
</div>

<!-- Contact us Area S t a r t -->
<section class='contact-us-area bg-neutral-20' id='contact'>
    <div class='container'>
        <div class='row g-4'>
            <div class='col-lg-6'>
                <div class='section-title'>
                    <h4 class='highlight-title ' data-wow-delay='0.1s'>
                        Свяжитесь с нами
                    </h4>
                    <h4 class='title'>
                        <?= HtmlPurifier::process($notes[11]['name']) ?>
                    </h4>
                    <?= HtmlPurifier::process($notes[11]['description']) ?>

                </div>
            </div>
            <div class='col-lg-6'>
                <div class='contact-point'>
                    <ul class='listing'>
                        <li class='single-point animate__animated animate__fadeInLeft wow'
                            data-wow-delay='0.2s'>
                            <div class='icon'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='10' height='10'
                                     viewBox='0 0 10 10' fill='none'>
                                    <path
                                            d='M4.9999 7.91668C4.91714 7.91716 4.83611 7.89298 4.76715 7.84723C4.69818 7.80148 4.6444 7.73623 4.61266 7.6598C4.58093 7.58336 4.57267 7.49921 4.58895 7.41806C4.60523 7.33692 4.6453 7.26246 4.70407 7.20418L6.9124 5.00001L4.70407 2.79584C4.63581 2.71614 4.60014 2.6136 4.60419 2.50874C4.60824 2.40387 4.65171 2.3044 4.72592 2.23019C4.80012 2.15599 4.8996 2.11252 5.00446 2.10847C5.10933 2.10441 5.21186 2.14008 5.29157 2.20834L7.79157 4.70834C7.86917 4.78641 7.91273 4.89202 7.91273 5.00209C7.91273 5.11217 7.86917 5.21778 7.79157 5.29585L5.29157 7.79585C5.21396 7.87282 5.10921 7.91622 4.9999 7.91668Z'
                                            fill='white'/>
                                    <path
                                            d='M2.4999 7.91658C2.41714 7.91706 2.33611 7.89289 2.26714 7.84714C2.19818 7.80139 2.1444 7.73613 2.11266 7.6597C2.08093 7.58326 2.07267 7.49911 2.08895 7.41796C2.10523 7.33682 2.1453 7.26236 2.20407 7.20408L4.4124 4.99991L2.20407 2.79575C2.12561 2.71729 2.08153 2.61087 2.08153 2.49991C2.08153 2.38896 2.12561 2.28254 2.20407 2.20408C2.28253 2.12562 2.38894 2.08154 2.4999 2.08154C2.61086 2.08154 2.71727 2.12562 2.79573 2.20408L5.29573 4.70408C5.37334 4.78215 5.4169 4.88775 5.4169 4.99783C5.4169 5.10791 5.37334 5.21351 5.29573 5.29158L2.79573 7.79158C2.75714 7.83095 2.71113 7.86227 2.66034 7.88373C2.60956 7.90519 2.55503 7.91635 2.4999 7.91658Z'
                                            fill='white'/>
                                </svg>
                            </div>
                            <h4 class='title'>Уникальная траектория сотрудничества</h4>

                        </li>
                        <li class='single-point animate__animated animate__fadeInLeft wow'
                            data-wow-delay='0.2s'>
                            <div class='icon'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='10' height='10'
                                     viewBox='0 0 10 10' fill='none'>
                                    <path
                                            d='M4.9999 7.91668C4.91714 7.91716 4.83611 7.89298 4.76715 7.84723C4.69818 7.80148 4.6444 7.73623 4.61266 7.6598C4.58093 7.58336 4.57267 7.49921 4.58895 7.41806C4.60523 7.33692 4.6453 7.26246 4.70407 7.20418L6.9124 5.00001L4.70407 2.79584C4.63581 2.71614 4.60014 2.6136 4.60419 2.50874C4.60824 2.40387 4.65171 2.3044 4.72592 2.23019C4.80012 2.15599 4.8996 2.11252 5.00446 2.10847C5.10933 2.10441 5.21186 2.14008 5.29157 2.20834L7.79157 4.70834C7.86917 4.78641 7.91273 4.89202 7.91273 5.00209C7.91273 5.11217 7.86917 5.21778 7.79157 5.29585L5.29157 7.79585C5.21396 7.87282 5.10921 7.91622 4.9999 7.91668Z'
                                            fill='white'/>
                                    <path
                                            d='M2.4999 7.91658C2.41714 7.91706 2.33611 7.89289 2.26714 7.84714C2.19818 7.80139 2.1444 7.73613 2.11266 7.6597C2.08093 7.58326 2.07267 7.49911 2.08895 7.41796C2.10523 7.33682 2.1453 7.26236 2.20407 7.20408L4.4124 4.99991L2.20407 2.79575C2.12561 2.71729 2.08153 2.61087 2.08153 2.49991C2.08153 2.38896 2.12561 2.28254 2.20407 2.20408C2.28253 2.12562 2.38894 2.08154 2.4999 2.08154C2.61086 2.08154 2.71727 2.12562 2.79573 2.20408L5.29573 4.70408C5.37334 4.78215 5.4169 4.88775 5.4169 4.99783C5.4169 5.10791 5.37334 5.21351 5.29573 5.29158L2.79573 7.79158C2.75714 7.83095 2.71113 7.86227 2.66034 7.88373C2.60956 7.90519 2.55503 7.91635 2.4999 7.91658Z'
                                            fill='white'/>
                                </svg>
                            </div>
                            <h4 class='title'>Своевременная поддержка</h4>
                        </li>
                    </ul>
                </div>
                <div class='contact-details'>
                    <div class='contact-card'>
                        <div class='icon'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'
                                 fill='none'>
                                <path
                                        d='M31.6126 24.7786C30.7937 23.9259 29.8059 23.4701 28.7591 23.4701C27.7207 23.4701 26.7245 23.9175 25.8718 24.7702L23.2041 27.4295C22.9846 27.3113 22.7651 27.2015 22.554 27.0918C22.2501 26.9398 21.9631 26.7963 21.7182 26.6444C19.2193 25.0572 16.9483 22.9888 14.7702 20.3126C13.7149 18.9788 13.0058 17.8559 12.4908 16.7162C13.1831 16.0831 13.8247 15.4246 14.4494 14.7914C14.6858 14.555 14.9222 14.3102 15.1586 14.0738C16.9315 12.3009 16.9315 10.0046 15.1586 8.23175L12.8538 5.92701C12.5921 5.6653 12.322 5.39515 12.0687 5.125C11.5622 4.60158 11.0303 4.06127 10.4816 3.55473C9.66266 2.74428 8.68335 2.31372 7.6534 2.31372C6.62344 2.31372 5.62725 2.74428 4.78302 3.55473C4.77458 3.56318 4.77458 3.56318 4.76614 3.57162L1.89576 6.46732C0.815154 7.54793 0.198868 8.86493 0.0637914 10.393C-0.138823 12.8581 0.587212 15.1544 1.1444 16.6571C2.51205 20.3464 4.55508 23.7655 7.60274 27.4295C11.3005 31.8448 15.7495 35.3315 20.8318 37.7882C22.7735 38.7084 25.3653 39.7974 28.261 39.9831C28.4383 39.9916 28.624 40 28.7928 40C30.743 40 32.3808 39.2993 33.664 37.9063C33.6725 37.8895 33.6894 37.881 33.6978 37.8641C34.1368 37.3323 34.6433 36.8511 35.1752 36.3361C35.5382 35.99 35.9097 35.6269 36.2727 35.247C37.1085 34.3775 37.5475 33.3644 37.5475 32.326C37.5475 31.2792 37.1 30.2745 36.2474 29.4303L31.6126 24.7786ZM34.6349 33.6683C34.6265 33.6683 34.6265 33.6768 34.6349 33.6683C34.3057 34.0229 33.968 34.3437 33.6049 34.6983C33.0562 35.2217 32.499 35.7705 31.9756 36.3867C31.1229 37.2985 30.1183 37.7291 28.8013 37.7291C28.6747 37.7291 28.5396 37.7291 28.4129 37.7206C25.9056 37.5602 23.5755 36.5809 21.828 35.7451C17.0496 33.4319 12.8538 30.1479 9.36718 25.9859C6.48836 22.5161 4.56352 19.308 3.28874 15.8636C2.50361 13.7614 2.21657 12.1236 2.34321 10.5787C2.42763 9.59096 2.80753 8.77206 3.50824 8.07135L6.38705 5.19254C6.80073 4.80419 7.23972 4.59313 7.67028 4.59313C8.20214 4.59313 8.6327 4.91394 8.90285 5.18409C8.91129 5.19254 8.91974 5.20098 8.92818 5.20942C9.44316 5.69063 9.93281 6.18872 10.4478 6.72059C10.7095 6.99074 10.9797 7.26089 11.2498 7.53949L13.5545 9.84423C14.4494 10.7391 14.4494 11.5665 13.5545 12.4613C13.3097 12.7062 13.0733 12.951 12.8285 13.1874C12.1194 13.9134 11.444 14.5888 10.7095 15.2473C10.6926 15.2642 10.6757 15.2726 10.6673 15.2895C9.94125 16.0155 10.0763 16.7247 10.2283 17.2059C10.2367 17.2312 10.2452 17.2565 10.2536 17.2819C10.853 18.7339 11.6972 20.1016 12.9805 21.7309L12.9889 21.7394C15.319 24.6098 17.7757 26.847 20.4857 28.5607C20.8318 28.7802 21.1864 28.9575 21.5241 29.1264C21.828 29.2783 22.115 29.4219 22.3598 29.5738C22.3936 29.5907 22.4274 29.616 22.4611 29.6329C22.7482 29.7764 23.0183 29.844 23.2969 29.844C23.9976 29.844 24.4366 29.405 24.5802 29.2615L27.4674 26.3742C27.7544 26.0872 28.2103 25.741 28.7422 25.741C29.2656 25.741 29.6962 26.0703 29.9579 26.3573C29.9663 26.3658 29.9663 26.3658 29.9748 26.3742L34.6265 31.0259C35.496 31.887 35.496 32.7734 34.6349 33.6683Z'
                                        fill='white'/>
                                <path
                                        d='M21.6166 9.51505C23.8285 9.88651 25.8377 10.9334 27.4418 12.5374C29.0458 14.1414 30.0842 16.1507 30.4641 18.3626C30.557 18.9197 31.0382 19.3081 31.5869 19.3081C31.6545 19.3081 31.7136 19.2996 31.7811 19.2912C32.4058 19.1899 32.8195 18.5989 32.7182 17.9742C32.2623 15.298 30.996 12.8582 29.0627 10.9249C27.1294 8.99163 24.6896 7.72529 22.0134 7.26941C21.3887 7.1681 20.8061 7.58177 20.6964 8.19806C20.5866 8.81435 20.9919 9.41375 21.6166 9.51505Z'
                                        fill='white'/>
                                <path
                                        d='M39.954 17.6448C39.2026 13.238 37.1258 9.2279 33.9346 6.03672C30.7434 2.84554 26.7334 0.768738 22.3265 0.0173754C21.7102 -0.0923741 21.1277 0.32974 21.0179 0.946026C20.9166 1.57075 21.3303 2.15327 21.955 2.26302C25.8891 2.92996 29.4771 4.7957 32.3306 7.64075C35.1841 10.4942 37.0414 14.0822 37.7083 18.0163C37.8012 18.5735 38.2824 18.9618 38.8311 18.9618C38.8987 18.9618 38.9578 18.9534 39.0253 18.945C39.6416 18.8521 40.0637 18.2611 39.954 17.6448Z'
                                        fill='white'/>

                            </svg>
                        </div>
                        <div class='content'>
                            <a href="https://t.me/KodBiznesa_1">https://t.me/KodBiznesa_1</a><br>
                            <a href="https://t.me/chifrovanie">https://t.me/chifrovanie</a><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
