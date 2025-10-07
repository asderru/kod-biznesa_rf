<?php

    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use backend\tools\TinyHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;

    /* @var $this yii\web\View */
    /* @var $author core\edit\entities\Library\Author */
    /* @var $model core\edit\forms\Library\AuthorForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */

    const LAYOUT_ID = '#library_author_update_';

    $this->title = $author->name . '. Правка';

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

            <div class="row mb-3">

                <div class="col-xl-4">
                    <div class="card h-100">
                        <div class="card-body">

                            <?= $form->field($model, 'name')->textInput(
                                    [
                                            'maxlength' => true,
                                            'required'  => true,
                                    ],
                            )
                                     ->label('Никнейм/псевдоним')
                            ?>

                            <?= $form->field($model, 'title')->textInput(['maxlength' => true])
                                     ->label('ФИО')
                            ?>

                        </div>

                    </div>
                </div>


                <div class='col-xl-8'>
                    <div class='card-header bg-body-secondary'>
                        <strong>Описание</strong>
                    </div>
                    <div class='card-body'>

                        <?= $form->field(
                                $model, 'description',
                                [
                                        'inputOptions' => [
                                                'id' => 'description-edit-area',
                                        ],
                                ],
                        )
                                 ->textarea(
                                         [
                                                 'rows' => 10,
                                         ],
                                 )
                                 ->label(false)
                        ?>
                    </div>
                    <div class='card-footer'>
                        <?= ButtonHelper::submit()
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    echo Html::activeHiddenInput(
            $model, 'slug',
            [
                    'value' => $author->slug,
            ],
    );
    echo $form->field($model, 'site_id')->hiddenInput(['value' => Parametr::siteId()])->label(false);
    echo $form->field($model, 'website')->hiddenInput(['value' => 'https://xn----8sbckhlgm6ae4b.xn--p1ai/'])->label(false);
    echo $form->field($model, 'contact')->hiddenInput(['value' => Parametr::siteName()])->label(false);
    echo $form->field($model, 'typeId')->hiddenInput(['value' => 1])->label(false);
    echo $form->field($model, 'text')->hiddenInput(['value' => 'text'])->label(false);
    echo $form->field($model->tags, 'textNew')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model->tags, 'existing')->hiddenInput(['value' => ''])->label(false);

    ActiveForm::end();
