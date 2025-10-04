<?php
    
    use backend\helpers\ModalHelper;
    use core\helpers\ParentHelper;
    use core\edit\entities\Utils\Photo;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Photo */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $actionId string */
    
    const CONTENT_PHOTO_PART_LAYOUT = '#content_photo_partView';
    echo PrintHelper::layout(CONTENT_PHOTO_PART_LAYOUT);

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
                                                'attribute' => 'parent_id',
                                                'value'     => static
                                                function (
                                                    Photo $model,
                                                ) {
                                                    return Html::a(
                                                        ParentHelper::getModel($model->parent_id, $model->type_id)->name,
                                                        [
                                                            ParentHelper::getParentView($model),
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
                                            //'name',
                                            //'description',
                                            
                                            [
                                                'attribute' => 'reference',
                                                'value'
                                                            => static
                                                function (
                                                    Photo $model,
                                                ) {
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
                                    'DetailView-widget ', CONTENT_PHOTO_PART_LAYOUT, $e,
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

        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Изображение
                </strong>
            </div>
            <div class='card-body'>
                <?php
                    try {
                        echo
                        Html::img(
                            $model->getImageUrl(3),
                            [
                                'class' => 'img-fluid',
                            ],
                        );
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                            $actionId, 'model->getImageUrl ' . CONTENT_PHOTO_PART_LAYOUT, $e,
                        );
                    } ?>
            </div>

            <div class="card-footer">
                <?= ModalHelper::changeImage();
                    echo $this->render(
                        '../../../views/utils/photo/_uploadForm',
                        [
                            'model'      => $model,
                            'uploadForm' => $uploadForm,
                        ],
                    )
                ?>
            </div>
        </div>

    </div>
</div>
