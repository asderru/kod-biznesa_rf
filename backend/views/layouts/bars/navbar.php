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
    <!--###############  CatZone ##############-->
    
    <div class='navbar-collapse collapse'>
        <ul class='navbar-nav navbar-align'>


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
