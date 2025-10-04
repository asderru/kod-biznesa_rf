<?php
    
    use backend\assets\ServerAppAsset;
    use core\edit\entities\User\User;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /** @var yii\web\View $this */
    /** @var string $content */
    /** @var User $user */
    
    ServerAppAsset::register($this);
    const SIMPLE_LAYOUT = '#layouts_simple';
    
    $user     = User::findOne([Yii::$app->user->id]);
    $site = (new ParametrHelper)::getSite();
    $appType  = Yii::$app->params[('appType')];
    $siteMode = Yii::$app->params[('siteMode')];
    
    
    $packName = match ($appType) {
        Constant::APP_ULTIMATE => 'Ultimate',
        Constant::APP_SERVER   => 'Server',
        Constant::APP_BUSINESS => 'Business',
        Constant::APP_STANDART => 'Standart',
        default                => 'Start',
    };
    
    $modeName = match ($siteMode) {
        Constant::MODE_SHOP     => 'Shop',
        Constant::MODE_MAGAZIN  => 'Magazin',
        Constant::MODE_LIBRARY  => 'Library',
        Constant::MODE_FORUM    => 'Forum',
        Constant::MODE_BLOG     => 'Blog',
        Constant::MODE_AGENCY   => 'Agency',
        Constant::MODE_PRODUCER => 'Producer',
        default                 => 'Site',
    };
    
    $siteName = $site->name;
    //session_start();
    /** @noinspection GlobalVariableUsageInspection */
    if (!isset($_SESSION['time'])) {
        /** @noinspection GlobalVariableUsageInspection */
        $_SESSION['time'] = date('d.m.Y H:i:s');
    }
    
    $superadmin         = (new User)->isSuperadmin();
    
    //session_start();
    /** @noinspection GlobalVariableUsageInspection */
    if (!isset($_SESSION['avatar'])) {
        try {
            /** @noinspection GlobalVariableUsageInspection */
            $_SESSION['avatar'] = '/img/avatar/cat-' . random_int(1, 35) . '.jpg';
        }
        catch (Exception $e) {
            PrintHelper::exception(
                $actionId, 'Avatar ' . SIMPLE_LAYOUT, $e,
            );
        }
    }

?>

<?php
    $this->beginPage()
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta
                name='viewport'
                content='width=device-width, initial-scale=1, shrink-to-fit=no'
        >
        <meta
                name='description'
                content='Dashboard'
        >
        <meta name='author' content='test'>
        <meta
                name='keywords'
                content='admin, dashboard,'
        >
        <meta
                name="viewport"
                content="width=device-width, initial-scale=1, shrink-to-fit=no"
        >
        <!--Favicons-->
        <link rel='shortcut icon' href='/favicon.ico' type='image/x-icon'>
        <link rel='icon' href='/favicon.ico' type='image/x-icon'>
        
        <?php
            $this->registerCsrfMetaTags()
        ?>
        <title><?= $siteName . ' | ' . Html::encode($this->title)
            ?></title>
        
        <?php
            $this->head()
        ?>
    </head>

    <body class='bg-light'>
    
    <?php
        $this->beginBody()
    ?>

    <div id='db-wrapper' class="toggled">
        <!-- navbar vertical -->

        <!-- Sidebar -->
        <nav class='navbar-vertical navbar'>
            <div class='nav-scroller'>

                <!-- Brand logo -->
                <a
                        class='navbar-brand text-light' href='<?= Url::to
                (
                    '@homepage',
                )
                ?>'
                        target="_blank"
                >
                    <?= $siteName ?>
                </a>

                <!-- Top Navbar nav -->
                <?= $this->render(
                    '/layouts/bars/navbar',
                )
                ?>

            </div>
        </nav>

        <!-- Page content -->
        <div id='page-content'>
            
            
            <?= $content ?>


        </div>

    </div>
    
    <?= $this->render(
        '/layouts/bars/footer',
        [
            'packName' => $packName,
            'modeName' => $modeName,
        ],
    )
    ?>

    <!--Container Main end-->
    <?php
        $this->endBody()
    ?>

    </body>

    </html>

<?php
    $this->endPage();
