<?php
    
    use backend\widgets\FeedbackWidget;
    use backend\widgets\MegaMenuWidget;
    use core\edit\entities\User\User;
    use core\edit\repositories\Tech\CacheRepository;
    use core\helpers\FaviconHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /* @var $this View */
    
    const NAVBAR_LAYOUT = '#layouts_partials_navbar';
    
    // Получаем экземпляр компонента кэша
    $cache = Yii::$app->cache;
    
    // Ключ для кэширования данных пользователя (можно выбрать любой уникальный идентификатор, связанный с пользователем)
    $cacheKey = 'user_navbar_' . Yii::$app->user->id;
    
    // Получаем данные из кэша
    $user = $cache->get($cacheKey);
    
    // Если данных в кэше нет, делаем запрос к базе данных и сохраняем результат в кэше
    if ($user === false) {
        $user = User::findOne(Yii::$app->user->id);
        
        // Время хранения данных в кэше (в секундах), например, 3600 секунд (1 час)
        $cache->set($cacheKey, $user, 3600);
    }
    
    // Теперь $user содержит объект пользователя либо false, если пользователь не найден.
    // Можно использовать $user в шаблоне навбара для отображения данных пользователя.
    
    $person = $user->person;
    
    //session_start();
    
    if (!isset($_SESSION['avatar'])) {
        try {
            $_SESSION['avatar'] = '/img/avatar/cat-' . random_int(1, 80) . '.webp';
        }
        catch (Exception $e) {
            PrintHelper::exception(
                'Avatar', NAVBAR_LAYOUT, $e,
            );
        }
    }
    
    $editModels = CacheRepository::getLastArray(10);

?>

