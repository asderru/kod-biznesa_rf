<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model Razdel|Category|Page|Group|Book|Section */
    /* @var $uploadForm UploadPhotoForm */
    
    const IMAGES_GALLERIES_LAYOUT = '#layouts_images_galleries';
    
    echo PrintHelper::layout(IMAGES_GALLERIES_LAYOUT);
?>

<div class="card">
    <div class='card-header bg-secondary-subtle d-flex justify-content-between'>
        <h4>Галереи, созданные для модели</h4>
        <?= ButtonHelper::galleryCreate($model::TEXT_TYPE, $model->id)
        ?>
    </div>
    <div class='card-body'>
        <?php
            if ($model->hasGalleries()):?>
                <div class="row">
                    <?php
                        foreach ($model->galleries as $gallery): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-body-secondary">
                                        <?= Html::img(
                                            $gallery->getImageUrl(3),
                                            [
                                                'class' => 'card-img-top',
                                            ],
                                        )
                                        ?>
                                    </div>
                                    <h5 class="card-title px-4">
                                        <?= StatusHelper::icon($gallery->status)
                                        ?>
                                        <?= Html::a(Html::encode($gallery->name), [
                                            '/utils/gallery/view',
                                            'id' => $gallery->id,
                                        ], [
                                            'class' => 'text-decoration-none',
                                        ])
                                        ?>
                                    </h5>
                                    <div class="px-4">
                                        <?= FormatHelper::truncate($gallery->description, 100)
                                        ?>
                                        В галерее <?= $gallery->photosCount ?> изображений
                                    </div>
                                </div>
                            </div>
                        <?php
                        endforeach; ?>
                </div>
            <?php
            endif; ?>
    </div>
</div>

<div class="card">
    <div class='card-header bg-secondary-subtle d-flex justify-content-between'>
        <h4>Галереи, связанные с моделью</h4>
        <?= ButtonHelper::galleryAssign($model::TEXT_TYPE, $model->id)
        ?>
    </div>
    <div class='card-body'>
        <?php
            if ($model->hasGalleriesAssigned()):?>
                <div class="row">
                    <?php
                        foreach ($model->getGalleriesAssigned()->all() as $galleryAssigned): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-body-secondary">
                                        <?php
                                            try {
                                                echo
                                                Html::img(
                                                    $galleryAssigned->getImageUrl(3),
                                                    [
                                                        'class' => 'card-img-top',
                                                    ],
                                                );
                                            }
                                            catch (Throwable $e) {
                                                PrintHelper::exception(
                                                    'galleryAssigned->getImageUrl', IMAGES_GALLERIES_LAYOUT, $e,
                                                );
                                            } ?>
                                    </div>
                                    <h5 class="card-title px-4">
                                        <?= Html::a(Html::encode($galleryAssigned->name), ['gallery/view', 'id' => $galleryAssigned->id], [
                                            'class' => 'text-decoration-none',
                                        ])
                                        ?> <?= StatusHelper::icon($galleryAssigned->status)
                                        ?>
                                    </h5>
                                    <div class="px-4">
                                        <?= FormatHelper::truncate($galleryAssigned->description, 100)
                                        ?>
                                        В галерее <?= $galleryAssigned->photosCount ?> изображений
                                    </div>
                                </div>
                            </div>
                        <?php
                        endforeach; ?>
                </div>
            <?php
            endif; ?>
    </div>
    <div class='card-footer d-flex justify-content-between'>

    </div>
</div>
