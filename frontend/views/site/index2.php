<?php

    use core\helpers\ImageHelper;
    use core\helpers\PrintHelper;
    use frontend\extensions\forms\ContactForm;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    use yii\web\View;
    use yii\widgets\MaskedInput;

    /* @var $this View */
    /* @var $model core\edit\entities\Admin\Information */
    /* @var $contactForm ContactForm */
    /* @var $rootPage array */
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

    $contactAddress = json_decode($model['contact_address'], true);
    $contactPhones  = json_decode($model['contact_phones'], true);

    // Получаем отдельные значения из адреса
    $postalCode      = $contactAddress['postalCode'];
    $streetAddress   = $contactAddress['streetAddress'];
    $addressCountry  = $contactAddress['addressCountry'];
    $addressLocality = $contactAddress['addressLocality'];
    $mainPage        = $rootPage['model'];

?>


<!-- ================================= Banner Section Start =============================== -->
<section class='homeCone-banner bg-overlay gradient-overlay overflow-hidden bg-img'
         data-background-image="<?= Url::to('@static', true) . '/cache/site/110-sv-partner-ru_col-12.webp'; ?>">

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
                            <h3>Наши цели:</h3>
                            <ul>
                                <li>развитие цифровой экономики России и культуры предпринимательства;</li>
                                <li>создание современных решений на основе ЦФА и искусственного интеллекта;</li>
                                <li>поддержка бизнеса на всех этапах роста и привлечения инвестиций;</li>
                                <li>объединение людей и технологий для устойчивого развития регионов и страны.</li>
                            </ul>
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
                <?= $firstPage['description'] ?>
            </p>
        </div>

        <div class='homeC-service-slider'>
            <div class='px-3'>
                <div class='homeC-service-item p-32 radius-12-px border border-neutral-500 bg-neutral-20'>

                    <div class='my-40 d-flex align-items-center justify-content-between gap-1'>
                        <h1 class='text-base'>ЦФА</h1>
                    </div>

                    <p class='text-xl color-dark'>Выводим бизнес на рынок Цифровых финансовых активов.<br>
                        Готовим компании к привлечению инвестиций: от инвестиционного аудита и упаковки до выпуска
                        токенов и поступления средств на расчётный счёт.</p>
                </div>
            </div>
            <div class='px-3'>
                <div class='homeC-service-item p-32 radius-12-px border border-neutral-500 bg-neutral-20'>

                    <div class='my-40 d-flex align-items-center justify-content-between gap-1'>
                        <h1 class='text-base'>Изготовление нейроконсультантов</h1>
                    </div>

                    <p class='text-xl color-dark'>Создаём искусственных ассистентов для бизнеса.<br>
                        От простых решений (50 000 ₽, 2 недели) до комплексных систем (от 250 000 ₽, 1 месяц). Работаем
                        в рамках законодательства РФ, обеспечиваем защиту персональных данных и абонентское
                        сопровождение.</p>
                </div>
            </div>
            <div class='px-3'>
                <div class='homeC-service-item p-32 radius-12-px border border-neutral-500 bg-neutral-20'>

                    <div class='my-40 d-flex align-items-center justify-content-between gap-1'>
                        <h1 class='text-base'>Партнёрам</h1>
                    </div>

                    <p class='text-xl color-dark'>Предлагаем партнёрскую программу: <br>
                        рекомендуйте бизнес, помогайте предпринимателям привлекать инвестиции и внедрять ИИ‑решения —
                        получайте вознаграждение. Все инструменты, скрипты и поддержка от нас.</p>
                </div>
            </div>
            <div class='px-3'>
                <div class='homeC-service-item p-32 radius-12-px border border-neutral-500 bg-neutral-20'>

                    <div class='my-40 d-flex align-items-center justify-content-between gap-1'>
                        <h1 class='text-base'>Группа в Telegram</h1>
                    </div>

                    <p class='text-xl color-dark'>Присоединяйтесь к нашему сообществу в Telegram:<br>
                        актуальные новости, кейсы, материалы по ЦФА и искусственному интеллекту, прямое общение с
                        экспертами и ответы на вопросы.</p>
                </div>
            </div>
        </div>
        <div class='slick-arrows d-flex align-items-center gap-3 mt-40 justify-content-center'>
            <button type='button' id='homeC-service-prev'
                    class='w-48-px h-48-px radius-8-px d-flex justify-content-center align-items-center border border-base text-base text-lg hover-bg-base bg-transparent hover-text-white position-relative top-0 end-0 start-0 mt-0'>
                <svg class='icon-purple' xmlns='http://www.w3.org/2000/svg' width='16' height='16'
                     viewBox='0 0 448 512'>
                    <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d='M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z'/>
                </svg>
            </button>
            <button type='button' id='homeC-service-next'
                    class='w-48-px h-48-px radius-8-px d-flex justify-content-center align-items-center border border-base text-base text-lg hover-bg-base bg-transparent hover-text-white position-relative top-0 end-0 start-0 mt-0'>
                <svg class='icon-purple' xmlns='http://www.w3.org/2000/svg' width='16' height='16'
                     viewBox='0 0 448 512'>
                    <path d='M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z'/>
                </svg>
            </button>
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
                    <div class="col-12 py-2">
                        <h6 class="p-4">Цифровые финансовые активы (ЦФА)</h6>
                        <h4>Группа компаний «КОД БИЗНЕСА»</h4>
                        <p>Мы помогаем российским компаниям (юрлицам и ИП) привлекать частные инвестиции с
                            использованием цифровых финансовых активов — законного инструмента, регулируемого ЦБ РФ.</p>
                        <ul>
                            <li> • Инвестиционный аудит и подготовка бизнеса к выпуску ЦФА;</li>
                            <li>Разработка и регистрация токенов на базе ОИС (операторов информационных систем);</li>
                            <li>Полный пакет документов: юридические, финансовые, стратегические;</li>
                            <li>Подготовка и защита токеномики проекта;</li>
                            <li>Маркетинговая поддержка для выхода на рынок;</li>
                            <li>Привлечение инвестиций от частных инвесторов через ЦФА;</li>
                            <li>Сопровождение до поступления средств на расчётный счёт.</li>
                        </ul>
                    </div>
                    <hr>
                    <p>
                        ⚡️ Мы работаем в формате «одного окна» — от диагностики проекта до фактического привлечения
                        капитала.</p>
                    <p>У нас есть партнёрская программа, которая позволяет зарабатывать, рекомендуя наши услуги
                        компаниям. Для партнёров мы предоставляем материалы, скрипты и поддержку.</p>
                    <p>📩 При заинтересованности напишите нам через форму обратной связи.
                    </p>
                </div>
            </div>
            <div class='col-xxl-1 d-xxl-block d-none'></div>


            <div class='col-xl-6'>

                <h3 class='mb-24 text-base text-2xl-center'>Мы предлагаем</h3>
                <ul>
                    <li>1. <strong>ЦФА‑аудит и скоринг проекта</strong>
                        <br>
                        Первичная диагностика компании, инвестиционный аудит и скоринг (3 рабочих дня).<br>
                        Результат — понимание, насколько проект готов к привлечению инвестиций через ЦФА.
                        <hr>
                    </li>

                    <li>2. <strong>Подготовка бизнеса к выпуску ЦФА</strong>
                        Разработка токеномики, бизнес‑плана, финансовой модели, юридического пакета документов.<br>
                        Срок подготовки: 1,5–3 месяца.<br>
                        Стоимость: 500 000 ₽ (фиксированная оплата по договору).
                        <hr>
                    </li>
                    <li>3. <strong>Выпуск цифровых финансовых активов (ЦФА)</strong>
                        <br>
                        Осуществляем выпуск токенов через аккредитованных операторов информационных систем (ОИС).<br>
                        Полностью сопровождаем процесс до размещения токенов.<br>
                        После успешного привлечения инвестиций — комиссия 5% от суммы.
                        <hr>
                    </li>
                    <li>4. <strong>Привлечение инвестиций через ЦФА</strong>
                        <br> Привлекаем частных инвесторов (суммы от 20 млн ₽ до 1 млрд ₽).<br>
                        Деньги поступают на расчётный счёт компании.<br>
                        Сопровождаем бизнес на всём цикле — от выпуска токенов до поступления средств.
                    </li>
                </ul>

            </div>
        </div>
    </div>


