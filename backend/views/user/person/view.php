<?php

    use backend\widgets\PagerWidget;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\User\Person */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */

    const LAYOUT_ID = '#user_person_view';

    $this->title = $model->id;
?>

<div class="card">

    <div class='card-body'>

        <div class='row mb-3'>

            <div class='col-xl-6'>
                <div class='card h-100 mb-3'>

                    <div class='card-header bg-light'>
                        <strong>
                            Персона
                        </strong>
                    </div>
                    <div class='card-body'>

                        <div class='table-responsive'>

                            <?php
                                try {
                                    echo DetailView::widget(
                                            [
                                                    'model'      => $model,
                                                    'attributes' => [
                                                            'id',
                                                            [
                                                                    'attribute' => 'site.name',
                                                                    'label'     => 'Сайт',
                                                            ],
                                                            'first_name',
                                                            'last_name',
                                                            'name',
                                                    ],
                                            ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                            'DetailView-widget ', LAYOUT_ID, $e,
                                    );
                                } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class='col-xl-6'>

                <div class='card rounded-0'>
                    <div class='card-header bg-light'>
                        <strong>
                            Дополнительно
                        </strong>
                    </div>

                    <div class='card-body'>
                        <dl class='row'>
                            <dt class='col-sm-3'>Должность:</dt>
                            <dd class='col-sm-9'><?= FormatHelper::asHtml($model->position)
                                ?></dd>
                            <dt class='col-sm-3'>Компания:</dt>
                            <dd class='col-sm-9'><?= FormatHelper::asHtml($model->company)
                                ?></dd>
                            <dt class='col-sm-3'>Пользователь:</dt>
                            <dd class='col-sm-9'><?= $model->user->name ?></dd>
                        </dl>

                    </div>
                    <div class='card-header bg-light'>
                        <strong>
                            Описание (тег Descriptiion)
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?= FormatHelper::asHtml($model->description)
                        ?>
                    </div>
                    <div class="card-footer">
                       <?= ButtonHelper::update($model->id, 'Редактировать') ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
