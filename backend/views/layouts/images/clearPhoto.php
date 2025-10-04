<?php
    
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $model Model */
    
    const CLEAR_PHOTO_LAYOUT = '#layouts_images_clearPhoto';
    
    echo PrintHelper::layout(CLEAR_PHOTO_LAYOUT);

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
                        echo Html::img(
                            $model->getImageUrl(6),
                            [
                                'class' => 'img-fluid',
                            ],
                        );
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                            'model->getImageUrl', CLEAR_PHOTO_LAYOUT, $e,
                        );
                    } ?>
                <hr>
                <?php
                    foreach ($model->photos as $photo) : ?>
                        <?php
                        
                        if ($photo->id !== $model->mainPhoto->id) {
                            echo Html::img(
                                $photo->getImageUrl(1),
                                [
                                    'class' => 'img-fluid',
                                ],
                            );
                        }
                        ?>
                    <?php
                    endforeach; ?>
            </div>
        <?php
        endif ?>
</div>
