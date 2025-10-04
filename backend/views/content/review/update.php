<?php

    use core\edit\entities\Content\Review;
    use core\edit\forms\Content\ReviewForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\SiteModeReader;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;

    /** @var yii\web\View $this */
    /** @var Model $parent */
    /** @var ReviewForm $model */
    /** @var Review $review */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */

    const LAYOUT_ID = '#content_review_update';

    $this->title = 'Обновить обзор: ' . $parent->name;

    $this->params['breadcrumbs'][] = ['label' => 'Обзоры', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $review->name, 'url' => ['view', 'id' => $review->id]];
    $this->params['breadcrumbs'][] = 'Обновить';


    $form           = ActiveForm::begin(
            [
                    'options'     => [
                            'class' => 'active__form',
                    ],
                    'fieldConfig' => [
                            'errorOptions' => [
                                    'encode' => false,
                                    'class'  => 'help-block',
                            ],
                    ],
            ],
    );
?>
    <div class="card">
        <div class='card-body'>

            <div class='row mb-3'>

                <div class='col-xl-4'>

                    <div class='card '>

                        <div class='card-header bg-light'>
                            <strong>
                                Общая информация
                            </strong>
                        </div>

                        <div class='card-body'>


                            <?= $form->field($model, 'name')
                                     ->textInput(
                                             [
                                                     'maxlength' => true,
                                             ],
                                     )
                                     ->label('Заголовок обзора')
                            ?>

                            <?= $form->field($model, 'vote')
                                     ->textInput([
                                             'maxlength' => true,
                                             'type'      => 'number',
                                             'value'     => 1,
                                             'min'       => 1,
                                             'max'       => 5,
                                             'step'      => 1,
                                     ])
                            ?>
                        </div>
                    </div>

                </div>
                <div class='col-xl-6 col-lg-12'>

                    <div class='card mb-3'>

                        <div class='card-header bg-light'>
                            <strong>
                                Обзор
                            </strong>
                        </div>
                        <div class='card-body'>
                            <?= $form->field($model, 'text')->textarea(
                                    [
                                            'rows' =>
                                                    10,
                                    ],
                            )
                                     ->label(false)
                            ?>
                        </div>
                        <div class='card-footer d-flex justify-content-between'>
                            <?php
                                try {
                                    echo
                                    $form
                                            ->field($model, 'status')
                                            ->radioList(
                                                    SiteModeReader::getSimpleStatusesMap($textType),
                                                    [
                                                            'itemOptions' => [
                                                                    'labelOptions' => ['class' => 'radio-inline mr-3'],
                                                                    'class'        => 'mr-1',
                                                            ],
                                                            'value'       => $parent->status,
                                                    ],
                                            )
                                            ->label('Изменить статус:')
                                    ;
                                }
                                catch (Exception $e) {
                                    PrintHelper::exception(
                                            'SiteModeReader_getSimpleStatusesMap', LAYOUT_ID, $e,
                                    );
                                }
                            ?>
                            <div class='btn-group-sm text-end'>
                                <?= ButtonHelper::submit() ?>
                            </div>

                        </div>

                    </div>


                </div>

            </div>

        </div>
    </div>
<?php

    echo Html::activeHiddenInput(
            $model, 'site_id',
            [
                    'value' => Parametr::siteId(),
            ],
    );
    echo Html::activeHiddenInput(
            $model, 'textType',
            [
                    'value' => Constant::SITE_TYPE,
            ],
    );
    echo Html::activeHiddenInput(
            $model, 'parentId',
            [
                    'value' => Parametr::siteId(),
            ],
    );
    echo Html::activeHiddenInput(
            $model, 'person_id',
            [
                    'value' => $model->person_id,
            ],
    );

    ActiveForm::end();