</section>
<!-- ================================= Second-Block Us Section End =============================== -->


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

<!-- ================================= Events Section Start =============================== -->

<!-- Case Studies Area S t a r t -->
<section class='case-studies-area' id='events'>
    <div class='container'>
        <div class='row'>
            <div class='col-xl-12'>
                <div class='section-title text-center'>
                    <h4 class='highlight-title'>
                        Изготовление нейроконсультантов</h4>
                    <p>Группа компаний «КОД БИЗНЕСА» создаёт интеллектуальных ассистентов для бизнеса — 
                        <strong>нейроконсультантов</strong>, которые помогают компаниям работать эффективнее.</p>
                </div>
            </div>
        </div>
        <div class='row g-4'>
            <div class='col-lg-1 d-none d-lg-block'></div>
            <div class='col-lg-6 col-md-6 col-sm-12 color-dark'>
                <h4>📌 Возможности нейроконсультантов</h4>
                <p>
                    • 📊 Аналитика: рынок, конкуренты, финансовые модели.<br>
                    • 👥 Работа с клиентами 24/7 (чат‑боты, голосовые ассистенты).<br>
                    • ⚙️ Автоматизация процессов: HR, документооборот, обучение.<br>
                    • 💡 Маркетинг и продажи: контент, персонализация, сопровождение сделок.
                </p>
                <h4>🔒 Безопасность и законность</h4>
                <p>
                    Мы уделяем особое внимание защите данных (при «Расширенном пакете»):</p>
                <ul>
                    <li>Работаем строго в рамках законодательства РФ (152‑ФЗ «О персональных данных»).</li>
                    <li>Используем только российские серверы и дата‑центры.</li>
                    <li>Обеспечиваем полную защиту и сохранность информации.</li>
                </ul>
                <p>
                    Ваш бизнес получает не только инновационный инструмент, но и уверенность в безопасности и
                    долгосрочной надёжности решений.</p>
            </div>
            <div class='col-lg-1 d-none d-lg-block'></div>
            <div class='col-lg-4 col-md-6 col-sm-12'>
                <h4>💰 Стоимость и сроки</h4>
                <ul>
                    <li><strong></strong>Базовый пакет — от <strong>50 000 ₽.</strong><br>
                        ⏳ Срок: до 2 недель.<br>
                        Один нейроконсультант под конкретную задачу.
                    </li>
                    <li><strong>Расширенный пакет</strong>  — от <strong>250 000 ₽.</strong><br>
                        ⏳ Срок: от 1 месяца.<br>
                        Комплексная система под задачи бизнеса (продажи, HR, маркетинг, аналитика).<br>
                        Включает абонентское сопровождение и поддержку.
                    </li>
                </ul>
                <h4>🚀 Для чего это бизнесу</h4>
                <ul>
                    <li>Снижение издержек.
                    </li>
                    <li>Ускорение процессов.
                    </li>
                    <li>Масштабирование бизнеса.
                    </li>
                    <li>Повышение эффективности и качества обслуживания клиентов.
                    </li>
                </ul>

            </div>
        </div>
        <div class='col-xl-12'>
            <div class='section-title text-center'>
                <h4 class='highlight-title'>
                    ✅ Итог</h4>
                <p><strong>Нейроконсультанты — это не мода, а новая реальность.</strong><br>
                    Сегодня они становятся таким же обязательным инструментом, как когда‑то сайт или CRM.<br>
                    📩 Хотите обсудить, каким может быть ваш нейроконсультант?<br>
                    Напишите нам через форму обратной связи.
                </p>
            </div>
        </div>
        <div class='gallery-block'>
            <div class='row'>
                <?php
                    foreach ($photos as $photo) { ?>

                        <div class='col-sm-6 col-lg-4 col-xl-3 mb-4'>
                            <div class='card'>
                                <a href='<?= ImageHelper::getModelImageSource($photo, 12) ?>' class='gallery-item'>
                                    <img alt='' src='<?= ImageHelper::getModelImageSource($photo, 3) ?>'>
                                </a>
                            </div>
                        </div>

                        <?php
                    } ?>
            </div>
        </div>

    </div>
