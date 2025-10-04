<?php
    
    
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Forum\Thread;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\helpers\ImageHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    /* @var $model Chapter|Post|Article|Thread| */
    
    const CHECK_POSTS_LAYOUT = '#layouts_images_checkPostsBar';
    echo PrintHelper::layout(CHECK_POSTS_LAYOUT);
    
    try {
        echo
        ImageHelper::checkImgUrl($model->getImageUrl(3))
            ?
            Html::tag(
                'span', 3,
                [
                    'class' => 'badge p-2 text-bg-success',
                ],
            )
            :
            Html::a(
                '3',
                [
                    'create-webp',
                    'id'  => $model->id,
                    'col' => 3,
                ],
                [
                    'class' => 'badge p-2 text-bg-danger',
                ],
            );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'ImageHelper_checkImgUrl', CHECK_POSTS_LAYOUT, $e,
        );
    }
    
    
    try {
        echo
        ImageHelper::checkImgUrl($model->getImageUrl(12))
            ?
            Html::tag(
                'span', 12,
                [
                    'class' => 'badge p-2 text-bg-success',
                ],
            )
            :
            Html::a(
                '12',
                [
                    'create-webp',
                    'id'  => $model->id,
                    'col' => 12,
                ],
                [
                    'class' => 'badge p-2 text-bg-danger',
                ],
            );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'ImageHelper_checkImgUrl', CHECK_POSTS_LAYOUT, $e,
        );
    }
