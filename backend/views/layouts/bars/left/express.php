<?php
    
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $context string */

?>


<li class='sidebar-item'>
    <a data-bs-target='#express-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-flag-checkered'></i>
        <span class='align-middle'>Экспресс-правка</span>
    </a>
    <ul id='express-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>

        <li class="sidebar-item<?= ($context === 'express/book') ? ' active' : null ?>">
            <?= Html::a(
                Constant::RAZDEL_LABEL,
                '/express/razdel',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'express/product') ? ' active' : null ?>">
            <?= Html::a(
                Constant::PRODUCT_LABEL,
                '/express/product',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'express/brand') ? ' active' : null ?>">
            <?= Html::a(
                Constant::BRAND_LABEL,
                '/express/brand',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'express/review') ? ' active' : null ?>">
            <?= Html::a(
                'Обзоры',
                '/express/review',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>


        <!--###### Magazin  ##################################-->
        <?php
            if (Yii::$app->params[('appType')] === 0 || Yii::$app->params[('siteMode')] === 2): ?>
                <li class="sidebar-item<?= ($context === 'express/section') ? ' active' : null ?>">
                    <?= Html::a(
                        'Рубрики / ' . Constant::SECTION_LABEL,
                        '/express/section',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'express/article') ? ' active' : null ?>">
                    <?= Html::a(
                        'Статьи / ' . Constant::ARTICLE_LABEL,
                        '/express/article',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'express/person') ? ' active' : null ?>">
                    <?= Html::a(
                        'Профили пользователей',
                        '/express/person',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>
            
            <?php
            endif; ?>


        <!--###### Library ##################################-->
        <?php
            if (Yii::$app->params[('appType')] === 0 || Yii::$app->params[('siteMode')] === 3): ?>


                <li class="sidebar-item<?= ($context === 'express/page') ? ' active' : null ?>">
                    <?= Html::a(
                        'Страницы / ' . Constant::PAGE_LABEL,
                        '/express/page',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'express/news') ? ' active' : null ?>">
                    <?= Html::a(
                        'Новости / ' . Constant::NEWS_LABEL,
                        '/express/news',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'express/book') ? ' active' : null ?>">
                    <?= Html::a(
                        'Книги / ' . Constant::BOOK_LABEL,
                        '/express/book',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'express/chapter') ? ' active' : null ?>">
                    <?= Html::a(
                        'Главы / ' . Constant::CHAPTER_LABEL,
                        '/express/chapter',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>
            
            <?php
            endif; ?>


        <!--###### Forum ##################################-->
        <?php
            if (Yii::$app->params[('siteMode')] === 7): ?>


                <li class="sidebar-item<?= ($context === 'express/group') ? ' active' : null ?>">
                    <?= Html::a(
                        Constant::GROUP_LABEL,
                        '/express/group',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'express/news') ? ' active' : null ?>">
                    <?= Html::a(
                        Constant::THREAD_LABEL,
                        '/express/news',
                        [
                            'class' => 'sidebar-link',
                        ],
                    )
                    ?>
                </li>

                <li class="sidebar-item<?= ($context === 'express/person') ? ' active' : null ?>">
                    <?= Html::a(
                        'Профили',
                        '/express/person',
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
