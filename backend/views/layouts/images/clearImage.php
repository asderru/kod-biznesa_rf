<?php
    
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $model Model */
    
    const CLEAR_IMAGES_LAYOUT = '#layouts_images_clearImage';
    echo PrintHelper::layout(CLEAR_IMAGES_LAYOUT);


?>
<div class='card mb-3'>

    <div class="card-header bg-body-secondary">
        <strong>
            Изображение
        </strong>
    </div>
    
    <?php
        if ($model->photo) : ?>
            <div class='card-body'>
                <?php
                    try {
                        echo
                        Html::a(
                            Html::img(
                                $model->getImageUrl(3),
                                [
                                    'class' => 'img-fluid',
                                ],
                            ),
                            $model->getImageUrl(12),
                            [
                                'target' => '_blank',
                            ],
                        );
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                            'model->getImageUrl', CLEAR_IMAGES_LAYOUT, $e,
                        );
                    } ?>
            </div>
        <?php
        endif ?>
</div>