</section>
<!-- End Case Studies Area -->

<!-- ================================= Team Section Start =============================== -->
<section class='expert-team space py-120 bg-neutral-20 position-relative'>

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
                <div class='slick-arrows d-flex align-items-center gap-3 mt-40 justify-content-start'>
                    <button class='w-48-px h-48-px radius-8-px d-flex justify-content-center align-items-center border border-base text-base text-lg hover-bg-base bg-transparent hover-text-white position-relative top-0 end-0 start-0 mt-0 slick-arrow'
                            id='expert-team-prev'
                            type='button'>
                        <svg class='icon-purple' height='16' viewBox='0 0 448 512' width='16'
                             xmlns='http://www.w3.org/2000/svg'>
                            <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path d='M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z'/>
                        </svg>
                    </button>
                    <button class='w-48-px h-48-px radius-8-px d-flex justify-content-center align-items-center border border-base text-base text-lg hover-bg-base bg-transparent hover-text-white position-relative top-0 end-0 start-0 mt-0 slick-arrow'
                            id='expert-team-next'
                            type='button'>
                        <svg class='icon-purple' height='16' viewBox='0 0 448 512' width='16'
                             xmlns='http://www.w3.org/2000/svg'>
                            <path d='M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z'/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>


        <div class='expert-team-slider'>

            <?php
                $i = 0;
                foreach ($team as $member):
                    ?>
                    <div class='expert-team-item mx-2'>
                        <div class='expert-team-item__thumb pb-20 position-relative'>
                            <a class='d-block' href='team-details.html'>
                                <img alt='' class='radius-12-px fit-img'
                                     src='<?= ImageHelper::getModelImageSource($member, 12) ?>'>
                            </a>
                        </div>
                        <div class='mt-20-px'>
                            <h4 class='mb-3'>
                                <span class='hover-text-brand'><?= $member['title'] ?></span>
                            </h4>
                            <span class='text-neutral-500'><?= $member['contact'] ?></span>
                            <hr>
                            <?= $member['description'] ?>
                        </div>
                    </div>

                <?php
                endforeach;
            ?>


            <?php
                $i = 0;
                foreach ($team as $member):
                    ?>
                    <div class='expert-team-item mx-2'>
                        <div class='expert-team-item__thumb pb-20 position-relative'>
                            <a class='d-block' href='team-details.html'>
                                <img alt='' class='radius-12-px fit-img'
                                     src='<?= ImageHelper::getModelImageSource($member, 12) ?>'>
                            </a>
                        </div>
                        <div class='mt-20-px'>
                            <h4 class='mb-3'>
                                <span class='hover-text-brand'><?= $member['title'] ?></span>
                            </h4>
                            <span class='text-neutral-500'><?= $member['contact'] ?></span>
                            <hr>
                            <?= $member['description'] ?>
                        </div>
                    </div>

                <?php
                endforeach;
            ?>

        </div>
    </div>
