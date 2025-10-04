<?php
    
    use backend\widgets\CheckImageSizesWidget;
    use consynki\yii\input\ImageInput;
    use core\edit\entities\Utils\Photo;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\SiteModeReader;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Photo */
    /* @var $uploadForm UploadPhotoForm */
    
    const UTILS_PHOTO_PART_LAYOUT = '#utils_photo_partView';
    echo PrintHelper::layout(UTILS_PHOTO_PART_LAYOUT);

?>

<div class='row'>

    <div class='col-xl-6'>

        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Информация
                </strong>
            </div>

            <div class='card-body'>
                <div class='table-responsive'>
                    <div class='table'>
                        
                        <?php
                            try {
                                echo DetailView::widget(
                                    [
                                        'model'      => $model,
                                        'attributes' => [
                                            'id',
                                            //'site_id',
                                            [
                                                'attribute' => 'galery_id',
                                                'value'     => static function (
                                                    Photo $model,
                                                ) {
                                                    return Html::a(
                                                        $model->gallery->name,
                                                        [
                                                            '/utils/gallery/view',
                                                            'id' => $model->gallery_id,
                                                        ],
                                                        [
                                                            'target' => '_blank',
                                                        ],
                                                    );
                                                    
                                                },
                                                'format'    => 'raw',
                                                'label'     => 'Галерея',
                                            ],
                                            'photo',
                                            'name',
                                            'description',
                                            [
                                                'attribute' => 'reference',
                                                'value'     => static function (Photo $model) {
                                                    return Html::a(
                                                        $model->reference,
                                                        [
                                                            $model->reference,
                                                        ],
                                                        [
                                                            'target' => '_blank',
                                                        ],
                                                    );
                                                    
                                                },
                                                'format'    => 'raw',
                                            ],
                                            'color',
                                            'sort',
                                            [
                                                'attribute' => 'updated_at',
                                                'format'    => 'datetime',
                                            
                                            ],
                                        
                                        ],
                                    ],
                                );
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'DetailView-widget ', UTILS_PHOTO_PART_LAYOUT, $e,
                                );
                            } ?>
                    </div>
                </div>
            </div>


            <div class='card-header bg-light'>
                <strong>
                    Описание
                </strong>
            </div>
            <div class='card-body'>
                <?= FormatHelper::asHtml($model->description)
                ?>
            </div>
        </div>
    </div>

    <div class='col-xl-6'>

        <div class="card">
            <div class="card-header bg-body-secondary">
                <small>адрес картинки:
                    <strong><?php
                            try {
                                echo $model->getImageUrl(6);
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'model->getImageUrl', UTILS_PHOTO_PART_LAYOUT, $e,
                                );
                            }
                        ?>
                    </strong>
                </small>
                <div>
                    <?php
                        try {
                            echo
                            CheckImageSizesWidget::widget(['model' => $model]);
                        }
                        catch (Throwable $e) {
                            PrintHelper::exception(
                                'CheckImageSizesWidget::widget', UTILS_PHOTO_PART_LAYOUT, $e,
                            );
                        } ?>
                </div>
            </div>
            <div class="card-body">
                <?php
                    try {
                        echo
                        Html::a(
                            Html::img(
                                $model->getImageUrl(6),
                                [
                                    'class' => 'img-fluid',
                                ],
                            ),
                            $model->getImageUrl(
                                12,
                            ),
                            [
                                'target' => '_blank',
                            ],
                        );
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                            'model->getImageUrl', UTILS_PHOTO_PART_LAYOUT, $e,
                        );
                    } ?>
            </div>
            <div class='card-footer d-flex justify-content-between'>
                Тип картинки:
                <?php
                    try {
                        echo
                        SiteModeReader::colorLabel($model->color);
                    }
                    catch (Exception $e) {
                        PrintHelper::exception(
                            'SiteModeReader_colorLabel', UTILS_PHOTO_PART_LAYOUT, $e,
                        );
                    }
                ?>
            </div>
            <?php
                $form = ActiveForm::begin(
                    [
                        'options' => [
                            'class'   => 'active__form',
                            'enctype' => 'multipart/form-data',
                        ],
                    ],
                );
                try {
                    echo
                    $form->field($uploadForm, 'imageFile')->widget(ImageInput::class, [
                        'options' => [
                            'accept' => 'image/jpeg, image/jpg, image/png, image/webp',
                        ], // Опции виджета, например, тип файлов
                    ])->label(false);
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        '#consynki\yii\input\ImageInput', UTILS_PHOTO_PART_LAYOUT, $e,
                    );
                } ?>
            
            <?= ButtonHelper::submit('Заменить фото')
            ?>
            
            <?php
                ActiveForm::end();
            ?>
        </div>
    </div>
</div>
