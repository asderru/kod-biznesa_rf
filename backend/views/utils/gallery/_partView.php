<?php
    
    use core\helpers\FaviconHelper;
    use backend\helpers\StatusHelper;
    use backend\widgets\NoteWidget;
    use consynki\yii\input\ImageInput;
    use core\edit\entities\Utils\Photo;
    use core\edit\search\Utils\PhotoSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Gallery */
    /* @var $mediaFile core\edit\entities\Utils\Photo */
    /* @var $photosForm core\edit\forms\UploadMultiPhotosForm */
    /* @var $searchModel PhotoSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $textType int */
    
    const UTILS_GALLERY_PART_LAYOUT = '#utils_gallery_partView';
    echo PrintHelper::layout(UTILS_GALLERY_PART_LAYOUT);
    
    $width  = Yii::$app->params['galleryPhotoWidth'];
    $height = Yii::$app->params['galleryPhotoHeight'];
    
    $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
    
    $image = null;
    
    try {
        $image = $model->getImageUrl(6);
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'model->getImageUrl', UTILS_GALLERY_PART_LAYOUT, $e,
        );
    }
?>

<div class='row mb-3'>

    <div class='col-xl-6'>

        <div class='card mb-3'>

            <div class='card-header bg-light'>
                
                <?php
                    if ($image) {
                        try {
                            echo
                            Html::a(
                                Html::img(
                                    $image,
                                    ['class' => 'img-fluid',],
                                ),
                                $model->getImageUrl(12),
                                ['target' => '_blank',],
                            );
                        }
                        catch (Throwable $e) {
                            PrintHelper::exception(
                                'model->getImageUrl ', UTILS_GALLERY_PART_LAYOUT, $e,
                            );
                        }
                    }
                ?>
            </div>
            <div class='card-body'>
                <?php
                    if ($parent) { ?>
                        <div class='row align-items-center'>
                            <div class='col-6'>
                                <span class='text-muted'>Тип родительской модели:</span>
                            </div>
                            <div class='col-6'>
                                <strong>
                                    <?
                                        TypeHelper::getName($parent::TEXT_TYPE) ?>
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
                        <?php
                    } ?>
                <div class="text-center py-2">
                    
                    <?php
                        if (!$image) {
                            echo Html::a(
                                'Добавить обложку',
                                [
                                    'image',
                                    'id' => $model->id,
                                ]
                                ,
                                [
                                    'class' => 'btn btn-sm btn-primary',
                                ],
                            );
                        }
                        else {
                            echo Html::a(
                                'Удалить обложку',
                                [
                                    'delete-photo',
                                    'id' => $model->id,
                                ]
                                ,
                                [
                                    'class' => 'btn btn-sm btn-danger',
                                ],
                            );
                        }
                    ?>
                </div>
            </div>


            <div class='card-body'>

                <div class='table-responsive'>
                    <div class='table'>
                        
                        <?= $this->render(
                            '_partDetailView',
                            [
                                'model'  => $model,
                                'parent' => $parent,
                            ],
                        )
                        ?>
                    </div>

                </div>

            </div>
        </div>
        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Описание галереи
                </strong>
            </div>

            <div class='card-body'>
                <?= FormatHelper::asHtml($model->description)
                ?>
            </div>
        </div>


        <!--######  NoteWidget <div class='card mb-3'> ##########-->
        <?php
            try {
                echo
                NoteWidget::widget(
                    [
                        'parent' => $model,
                        'title'  => 'Заметки, закрепленные за галереей',
                    ],
                );
            }
            catch (Throwable $e) {
                PrintHelper::exception(
                    ' NoteWidget ', UTILS_GALLERY_PART_LAYOUT, $e,
                );
            } ?>
        
        
        <?= $this->render(
                '/layouts/templates/_textWidget',
                [
                    'model' => $model,
                ],
            );
        ?>
    </div>

    <div class='col-xl-6'>
        <!--####### Widget фотографий ###############################-->


        <div class='card mb-3'>
            <div class='card-header text-white bg-primary'>
                Картинки (размер
                <?= $width ?> *
                <?= $height ?>
                пикс.)
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                        foreach ($model->media as $mediaFile): ?>
                            <div class='col-lg-4 col-md-6 col-sm-12 mb-3 text-center'>
                                <p>#<?= $mediaFile->id ?>.
                                    
                                    <?= StatusHelper::statusBadgeLabel($mediaFile->status) ?>
                                </p>


                                <div class="col-12">
                                    <?php
                                        if ($mediaFile->media_type === Constant::MEDIA_TYPE_VIDEO): ?>
                                            <?php
                                            try {
                                                echo Html::a(
                                                    Html::tag(
                                                        'picture',
                                                        Html::tag('source', '', [
                                                            'srcset' => $mediaFile->getVideoThumbnailUrl()['webp'],
                                                            'type'   => 'image/webp',
                                                        ]) .
                                                        Html::img(
                                                            $mediaFile->getVideoThumbnailUrl()['jpg'], // fallback
                                                            ['class' => 'img-fluid'],
                                                        ),
                                                    ),
                                                    $mediaFile->getVideoUrl(),
                                                    ['target' => '_blank'],
                                                );
                                            }
                                            catch (Exception $e) {
                                                PrintHelper::exception(
                                                    'mediaFile->getVideoThumbnailUrl ', UTILS_GALLERY_PART_LAYOUT, $e,
                                                );
                                            }
                                            
                                            ?>
                                        
                                        <?php
                                        endif; ?>
                                    <?php
                                        if ($mediaFile->media_type === Constant::MEDIA_TYPE_PHOTO): ?>
                                            <?php
                                            try {
                                                echo Html::a(
                                                    Html::img(
                                                        $mediaFile->getImageUrl(3),
                                                        ['class' => 'img-fluid'],
                                                    ),
                                                    $mediaFile->getImageUrl(12),
                                                    ['target' => '_blank'],
                                                );
                                            }
                                            catch (Throwable $e) {
                                                PrintHelper::exception(
                                                    'mediaFile->getImageUrl ', UTILS_GALLERY_PART_LAYOUT, $e,
                                                );
                                            }
                                            ?>
                                        <?php
                                        endif; ?>

                                </div>

                                <div class='d-flex justify-content-around border-bottom pb-2'>

                                    <span class='p-1'>
                                        <?= FaviconHelper::movePhotoUp($model->id, $mediaFile->id)
                                        ?>
                                    </span>
                                    <span class='p-1'>
                                        <?= FaviconHelper::deletePhotos($model->id, $mediaFile->id)
                                        ?>
                                    </span>
                                    <span class='p-1'>
                                        <?= FaviconHelper::movePhotoDown($model->id, $mediaFile->id)
                                        ?>
                                    </span>
                                </div>

                            </div>
                        
                        <?php
                        endforeach; ?>
                </div>

            </div>

        </div>
        <hr>
        <div class="card-body">
            <?php
                $form
                    = ActiveForm::begin(
                    [
                        'options' => ['enctype' => 'multipart/form-data'],
                    ],
                ); ?>
            <?php
                try {
                    echo
                    $form
                        ->field($photosForm, 'files[]')
                        ->label(false)
                        ->widget(
                            ImageInput::class,
                            [
                                'options' => [
                                    'accept'   => 'image/jpg, image/jpeg',
                                    'multiple' => true,
                                ],
                            ],
                        )
                    ;
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        ' Widget загрузки изображений', UTILS_GALLERY_PART_LAYOUT, $e,
                    );
                }
            ?>
            
            <?= Html::activeHiddenInput(
                $photosForm, 'siteId',
                ['value' => $model->site_id],
            )
            ?>
            
            <?= ButtonHelper::submit()
            ?>
            <?php
                ActiveForm::end();
            ?>

        </div>

        <div class='card-footer text-center'>
            <p>
                Добавить можно не больше 8 картинок с расширением JPG и не
                больше 250кБ за раз!
            </p>
        </div>

        <div class='card-footer text-center'>
            <?= Html::a(
                'Загрузить видео',
                [
                    'video',
                    'id' => $model->id,
                ],
                [
                    'class' => 'btn btn-sm btn-primary',
                ],
            )
            ?>
        </div>
    </div>

    <!--####### Конец виджета фотографий ########################-->

    <div class='card mb-3'>
        <div class='card-body'>
            <?=
                // Форма выбора количества строк
                
                $this->render(
                    '/layouts/partials/_pageSize',
                );
            ?>
            <div class='table-responsive'>
                <?php
                    try {
                        echo
                        GridView::widget(
                            [
                                'pager'          => [
                                    'firstPageLabel' => 'в начало',
                                    'lastPageLabel'  => 'в конец',
                                ],
                                'dataProvider'   => $dataProvider,
                                'caption'        => Html::encode($this->title),
                                'captionOptions' => ['class' => 'text-end p-2',],
                                'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                                'summaryOptions' => ['class' => 'bg-secondary text-white p-1',],
                                
                                'tableOptions' => [
                                    'id'    => 'point-of-grid-view',
                                    'class' => 'table table-striped table-bordered',
                                ],
                                'columns'      => [
                                    ['class' => SerialColumn::class],
                                    
                                    [
                                        'attribute' => 'name',
                                        'label'     => 'Название',
                                        'value'     => static function (
                                            Photo $model,
                                        ) {
                                            return Html::encode
                                                (
                                                    $model->name ?? 'Без названия - ' . $model->id,
                                                ) . '<hr>'
                                                   . FaviconHelper::view($model::TEXT_TYPE, $model->id)
                                                   . FaviconHelper::update($model::TEXT_TYPE, $model->id);
                                            
                                        },
                                        'format'    => 'raw',
                                    ],
                                    'id',
                                    //'site_id',
                                    
                                    'photo',
                                    //'description',
                                    //'reference',
                                    [
                                        'attribute' => 'status',
                                        'label'     => 'статус',
                                        'filter'    =>
                                            StatusHelper::statusList(),
                                        'value'     => static function (Photo $model) {
                                            return
                                                StatusHelper::statusLabel($model->status)
                                                . '<hr>' .
                                                StatusHelper::activation($model->id, $model->status);
                                        },
                                        'format'    => 'raw',
                                    ],
                                    //'color',
                                    //'sort',
                                    //'updated_at',
                                ],
                            ],
                        );
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                            'GridView-widget ', UTILS_GALLERY_PART_LAYOUT, $e,
                        );
                    } ?>
            </div>

        </div>
        <div class="card-footer">
            <?= ButtonHelper::galleryResort(
                $model->id,
            )
            ?>
        </div>
    </div>

</div>