</section>
<!-- ================================= Team Section End =============================== -->

<!-- ================================= Testimonials Section Start =============================== -->

<section class='homeC-testimonial space overflow-hidden position-relative bg-neutral-20'>

    <h1 class='text-outline-neutral writing-mode position-absolute top-50 translate-y-middle-rotate text-white text-opacity-25 text-uppercase margin-left-80 z-index-2 h-100 text-center start-0'>
        Партнеры пишут</h1>

    <div class='container'>
        <div class='section-heading max-w-804 mx-auto text-center mb-60'>
            <div class='d-inline-flex align-items-center gap-2 text-base mb-3'>
                <img alt='' src='/img/arrow-icon-two.png'>
                <h4 class='mb-0 text-base'>Что говорят наши партнеры</h4>
            </div>
            <h2 class='mb-24'>Наши истории успеха</h2>
            <p class='mb-0'>'Наши партнеры — наше главное вдохновение! Здесь вы найдете реальные истории успеха,
                подтверждающие качество наших услуг и доверие, которое мы заслужили.'</p>

        </div>

        <div class='row gy-4 align-items-center'>

            <div class='col-lg-7'>
                <div class='position-relative'>

                    <div class='homeC-testimonial-slider'>
                        <?php
                            foreach ($reviewsArray as $review):
                                ?>
                                <div class='homeC-testimonial-item'>
                                    <p class='text-neutral-900 text-2xl fw-medium'><?= $review['model']['text'] ?></p>
                                    <div class='d-flex align-items-center gap-4'>
                                        <img alt='' class='w-60-px h-60-px rounded-circle '
                                             src='<?= ImageHelper::getModelImageSource($review['person'], 3) ?>'>
                                        <div class=''>
                                            <h6 class='text-20 mb-10-px'><?= $review['person']['first_name'] ?> <?= $review['person']['last_name'] ?> </h6>
                                            <span class='text-neutral-900'><?= $review['person']['position'] ?>.</span>
                                        </div>
                                    </div>

                                </div>

                            <?php
                            endforeach;
                        ?>
                    </div>

                    <div class='slick-arrows position-absolute end-0 bottom-0 d-flex align-items-center gap-3 justify-content-start'>
                        <button class='w-48-px h-48-px color-dark radius-8-px d-flex justify-content-center align-items-center border border-base text-base text-lg hover-bg-base bg-white hover-text-white position-relative top-0 end-0 start-0 mt-0 slick-arrow'
                                id='homeC-testimonial-prev'
                                type='button'>
                            <svg class='icon-purple' height='16' viewBox='0 0 448 512' width='16'
                                 xmlns='http://www.w3.org/2000/svg'>
                                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                <path d='M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z'/>
                            </svg>
                        </button>
                        <button class='w-48-px h-48-px radius-8-px d-flex justify-content-center align-items-center border border-base text-base text-lg hover-bg-base bg-white hover-text-white position-relative top-0 end-0 start-0 mt-0 slick-arrow'
                                id='homeC-testimonial-next'
                                type='button'>
                            <svg class='icon-purple' height='16' viewBox='0 0 448 512' width='16'
                                 xmlns='http://www.w3.org/2000/svg'>
                                <path d='M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z'/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class='col-lg-5'>
                <div class='homeC-testimonial__thumb circle-border position-relative ps-lg-5'>
                    <div class='position-relative max-w-306 max-h-306'>
                        <img alt='' class='fit-img rounded-circle'
                             src='/img/testimonial-image.png'>
                        <span class='w-72-px h-72-px border border-white rounded-circle bg-base text-32 text-base-two d-inline-block d-flex justify-content-center align-items-center position-absolute top-50 end-0 translate-middle-y end--36'>
                            <svg class='white-icon' viewBox='0 0 448 512' xmlns='http://www.w3.org/2000/svg'>
                                <path d='M448 296c0 66.3-53.7 120-120 120l-8 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l8 0c30.9 0 56-25.1 56-56l0-8-64 0c-35.3 0-64-28.7-64-64l0-64c0-35.3 28.7-64 64-64l64 0c35.3 0 64 28.7 64 64l0 32 0 32 0 72zm-256 0c0 66.3-53.7 120-120 120l-8 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l8 0c30.9 0 56-25.1 56-56l0-8-64 0c-35.3 0-64-28.7-64-64l0-64c0-35.3 28.7-64 64-64l64 0c35.3 0 64 28.7 64 64l0 32 0 32 0 72z'/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================= Feedback Section End =============================== -->

