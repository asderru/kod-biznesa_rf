<?php
    
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /** @var View $this */
    /** @var bool $superadmin */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_start_index';
    $this->title = 'Панель управления';

?>
<div class="site-index py-4">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Shop'n'SEO Server Pack</h1>
    </div>

    <div class="body-content">

        <div class="row">

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header bg-body-secondary">
                        <h4><?= Html::encode(Constant::RAZDEL_LABEL)
                            ?></h4>

                    </div>
                    <div class="card-body">

                        <p>Основная сущность для СЕО-продвижения - это разделы.
                            Текст для раздела должен содержать не менее 600
                            знаков и
                            быть уникальным. Все ссылки внутри сайта должны быть
                            распределены с целью перенести основную ссылочную
                            массу на
                            те разделы, которые выбраны для продвижения.</p>
                    </div>

                    <div class='card-footer'>
                        
                        <?= Html::a(
                            Constant::RAZDEL_LABEL . ' (все разделы)',
                            [
                                '/shop/razdel/index',
                            ],
                        )
                        ?><br>
                        <?= Html::a(
                            'Добавить раздел',
                            [
                                '/shop/razdel/create',
                            ],
                        )
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header bg-body-secondary">
                        <h4><?= Html::encode(Constant::PRODUCT_LABEL)
                            ?></h4>
                    </div>
                    <div class="card-body">
                        <p>Для продвижения в сети товаров и услуг нет смысла
                            тратить
                            усилия на продвижение всей номенклатуры. Усилия по
                            СЕО-продвижению в этом случае распыляются. Следует
                            сосредоточить внимание на разделах и направлять
                            ссылочную
                            массу на самые важные из них. Текст для товара/услуги
                            должен состоять из более 600 знаков и быть
                            уникальным.
                            Товары - главный источник ссылочной массы на
                            продвигаемые разделы.
                        </p>
                    </div>

                    <div class='card-footer'>
                        <?= Html::a(
                            Constant::PRODUCT_LABEL . ' (все товары/услуги)',
                            [
                                '/shop/product/index',
                            ],
                        )
                        ?><br>
                        <?= Html::a(
                            'Добавить',
                            [
                                '/shop/product/create',
                            ],
                        )
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header bg-body-secondary">
                        <h4><?= Html::encode(Constant::BRAND_LABEL)
                            ?></h4>
                    </div>
                    <div class="card-body">
                        <p>Сущность бренды создана наращивания ссылочной массы.
                            Тексты для брендов должен содержать не менее 600
                            знаков и
                            быть уникальными. Ссылки со страницы бренда должны
                            идти
                            на
                            товары/услуги этого бренда. В первую очередь
                            создаются
                            тексты для брендов раздела, который участвует в СЕО
                            продвижении.</p>
                    </div>

                    <div class='card-footer'>
                        <?= Html::a(
                            Constant::BRAND_LABEL . ' (все бренды)',
                            [
                                '/shop/brand/index',
                            ],
                        )
                        ?><br>
                        <?= Html::a(
                            'Добавить бренд',
                            [
                                '/shop/brand/create',
                            ],
                        )
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header bg-body-secondary">
                        <h4><?= Html::encode(Constant::NEWS_LABEL)
                            ?></h4>
                    </div>
                    <div class="card-body">

                        <p>Сайт должен регулярно генерировать контент. Лучшим
                            способом для генерации является написание новостей.
                            лучшей практикой является ежедневное написание
                            новостей
                            со ссылкой на продвигаемые разделы.</p>
                    </div>
                    <div class='card-footer'>
                        <?= Html::a(
                            Constant::NEWS_LABEL . ' (новости)',
                            [
                                'seo/news/index',
                            ],
                        )
                        ?><br>
                        <?= Html::a(
                            'Добавить новость',
                            [
                                'seo/news/create',
                            ],
                        )
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header bg-body-secondary">
                        <h4><?= Html::encode(Constant::PAGE_LABEL)
                            ?></h4>
                    </div>
                    <div class="card-body">
                        <p>Страницы сайта в наращивании ссылочной массы не
                            участвуют.
                            Страницы как правило создаются для размещения на
                            сайте
                            необходимой информации - время работы, условия работы
                            и
                            пр. Информация, нужная для продвижения того или иного
                            раздела, должна размещаться в новостях.</p>
                    </div>
                    <div class='card-footer'>
                        <?= Html::a(
                            Constant::PAGE_LABEL . ' (страницы)',
                            [
                                'content/page/index',
                            ],
                        )
                        ?><br>
                        <?= Html::a(
                            'Добавить страницу',
                            [
                                'content/page/create',
                            ],
                        )
                        ?>
                    </div>
                </div>
            </div>


            <div class='col-lg-4 col-md-6 mb-3'>
                <div class='card h-100'>
                    <div class="card-header bg-body-secondary">
                        <h4>Метки</h4>
                    </div>
                    <div class="card-body">
                        <p>Метки, как и бренды, созданы для
                            наращивания ссылочной массы страниц с
                            товарами/усулугами. В первую очередь
                            создаются метки для товаров/услуг раздела, который
                            участвует в СЕО продвижении.</p>
                    </div>

                    <div class='card-footer'>
                        <?= Html::a(
                            'Метки',
                            [
                                '/content/tag/index',
                            ],
                        )
                        ?><br>
                        <?= Html::a(
                            'Добавить метку',
                            [
                                '/content/tag/create',
                            ],
                        )
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class='col-lg-4 col-md-6 mb-3'>
                <div class='card h-100'>
                    <div class="card-header bg-body-secondary">
                        <h4>Оформление сайта</h4>
                    </div>
                    <div class='card-body'>
                        Оформление сайта и основных страниц под требования
                        поисковых систем.
                        <ul class="p-4">
                            <li>
                                <?= Html::a(
                                    'Оформление сайта',
                                    [
                                        '/admin/information/update',
                                    ],
                                )
                                ?>
                            </li>
                            <li>
                                <?= Html::a(
                                    'Оформление контактов',
                                    [
                                        '/admin/contact/update',
                                    ],
                                )
                                ?>
                            </li>
                            <li>
                                <?= Html::a(
                                    'Оформление разделов',
                                    [
                                        '/shop/razdel/updateRoot',
                                    ],
                                )
                                ?>
                            </li>
                            <li>
                                <?= Html::a(
                                    'Оформление товаров/услуг',
                                    [
                                        '/shop/product/updateRoot',
                                    ],
                                )
                                ?>
                            </li>
                            <li>
                                <?= Html::a(
                                    'Оформление брендов',
                                    [
                                        '/shop/brand/updateRoot',
                                    ],
                                )
                                ?>
                            </li>
                            <li>
                                <?= Html::a(
                                    'Оформление страниц',
                                    [
                                        '/content/page/updateRoot',
                                    ],
                                )
                                ?>
                            </li>
                            <li>
                                <?= Html::a(
                                    'Оформление новостей',
                                    [
                                        '/seo/news/updateRoot',
                                    ],
                                )
                                ?>
                            </li>
                            <li>
                                <?= Html::a(
                                    'Оформление анонсов',
                                    [
                                        '/seo/anons/updateRoot',
                                    ],
                                )
                                ?>
                            </li>
                            <li>
                                <?= Html::a(
                                    'Оформление фотографий',
                                    [
                                        '/admin/photo/create',
                                    ],
                                )
                                ?>
                            </li>
                            <li>
                                <?= Html::a(
                                    'Коррекция  размеров фото',
                                    [
                                        '/admin/ratio/create',
                                    ],
                                )
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
                if ($superadmin) { ?>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class='card h-100'>
                            <div class="card-header bg-body-secondary">
                                <h4>Основные данные</h4>
                            </div>
                            <div class='card-body'>
                                <?= Html::a(
                                    'Сайт',
                                    [
                                        '/admin/information/view',
                                    ],
                                    [
                                        'class' => 'btn btn-sm btn-success',
                                    ],
                                )
                                ?>
                                <?= Html::a(
                                    'Запустить!',
                                    [
                                        '/admin/start/index',
                                    ],
                                    [
                                        'class' => 'btn btn-sm btn-danger',
                                    ],
                                )
                                ?>
                            </div>
                            <div class="card-header bg-body-secondary">
                                <h4>Тестирование</h4>
                            </div>
                            <div class='card-body'>

                                <ul>
                                    <li>
                                        <?= Html::a(
                                            'Тестировать анонс',
                                            [
                                                '/test/anons/index',
                                            ],
                                        )
                                        ?>
                                    </li>
                                    <li>
                                        <?= Html::a(
                                            'Тестировать страницы',
                                            [
                                                '/test/page/index',
                                            ],
                                        )
                                        ?>
                                    </li>
                                    <li>
                                        <?= Html::a(
                                            'Тестировать новости',
                                            [
                                                '/test/news/index',
                                            ],
                                        )
                                        ?>
                                    </li>
                                    <li>
                                        <?= Html::a(
                                            'Тестировать разделы',
                                            [
                                                '/test/razdel/index',
                                            ],
                                        )
                                        ?>
                                    </li>
                                    <li>
                                        <?= Html::a(
                                            'Тестировать продукты',
                                            [
                                                '/test/product/index',
                                            ],
                                        )
                                        ?>
                                    </li>
                                    <li>
                                        <?= Html::a(
                                            'Тестировать бренды',
                                            [
                                                '/test/brand/index',
                                            ],
                                        )
                                        ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                } ?>
            <div class='col-lg-4'>

            </div>
        </div>
    </div>
