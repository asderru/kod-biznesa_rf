<?php
    
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use kartik\file\FileInput;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Gallery */
    /* @var $videoForm core\edit\forms\UploadVideoForm */
    /* @var $textType int */
    
    const GALLERY_VIDEO_LAYOUT = '#utils_gallery_partVideo';
    echo PrintHelper::layout(GALLERY_VIDEO_LAYOUT);
    
    $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
    
    $image = null;
    
    try {
        $image = $model->getImageUrl(6);
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'model->getImageUrl ', GALLERY_VIDEO_LAYOUT, $e,
        );
    }
    
    $form                      = ActiveForm::begin(
        [
            'options' => ['enctype' => 'multipart/form-data'],
        ],
    )

?>
    <div class='row mb-3'>

        <div class='col-xl-6'>

            <div class='card mb-3'>

                <div class='card-header bg-light'>
                    <div class='row align-items-center'>
                        <div class='col-6'>
                            <span class='text-muted'>Тип родительской модели:</span>
                        </div>
                        <div class='col-6'>
                            <strong>
                                <?= TypeHelper::getName($parent::TEXT_TYPE) ?>
                            </strong>
                        </div>
                    </div>
                    <div class="row align-items-center mt-2">
                        <div class="col-6">
                            <span class="text-muted">Название родительской модели:</span>
                        </div>
                        <div class="col-6">
                            <strong>
                                <?= Html::a($parent->name, $parent->viewUrl)
                                ?>
                            </strong>
                        </div>
                    </div>
                </div>

                <div class='card-header bg-light'>
                    Галерея: <?= Html::a(
                        $model->name,
                        [
                            '/utils/gallery/view',
                            'id' => $model->id,
                        ],
                    )
                    ?>
                </div>

                <div class='card-body'>
                    
                    <?= $form->field($videoForm, 'name')->textInput(
                        [
                            'maxlength' => true,
                            'required'  => true,
                        ],
                    )->label('Название видео');
                    ?>
                    <?= $form->field($videoForm, 'description')->textarea(['rows' => 3])->label('Краткое описание под видео');
                    ?>
                    <?= $form->field($videoForm, 'text')->textarea(['rows' => 4])->label('Текст');
                    ?>
                    <?= $form->field($videoForm, 'reference')->textInput(['maxlength' => true])->label('Ссылка при необходимости')
                    ?>

                </div>

            </div>

        </div>

        <!--####### Widget фотографий ###############################-->

        <div class='col-xl-6'>

            <div class='card mb-3'>

                <div class="card-body">
                    
                    <?php
                        try {
                            echo $form->field($videoForm, 'videoFile')->widget(FileInput::class, [
                                'options'       => ['accept' => 'video/*'],
                                'pluginOptions' => [
                                    'showPreview'           => true,
                                    'showCaption'           => true,
                                    'showRemove'            => true,
                                    'showUpload'            => false,
                                    'allowedFileExtensions' => ['mp4', 'webm', 'mov'],
                                    'maxFileSize'           => 102400, // 100MB в KB
                                ],
                            ]);
                        }
                        catch (Exception $e) {
                            PrintHelper::exception(
                                'GridView-widget ', GALLERY_VIDEO_LAYOUT, $e,
                            );
                        } ?>
                </div>

                <div class="card-footer">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
                    ?>
                </div>

            </div>

        </div>

        <!--####### Конец виджета фотографий ########################-->

    </div>

<?php
    ActiveForm::end();
