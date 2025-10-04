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
