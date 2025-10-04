<?php
    
    use backend\helpers\ModalHelper;
    use core\read\readers\Admin\SiteModeReader;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $model Model|Razdel */
    /* @var $uploadForm UploadPhotoForm */
    
    const UPLOAD_IMAGE_LAYOUT = '#layouts_images_uploadImage';
    
    echo PrintHelper::layout(UPLOAD_IMAGE_LAYOUT);

?>
<div class='card mb-3'>

    <div class='card-header bg-light'>
        <strong>
            Изображение
        </strong>

    </div>
    
    <?php
        if ($model->photo) : ?>

            <div class="card-header bg-body-secondary">
                <?= $this->render(
                    '/layouts/images/_checkImagesBar',
                    [
                        'model' => $model,
                    ],
                )
                ?>
            </div>
        
        <?php
        endif;
    ?>
    
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
                            'model->getImageUrl ', CONTENT_PHOTO_PART_LAYOUT, $e,
                        );
                    } ?>


                <hr>
                <?= Html::a(
                    'Править картинки',
                    [
                        'utils/picture/view',
                        'textType' => $model::TEXT_TYPE,
                        'id'       => $model->id,
                    ]
                    ,
                    [
                        'class' => 'btn btn-sm btn-outline-primary',
                    ],
                )
                ?><br>
                <?php
                    try {
                        echo $model->getImageUrl(3);
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                            'model->getImageUrl', UPLOAD_IMAGE_LAYOUT, $e,
                        );
                    } ?>
            </div>
        <?php
        endif;
    ?>

    <div class="card-footer">
        
        <?php
            if ($model->photo && $model->site_id === Parametr::siteId()) : ?>
                <?= Html::a(
                    'Удалить картинку',
                    [
                        'delete-photo',
                        'id' => $model->id,
                    ]
                    ,
                    [
                        'class' => 'btn btn-sm btn-danger',
                    ],
                )
                ?>

                <hr>

                Тип картинки:
                <?php
                try {
                    echo
                    SiteModeReader::colorLabel($model->color);
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        'SiteModeReader_colorLabel', UPLOAD_IMAGE_LAYOUT, $e,
                    );
                }
                ?>
            
            <?php
            endif;
        ?>

    </div>
    
    <?php
        
        if (!$model->photo && $model->site_id === Parametr::siteId()) : ?>

            <div class='card-body p-4'>
                
                <?= ModalHelper::uploadImage($model->id)
                ?>
                
                <?= $this->render(
                    '/layouts/images/_uploadForm',
                    [
                        'model'      => $model,
                        'uploadForm' => $uploadForm,
                    ],
                )
                ?>
            </div>
        
        <?php
        endif ?>

</div>
