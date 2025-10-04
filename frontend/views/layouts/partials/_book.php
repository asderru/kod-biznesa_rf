<?php
    
    use core\edit\entities\Library\Book;
    use core\helpers\FormatHelper;
    use yii\bootstrap5\Html;
    
    /* @var $model Book */
    /* @var $url string */
    
    $layoutId = '#frontend_views_layouts_partials_book';

?>

<section class='main-section__content'>

    <div class='main-section__image'>
        
        <?php
            try {
                echo
                Html::a(
                    $model->getPicture([3, 6, 12]),
                    [
                        'view',
                        'id' => $model->id,
                    ],
                );
            }
            catch (Throwable $e) {
            }
        ?>

    </div>


    <h2 class='main-section__title'>
        
        <?= Html::a(
            Html::encode($model->title),
            [
                'view',
                'id' => $model->id,
            ],
            [
                'itemprop' => 'url',
            ],
        ) ?>
    </h2>

    <div class="main-section__description">
        <?= FormatHelper::asDescription($model, 30) ?>
    </div>

    <div class='button-block text-center'>
        <?= Html::a(
            'Читать',
            [
                'book/view',
                'id' => $model->id,
            ],
            [
                'class' => 'btn btn-read-more',
            ],
        )
        ?>

    </div>
</section>