<!-- Contact us Area S t a r t -->
<section class='contact-us-area' id="feedback">
    <div class='container'>
        <div class='row g-4'>
            <div class='col-lg-6'>
                <div class='section-title'>
                    <h4 class='highlight-title ' data-wow-delay='0.1s'>
                        Свяжитесь с нами
                    </h4>
                    <h4 class='title'>
                        Давайте работать вместе
                    </h4>
                    <p class=text-xl'>
                        <?= $model['description'] ?>

                    </p>

                </div>
                <div class='contact-point'>
                    <ul class='listing'>
                        <li class='single-point animate__animated animate__fadeInLeft wow' data-wow-delay='0.2s'>
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
                            <h4 class='title'>Персональный подход</h4>

                        </li>
                        <li class='single-point animate__animated animate__fadeInLeft wow' data-wow-delay='0.2s'>
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
                            <h4 class='title'>Быстрый ответ</h4>
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
                            <h4 class='highlight'>
                                Есть вопросы? Звоните!
                            </h4> <?php
                                foreach ($contactPhones as $phone): ?>
                                    <p class='pera'><a
                                                class='text-neutral-900 fw-semibold text-lg d-block hover-text-brand'
                                                href='tel:<?= $phone['number'] ?>'>
                                            <?= $phone['number'] ?></a>

                                    </p>
                                <?php
                                endforeach; ?>

                        </div>
                    </div>
                    <div class='contact-card'>
                        <div class='icon'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'
                                 fill='none'>
                                <path
                                        d='M34.1388 5H5.86125C4.63868 5.00132 3.46656 5.48758 2.60207 6.35207C1.73758 7.21656 1.25132 8.38868 1.25 9.61125V30.3888C1.25132 31.6113 1.73758 32.7834 2.60207 33.6479C3.46656 34.5124 4.63868 34.9987 5.86125 35H34.1388C35.3613 34.9987 36.5334 34.5124 37.3979 33.6479C38.2624 32.7834 38.7487 31.6113 38.75 30.3888V9.61125C38.7487 8.38868 38.2624 7.21656 37.3979 6.35207C36.5334 5.48758 35.3613 5.00132 34.1388 5ZM5.86125 7.5H34.1388C34.6985 7.50066 35.2351 7.72331 35.6309 8.1191C36.0267 8.51489 36.2493 9.05152 36.25 9.61125V10.5675L20 21.0138L3.75 10.5675V9.61125C3.75066 9.05152 3.97331 8.51489 4.3691 8.1191C4.76489 7.72331 5.30152 7.50066 5.86125 7.5ZM34.1388 32.5H5.86125C5.30152 32.4993 4.76489 32.2767 4.3691 31.8809C3.97331 31.4851 3.75066 30.9485 3.75 30.3888V13.54L19.3237 23.5513C19.5254 23.681 19.7602 23.75 20 23.75C20.2398 23.75 20.4746 23.681 20.6763 23.5513L36.25 13.54V30.3888C36.2493 30.9485 36.0267 31.4851 35.6309 31.8809C35.2351 32.2767 34.6985 32.4993 34.1388 32.5Z'
                                        fill='white'/>
                            </svg>
                        </div>
                        <div class='content'>
                            <h4 class='highlight'>
                                Наш Email
                            </h4>
                            <p class='pera'>info@sv-partner.ru</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-lg-6'>
                <div class='contact-form'>
                    <div class='section-title'>
                        <h4 class='title text-center'>
                            Мы на связи
                        </h4>
                    </div>
                    <?php
                        $form = ActiveForm::begin([
                                'id'          => 'contactForm',
                                'options'     => [
                                        'class' => 'row', // добавляем класс row для основной формы
                                ],
                                'fieldConfig' => [
                                        'template'     => '<div class="col-md-12"><div class="form-group">{input}{error}</div></div>',
                                        'inputOptions' => [
                                                'class' => 'form-control', // базовый класс для всех инпутов
                                        ],
                                ],
                        ]); ?>

                    <?= $form->field($contactForm, 'name')->textInput([
                            'placeholder' => 'Ваше имя',
                            'id'          => 'name',
                    ]) ?>

                    <?= $form->field($contactForm, 'email')->textInput([
                            'placeholder' => 'Ваш Email',
                            'id'          => 'email',
                            'type'        => 'email',
                    ]) ?>

                    <?= $form->field($contactForm, 'phone')->widget(MaskedInput::class, [
                            'mask'          => '+9(999)999-99-99',
                            'options'       => [
                                    'placeholder' => 'Ваш контактный телефонный номер',
                                    'class'       => 'form-control',
                            ],
                            'clientOptions' => [
                                    'greedy'          => false,
                                    'clearIncomplete' => true,
                            ],
                    ]) ?>

                    <?= $form->field($contactForm, 'body')->textarea([
                            'placeholder' => 'Ваше сообщение ...',
                            'id'          => 'message',
                            'rows'        => 4,
                    ]) ?>

                    <?= $form->field($contactForm, 'subject')->hiddenInput(['value' => 'Форма обратной связи сайта'])->label(false);
                    ?>
                    <div class="col-md-12">
                        <div class="form-group mt-15 text-end">
                            <?= Html::submitButton('Отправить', [
                                    'class' => 'btn btn-lg global-btn',
                                    'type'  => 'submit',
                            ]) ?>
                        </div>
                    </div>

                    <?php
                        ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</section>
