<?php
    
    use backend\helpers\StatusHelper;
    use backend\widgets\NoteWidget;
    use backend\widgets\PagerWidget;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\AppHelper;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $isActive bool */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $model core\edit\entities\Admin\Information */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    $status   = $model->status;
    $isActive = $status > Constant::STATUS_DRAFT;
    
    const LAYOUT_ID = '#admin_information_view';
    $this->title = 'Сайт ' . $model->name;
    
    $this->params['breadcrumbs'][] = $model->name;
    

?>
    <div class="card">
    <div class='card-body'>


        <div class='row'>

            <div class='col-xl-6'>
                <?=
                    $this->render(
                        '/layouts/templates/_textWidget',
                        [
                            'model' => $model,
                        ],
                    )
                ?>
                <div class="card">
                    <div class="card-body">
                        <?= ButtonHelper::update(110, 'Редактировать') ?>
                    </div>
                </div>
                
            </div>

            <div class='col-xl-6'>
                <!--####### Одна картинка #############-->
                <?= $this->render(
                    '/layouts/images/_images',
                    [
                        'model'      => $model,
                        'uploadForm' => $uploadForm,
                    ],
                )
                ?>
                
                <!--####### Конец картинки ######################-->


                <div class='card mb-3'>

                    <div class='card-header bg-light'>
                        <strong>
                            Внутренняя информация по сайту
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?php
                            try {
                                echo DetailView::widget(
                                    [
                                        'model'      => $model,
                                        'attributes' => [
                                            'id',
                                            'text_type',
                                            'user_id',
                                            [
                                                'attribute' => 'parent.name',
                                                'label'     => 'Родительский сайт',
                                                'visible'   => !$model->isRoot(),
                                            ],
                                            'name',
                                            'title',
                                            [
                                                'attribute' => 'status',
                                                'value'     => StatusHelper::statusLabel($model->status),
                                                'format'    => 'raw',
                                                'label'     => 'Статус',
                                            ],
                                            [
                                                'attribute' => 'site_mode',
                                                'value'     => AppHelper::statusApp($model->site_mode),
                                                'format'    => 'raw',
                                                'label'     => 'Режим',
                                            ],
                                            [
                                                'attribute' => 'app_type',
                                                'value'     => AppHelper::statusMode($model->app_type),
                                                'format'    => 'raw',
                                                'label'     => 'Тип',
                                            ],
                                            //'photo',
                                            'color',
                                            //'updated_at',
                                            //'status',
                                            //'rating',
                                            'tree',
                                            'lft',
                                            'rgt',
                                            'depth',
                                            //'count_stock',
                                            //'content_id',
                                        ],
                                    ],
                                );
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'DetailView::widget ', LAYOUT_ID, $e,
                                );
                            } ?>

                    </div>
                    <?php
                        if ($model->hasGallery()) { ?>
                            <div class='card-footer'>
                                <strong>Галерея для сайта:</strong>
                                <br>
                                <?= ButtonHelper::gallery($model)
                                ?>
                            </div>
                            <?php
                        } ?>
                </div>
                <!--############  NoteWidget  #####################################-->
                
                <?php
                    try {
                        echo
                        NoteWidget::widget(
                            [
                                'parent' => $model,
                                'title'  => 'Заметки, закрепленные за сайтом',
                            ],
                        );
                    }
                    catch (Exception|Throwable $e) {
                        PrintHelper::exception(
                            'NoteWidget::widget',
                            LAYOUT_ID
                            , $e,
                        );
                    }
                ?>


                <!--############ Конец виджета заметок ##############################-->
            </div>

        </div>

        <div class="row">
            <div class='col-xl-6'>
                <div class='card mb-3'>

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
                            Рекламный профиль
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?= FormatHelper::asHtml($model->advert)
                        ?>
                    </div>
                </div>
            </div>
        </div>


    </div>


    </div>
