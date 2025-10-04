<?php
    
    use consynki\yii\input\ImageInput;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $model Model */
    /* @var $uploadForm UploadPhotoForm */
    
    $form = ActiveForm::begin(
        [
            'options' =>
                [
                    'enctype' => 'multipart/form-data',
                ],
        ],
    )
?>

<div
        class='modal fade' id='uploadModal' data-bs-backdrop='static'
        data-bs-keyboard='false' tabindex='-1'
        aria-labelledby='uploadModalLabel' aria-hidden='true'
>
    <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
        <div class='modal-content'>
            <div class='modal-header'>
                <p class='text-center'>Загрузить изображение для <br>
                    <?= Html::encode($model->name)
                    ?>
                    <br>
                    Размеры: ширина -
                    <strong><?= $model->getPhotoSize($model->site_id)->width
                        ?></strong>
                    пикселей,
                    высота
                    <strong><?= $model->getPhotoSize($model->site_id)->height ?></strong>
                    пикселей.
                </p>
            </div>
            <div class='modal-body'>
                
                <?php
                    try {
                        echo
                        $form->field($uploadForm, 'imageFile')
                             ->label(false)
                             ->widget(
                                 ImageInput::class,
                                 [
                                     'options' => [
                                         'accept' => 'image/jpg, image/jpeg, ',
                                     ],
                                 ],
                             )
                        ;
                    }
                    catch (Exception|Throwable $e) {
                        PrintHelper::exception('ImageInput::class', LAYOUT_ID . ' Widget загрузки изображений', $e);
                    } ?>

            </div>
            <div class='modal-footer'>
                <?= ButtonHelper::submit()
                ?>
                <button
                        type='button'
                        class='btn-sm btn-close'
                        data-bs-dismiss='modal'
                        aria-label='Закрыть'
                >
                </button>
            </div>
        </div>
    </div>
</div>

<?php
    ActiveForm::end()
?>