<nav class='navbar navbar-expand navbar-light navbar-bg'>
    <div class='buttons-area_colorscheme'>
        <button type='button' id='color-light' class='btn text-secondary p-0'>
            <i class='fa-regular fa-circle'></i>
        </button>
        <button type='button' id='color-semi' class='btn text-secondary p-0'>
            <i class='fa-solid fa-circle-half-stroke'></i>
        </button>
        <button type='button' id='color-dark' class='btn text-secondary p-0'>
            <i class='fa-solid fa-circle'></i>
        </button>
    </div>

    <a class='sidebar-toggle js-sidebar-toggle'>
        <i class='hamburger align-self-center'></i>
    </a>
    <a class='nav-icon js-fullscreen d-none d-lg-block' href='#'>
        <div class='position-relative'>
            <i class='fa-solid fa-expand'></i>
        </div>
    </a>
    <ul class='navbar-nav d-none d-lg-flex'>

        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='megaDropdown' role='button' data-bs-toggle='dropdown'
               aria-haspopup='true'
               aria-expanded='false'>
                <i class='fa-solid fa-sliders text-primary'></i> Mega Menu
            </a>
            <div id='megamenu_item' class='dropdown-menu dropdown-menu-start dropdown-mega'
                 aria-labelledby='megaDropdown'>
                
                <?php
                        echo
                        MegaMenuWidget::widget();
                ?>
            </div>
        </li>

        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='resourcesDropdown' role='button' data-bs-toggle='dropdown'
               aria-haspopup='true'
               aria-expanded='false'>
                <i class='fa-solid fa-flag-checkered text-warning'></i> Express Menu
            </a>
            <div class='dropdown-menu' aria-labelledby='resourcesDropdown'>
                <a class='dropdown-item' href='/express/razdel/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::RAZDEL_TYPE)
                    ?>
                    Раздел</a>
                <a class='dropdown-item' href='/express/product/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::PRODUCT_TYPE)
                    ?>
                    Товар</a>
                <a class='dropdown-item' href='/express/page/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::PAGE_TYPE)
                    ?>
                    Страницы</a>
                <a class='dropdown-item' href='/express/book/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::BOOK_TYPE)
                    ?>
                    Книги</a>
                <a class='dropdown-item' href='/express/chapter/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::CHAPTER_TYPE)
                    ?>
                    Главы</a>
                <a class='dropdown-item' href='/express/category/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::CATEGORY_TYPE)
                    ?>
                    Блоги</a>
                <a class='dropdown-item' href='/express/post/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::POST_TYPE)
                    ?>
                    Посты</a>
                <a class='dropdown-item' href='/express/news/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::NEWS_TYPE)
                    ?>
                    Новости</a>
                <a class='dropdown-item' href='/express/material/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::MATERIAL_TYPE)
                    ?>
                    Материалы</a>
                <a class='dropdown-item' href='/seo/anons/index'>
                    <?= FaviconHelper::getTypeFavSized(Constant::ANONS_TYPE)
                    ?>
                    Анонсы</a>

            </div>
        </li>

        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='resourcesDropdown' role='button' data-bs-toggle='dropdown'
               aria-haspopup='true'
               aria-expanded='false'>
                <i class='fa-solid fa-pen-to-square text-primary'></i> Редактировать
            </a>
            <div class='dropdown-menu' aria-labelledby='resourcesDropdown'>
                <?php
                    foreach ($editModels as $model): ?>
                        <?= Html::a(
                            FaviconHelper::getTypeFavSized($model['text_type']) . Html::encode($model['name']),
                            TypeHelper::getUpdate($model['text_type'], $model['parent_id']),
                            [
                                'class' => 'dropdown-item',
                            ],
                        
                        )
                        ?>
                    <?php
                    endforeach; ?>
            </div>
        </li>

        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='resourcesDropdown' role='button' data-bs-toggle='dropdown'
               aria-haspopup='true'
               aria-expanded='false'>
                <i class='fa-solid fa-file-circle-plus text-success'></i> Создать
            </a>
            <div class='dropdown-menu' aria-labelledby='resourcesDropdown'>
                <a class='dropdown-item' href='/shop/razdel/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::RAZDEL_TYPE)
                    ?>
                    Раздел</a>
                <a class='dropdown-item' href='/shop/product/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::PRODUCT_TYPE)
                    ?>
                    Продукт</a>
                <a class='dropdown-item' href='/content/page/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::PAGE_TYPE)
                    ?>
                    Страницу</a>
                <a class='dropdown-item' href='/library/book/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::BOOK_TYPE)
                    ?>
                    Книгу</a>
                <a class='dropdown-item' href='/library/chapter/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::CHAPTER_TYPE)
                    ?>
                    Главу</a>
                <a class='dropdown-item' href='/blog/category/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::CATEGORY_TYPE)
                    ?>
                    Блог</a>
                <a class='dropdown-item' href='/blog/post/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::POST_TYPE)
                    ?>
                    Пост</a>
                <a class='dropdown-item' href='/seo/news/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::NEWS_TYPE)
                    ?>
                    Новости</a>
                <a class='dropdown-item' href='/seo/material/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::MATERIAL_TYPE)
                    ?>
                    Материал</a>
                <a class='dropdown-item' href='/seo/anons/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::ANONS_TYPE)
                    ?>
                    Анонс</a>
                <a class='dropdown-item' href='/tools/draft/create'>
                    <?= FaviconHelper::getTypeFavSized(Constant::DRAFT_TYPE)
                    ?>
                    Черновик</a>

            </div>
        </li>

        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='resourcesDropdown' role='button' data-bs-toggle='dropdown'
               aria-haspopup='true'
               aria-expanded='false'>
                <i class='fa-solid fa-hat-cowboy text-danger'></i> Управление сайтом
            </a>
            <div class='dropdown-menu' aria-labelledby='resourcesDropdown'>
                <a class='dropdown-item' href='/admin/information/index'>
                    <i class='fa-solid fa-gauge-high  align-middle me-1'></i>
                    Главная</a>
                <a class='dropdown-item' href='/admin/contact/index'>
                    <i class='fa-solid fa-address-card align-middle me-1'></i>
                    Контакты</a>
                <a class='dropdown-item' href='/user/user'>
                    <i class='fa-solid fa-users-gear  align-middle me-1'></i>
                    Пользователи</a>
                <a class='dropdown-item' href='/user/person'>
                    <i class='fa-solid fa-id-card  align-middle me-1'></i>
                    Профили пользователей</a>
                <a class='dropdown-item' href='/link/gazer'>
                    <i class='fa-solid fa-route align-middle me-1'></i>
                    LinkGazer</a>
                <a class='dropdown-item' href='/admin/structure'>
                    <i class='fa-solid fa-network-wired align-middle me-1'></i>
                    Структура сервера</a>
                <a class='dropdown-item' href='/user/userstat'>
                    <i class='fa-solid fa-chart-column  align-middle me-1'></i>
                    Статистика</a>
            </div>
        </li>
    </ul>

    <!--###############  CatZone ##############-->
    
    <div class='navbar-collapse collapse'>
        <ul class='navbar-nav navbar-align'>
            <li class='nav-item dropdown'>

                <!--#################################-->
                <?php
                    try {
                        echo
                        FeedbackWidget::widget();
                    }
                    catch (Throwable $e) {
                    }
                ?>
                <!--#################################-->

            </li>


            <li class='nav-item dropdown'>
                <a class='nav-icon pe-md-0 dropdown-toggle' href='#' data-bs-toggle='dropdown'>
                    <img
                            alt='avatar'
                            src= <?= $_SESSION['avatar'] ?>
                            class='avatar img-fluid rounded me-1'
                    />


                </a>
                <div class='dropdown-menu dropdown-menu-end'>

                    <a class='dropdown-item' href='pages-profile.html'>
                        <strong>
                            
                            <?= ($person) ?
                                Html::encode
                                (
                                    $person->name,
                                ) : 'Аноним' ?></strong>, <?= ($person) ?
                            Html::encode
                            (
                                $person->position,
                            ) : 'Прохожий' ?>
                    </a>

                    <div class='dropdown-divider'></div>

                    <a class='dropdown-item' href='pages-profile.html'>
                        <i class='align-middle me-1'
                           data-feather='user'></i>
                        Профиль</a>
                    <a class='dropdown-item' href='#'><i class='align-middle me-1' data-feather='pie-chart'></i>
                        class</a>
                    <div class='dropdown-divider'></div>
                    <a class='dropdown-item' href='pages-settings.html'>
                        <i class='align-middle me-1'
                           data-feather='settings'></i> Настройки</a>
                    <a class='dropdown-item' href='#'>
                        <i class='align-middle me-1' data-feather='help-circle'></i> Помощь</a>
                    <div class='dropdown-divider'></div>
                    <a class='dropdown-item' href='/auth/logout'>Выйти</a>
                </div>
            </li>
        </ul>
    </div>

</nav>
