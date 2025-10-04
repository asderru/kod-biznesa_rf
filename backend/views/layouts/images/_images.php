<?php
    
    use backend\widgets\CheckImageSizesWidget;
    use consynki\yii\input\ImageInput;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\ImageHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\read\readers\Admin\SiteModeReader;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model Razdel|Category|Page|Group|Book|Section|Chapter */
    /* @var $uploadForm UploadPhotoForm */
    
    const IMAGES_LAYOUT = '#layouts_images_images';
    
    echo PrintHelper::layout(IMAGES_LAYOUT);
    
    $colorMod = $model->hasAttribute('color') && !empty($model->color) ? $model->color : 0;
?>
<div class='card'>
    <div class='card-header'>
        Рекомендуемый размер <?= TypeHelper::getPhotoSizes($model->textType, $model->site_id) ?>
    </div>
    <?php
        if ($model->photo):?>
            <div class="px-2">
                <small>адрес картинки:
                    <strong><?php
                            try {
                                echo $model->getImageUrl(6);
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'model->getImageUrl', LAYOUT_ID, $e,
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
                                'CheckImageSizesWidget', LAYOUT_ID, $e,
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
                               ImageHelper::getModelImageSource($model, 6),
                                [
                                    'class' => 'img-fluid',
                                ],
                            ),
                            ImageHelper::getModelImageSource($model, 12),
                            [
                                'target' => '_blank',
                            ],
                        );
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                            'getImageUrl', IMAGES_LAYOUT, $e,
                        );
                    } ?>
            </div>
            
            <?php
            if ($model->photo && $model->site_id === Parametr::siteId()) : ?>
                <div class='card-footer d-flex justify-content-between'>
                    <div class='p-1'>
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
                    </div>
                    <div class='p-1 text-center'>
                        Тип картинки:
                        <?php
                            try {
                                echo
                                SiteModeReader::colorLabel($colorMod);
                            }
                            catch (Exception $e) {
                                PrintHelper::exception(
                                    'SiteModeReader_colorLabel', IMAGES_LAYOUT, $e,
                                );
                            }
                        ?>
                        <br>
                        <?= Html::a(
                            'Сменить цвет',
                            [
                                'color',
                                'id' => $model->id,
                            ]
                            ,
                            [
                                'class' => 'btn btn-sm btn-outline-primary',
                            ],
                        )
                        ?>
                    </div>
                    <div class='p-1'>
                        <?= Html::a(
                            'См. доступные',
                            [
                                '/utils/picture/view',
                                'textType' => $model::TEXT_TYPE,
                                'parentId' => $model->id,
                            ]
                            ,
                            [
                                'class' => 'btn btn-sm btn-success',
                            ],
                        )
                        ?>
                    </div>
                </div>
            <?php
            endif;
            ?>

        <?php
        else:
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
                    '#consynki\yii\input\ImageInput', IMAGES_LAYOUT, $e,
                );
            } ?>
            
            
            <?= ButtonHelper::submit()
            ?>
            <button
                    type='button'
                    class='btn-sm btn-close'
                    data-bs-dismiss='modal'
                    aria-label='Закрыть'
            >
            </button>
            
            <?php
            ActiveForm::end();
        endif;
    ?>
</div>

<!-- Модальное окно -->
<div class='modal fade' id='imageModal' tabindex='-1' aria-labelledby='imageModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='imageModalLabel'>Просмотр изображения</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body text-center'>
                <img src='' id='modalImage' class='img-fluid' alt='Preview'>
            </div>
            <div class='modal-footer'>
                <div class='text-center w-100'>
                    <span id='imageDimensions'>Размер изображения: загрузка...</span>
                    <br>
                    <span id='imageFileSize'>Размер файла: загрузка...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalImage = document.getElementById('modalImage');
        const dimensionsSpan = document.getElementById('imageDimensions');
        const fileSizeSpan = document.getElementById('imageFileSize');

        // Обработчик клика по ссылкам с изображениями
        document.querySelectorAll('.image-status-badge[data-image-url]').forEach(function (element) {
            element.addEventListener('click', function (e) {
                e.preventDefault();
                const imageUrl = this.getAttribute('data-image-url');
                modalImage.src = imageUrl;

                // Сбрасываем информацию при начале загрузки
                dimensionsSpan.textContent = 'Размер изображения: загрузка...';
                fileSizeSpan.textContent = 'Размер файла: загрузка...';

                // Получаем размеры изображения после загрузки
                modalImage.onload = function () {
                    dimensionsSpan.textContent = `Размер изображения: ${this.naturalWidth}×${this.naturalHeight} px`;
                };

                fetch(`https://panel25.seowebdev.ru/proxy.php?url=${encodeURIComponent(imageUrl)}`)
                    .then(response => response.blob())
                    .then(blob => {
                        const fileSizeInKB = (blob.size).toFixed(2);
                        fileSizeSpan.textContent = `Размер файла: ${fileSizeInKB} KB`;
                    })
                    .catch(error => {
                        console.error('Ошибка при получении размера файла:', error);
                        fileSizeSpan.textContent = 'Размер файла: ошибка';
                    });

            });
        });

        // Очистка при закрытии модального окна
        const imageModal = document.getElementById('imageModal');
        imageModal.addEventListener('hidden.bs.modal', function () {
            modalImage.src = '';
            dimensionsSpan.textContent = 'Размер изображения: загрузка...';
            fileSizeSpan.textContent = 'Размер файла: загрузка...';
        });
    });

</script>
