<?php
    
    use core\edit\entities\User\Person;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $this yii\web\View */
    /** @var string $packName */
    /** @var string $modeName */
    /** @var string $context */
    /** @var int $appType */
    /** @var int $siteMode */
    /** @var Person $person */
    
    const SIDEBAR_LAYOUT = '#layouts_partials_sidebar';
    
    $controller   = Yii::$app->controller;
    $action       = $controller->action->id;
    $controllerID = $controller->getUniqueId(); // Это вернет 'express/article'
    $folderName   = explode('/', $controllerID)[0];
    $collapsed    = ($action === 'create' || $action === 'update' || $action === 'update-html' || $action === 'keywords'
                     || $folderName === 'express') ? ' collapsed' : null;
?>

<nav id='sidebar' class='sidebar js-sidebar <?= $collapsed ?>'>
    <div class='sidebar-content js-simplebar'>
        <!-- Brand logo -->
        <div class='sidebar-homepage'>
            <a class='sidebar-brand' href='<?= Url::to
            (
                '@homepage',
            )
            ?>' target="_blank">
					<span class='sidebar-brand-text align-middle'>
						<sup><small class='badge bg-secondary text-uppercase'><?= $modeName ?></small></sup>
						<?= Yii::$app->id ?>
					</span>
            </a>
        </div>

        <div class='sidebar-panel'>
            <a class='sidebar-user-title' href='/'>
                SnS <?= $packName ?> Pack
            </a>
        </div>

        <ul class='sidebar-nav'>

            <li class="sidebar-item<?= ($context === 'admin/information') ? ' active' : null ?>">
                <?= Html::a(
                    'Блок с баннером',
                    [
                        '/admin/information/view',
                        'id' => 110,
                    ],
                    [
                        'class' => 'sidebar-link',
                    ],
                )
                ?>
            </li>
            <li class="sidebar-item<?= ($context === 'content/page') ? ' active' : null ?>">
                <?= Html::a(
                    'Первый блок', [
                    '/content/page/view',
                    'id' => 2,
                ],
                    [
                        'class' => 'sidebar-link',
                    ],
                )
                ?>
            </li>
            <li class="sidebar-item<?= ($context === 'content/page') ? ' active' : null ?>">
                <?= Html::a(
                    'Второй блок', [
                    '/content/page/view',
                    'id' => 3,
                ],
                    [
                        'class' => 'sidebar-link',
                    ],
                )
                ?>
            </li>

            <li class="sidebar-item<?= ($context === 'content/page') ? ' active' : null ?>">
                <?= Html::a(
                    'Мероприятия', [
                    '/content/page/view',
                    'id' => 4,
                ],
                    [
                        'class' => 'sidebar-link',
                    ],
                )
                ?>
            </li>

            <li class="sidebar-item<?= ($context === 'content/page') ? ' active' : null ?>">
                <?= Html::a(
                    'Команда', [
                    '/library/author/index',
                ],
                    [
                        'class' => 'sidebar-link',
                    ],
                )
                ?>
            </li>

            <li class="sidebar-item<?= ($context === 'content/review') ? ' active' : null ?>">
                <?= Html::a(
                    'Отзывы', [
                    '/content/review/index',
                ],
                    [
                        'class' => 'sidebar-link',
                    ],
                )
                ?>
            </li>

            <li class="sidebar-item<?= ($context === 'user/person') ? ' active' : null ?>">
                <?= Html::a(
                    'Профили', [
                    '/user/person/index',
                ],
                    [
                        'class' => 'sidebar-link',
                    ],
                )
                ?>
            </li>

            <li class="sidebar-item<?= ($context === 'utils/image') ? ' active' : null ?>">
                <?= Html::a(
                    'Картинки',
                    [
                        '/utils/image/index',
                    ],
                    [
                        'class' => 'sidebar-link',
                    ],
                )
                ?>
            </li>

        </ul>

        <div class='sidebar-cta'>
            <div class='sidebar-cta-content'>
                Начало сессии:
                <br><?php
                    try {
                        echo
                        
                        Yii::$app->formatter->asDateTime(
                            $_SESSION['time'], 'long',
                        );
                    }
                    catch (InvalidConfigException $e) {
                        PrintHelper::exception(
                            'Виджет времени', SIDEBAR_LAYOUT, $e,
                        );
                    }
                ?>
            </div>
        </div>
    </div>
</nav>
