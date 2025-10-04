<?php

    use backend\tools\TinyHelper;
    use backend\widgets\PagerWidget;
    use backend\widgets\select\SelectTagWidget;
    use core\edit\editors\Content\PageEditor;
    use core\edit\entities\Admin\Information;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;

    /* @var $this yii\web\View */
    /* @var $page core\edit\entities\Content\Page */
    /* @var $site Information */
    /* @var $model core\edit\forms\Content\PageForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */

    const LAYOUT_ID = '#content_page_update';

    $this->title = $page->name . '. Правка';

    $cardForm = $model->card;

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

        <?= $this->render(
                '/layouts/tops/_updateHeader',
                [
                        'model'    => $page,
                        'title'    => $this->title,
                        'textType' => $textType,
                ],
        )
        ?>

        <div class='card-body'>

            <div class='row mb-3'>

                <div class="col-xl-6">

                    <div class='card h-100'>
                        <div class='card-header bg-light'>
                            <strong>
                                Общая информация.
                            </strong>
                        </div>
                        <div class='card-body'>
                            <?= $form->field($model, 'name')->textInput(
                                    [
                                            'maxlength' => true,
                                            'required'  => true,
                                    ],
                            )->label('Название блока')
                            ?>



                            <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Полное название блока')
                            ?>

                        </div>
                    </div>

                </div>

                <div class="col-xl-6">
                    <div class="card-header">
                        Заголовок блока
                    </div>
                    <div class='card h-100'>
                        <?= $form->field($model, 'description', [
                                'options' => ['class' => 'mb-0'],
                        ])->textarea([
                                'rows'      => 6,
                                'maxlength' => $maxLetters ?? 511,
                                'value'     => $value ?? null,
                        ])->label(false) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class='card-header bg-light d-flex justify-content-between'>
            <strong>
                Текст блока
            </strong>
        </div>

        <div class='card-body'>

            <?= $form->field(
                    $model, 'text',
                    [
                            'inputOptions' => [
                                    'id' => 'text-edit-area',
                            ],
                    ],
            )
                     ->textarea(
                             [
                                     'rows' => 20,
                             ],
                     )
                     ->label(false)
            ?>

        </div>

        <div class='card-footer text-end'>
            <?= ButtonHelper::submit()
            ?>
        </div>

        <div class="row">
            <div class="col-lg-6">


                <?php
                    try {
                        echo SelectTagWidget::widget(
                                [
                                        'form'  => $form,
                                        'model' => $model->tags,
                                        'site'  => $site,
                                ],
                        );
                    }
                    catch (Throwable $e) {
                        PrintHelper::exception(
                                'SelectTagWidget', LAYOUT_ID, $e,
                        );

                    }
                ?>
            </div>
            <div class="col-lg-6">
                <?= $this->render(
                        '/layouts/partials/_brands',
                        [
                                'form'   => $form,
                                'model'  => $model->brands,
                                'siteId' => $page->site_id,
                        ],
                )
                ?>
            </div>
        </div>

        <div class='card-header bg-light'>
            <strong>
                Карточка контента
            </strong>
        </div>
        <div class='card-body'>
            <?= $this->render(
                    '/content/card/_form_card',
                    [
                            'form'     => $form,
                            'model'    => $cardForm,
                            'parentId' => $page->id,
                            'textType' => $textType,
                    ],
            );

            ?>
        </div>
    </div>

<?php
    echo Html::activeHiddenInput(
            $model, 'site_id',
            [
                    'value' => $page->site_id,
            ],
    );
    echo Html::activeHiddenInput(
            $model, 'slug',
            [
                    'value' => $page->slug,
            ],
    );
    echo Html::activeHiddenInput(
            $model, 'rating',
            [
                    'value' => 1,
            ],
    );
    echo Html::activeHiddenInput(
            $model, 'parentId',
            [
                    'value' => 1,
            ],
    );

    if ($cardForm->parent_id === null) {
        echo Html::activeHiddenInput(
                $cardForm, 'site_id',
                [
                        'value' => Parametr::siteId(),
                ],
        );
        echo Html::activeHiddenInput(
                $cardForm, 'text_type',
                [
                        'value' => Constant::PAGE_TYPE,
                ],
        );
        echo Html::activeHiddenInput(
                $cardForm, 'parent_id',
                [
                        'value' => null,
                ],
        );
        echo Html::activeHiddenInput(
                $cardForm, 'name',
                [
                        'value' => '',
                ],
        );
        echo Html::activeHiddenInput(
                $cardForm, 'description',
                [
                        'value' => '',
                ],
        );
        echo Html::activeHiddenInput(
                $cardForm, 'firstField',
                [
                        'value' => '',
                ],
        );
        echo Html::activeHiddenInput(
                $cardForm, 'secondField',
                [
                        'value' => '',
                ],
        );
        echo Html::activeHiddenInput(
                $cardForm, 'thirdField',
                [
                        'value' => '',
                ],
        );
        echo Html::activeHiddenInput(
                $cardForm, 'fourthField',
                [
                        'value' => '',
                ],
        );
        echo Html::activeHiddenInput(
                $cardForm, 'addedField',
                [
                        'value' => '',
                ],
        );
    }

    ActiveForm::end();
