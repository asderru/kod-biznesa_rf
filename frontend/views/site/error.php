<?php
    
    use core\edit\entities\User\User;
    use frontend\assets\AppAsset;
    use Random\RandomException;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    AppAsset::register($this);
    
    /** @var yii\web\View $this */
    /** @var string $message */
    /** @var string $imgBackground */
    /** @var Exception $exception */
    
    $this->title = $message;
    
    $superadmin = (new User)->isSuperadmin();
    $img        = 'error_col-12_1.webp';
    try {
        $randomNumber = random_int(1, 15);
    }
    catch (RandomException $e) {
    
    }
    $img = preg_replace('/_(\d+)\.webp$/', "_{$randomNumber}.webp", $img);
    
    $imgBackground = Url::to('/img/error/' . $img, true);

?>

<!--###### Content ##########################################################-->
<div style='background-image: url(<?= $imgBackground ?>);
        background-size: cover; background-repeat: no-repeat; background-position: center center;'>
    <div class='content-area'>

        <div class='inner-area__content error-screen'>

            <div class='site-error container'>


                <div class='col-lg-8 offset-lg-4 error-area'>
                    <h1 class='error-title'>
                        <?= Html::encode($this->title) ?>
                    </h1>

                    <div class="alert alert-danger">
                        <?= ($superadmin) ? nl2br(Html::encode($exception)) : null ?>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
