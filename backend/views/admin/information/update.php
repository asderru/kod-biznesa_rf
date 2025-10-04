<?php

    use backend\tools\TinyHelper;
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\SiteModeReader;
    use core\read\readers\Admin\InformationReader;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;

    /* @var $this yii\web\View */
    /* @var $info core\edit\entities\Admin\Information */
    /* @var $roles yii\rbac\Role */
    /* @var $model core\edit\forms\Admin\InformationForm */
    /* @var $actionId string */
    /* @var $prefix string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $siteId int */
    /* @var $textType int */

    const LAYOUT_ID = '#admin_information_update';
    $this->title = $info->name . ' Правка информации';

    $siteId = $info->id ?? Parametr::siteId();

    $this->params['breadcrumbs'][] = [
            'label' => $this->title,
            'url'   => ['view', 'id' => $siteId],
    ];
    $this->params['breadcrumbs'][] = 'Правка';

    echo $this->render(
            '/layouts/tops/_infoHeader',
            [
                    'label'    => $label,
                    'textType' => $textType,
                    'prefix'   => $prefix,
                    'actionId' => $actionId,
                    'layoutId' => LAYOUT_ID,
            ],
    );

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

                <div class="col-xl-6">

                    <div class='card'>

                        <div class='card-header bg-body-secondary'>
                            <strong>Текст на баннере</strong>
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
                                                     'rows'      => 25,
                                                     'maxlength' => true,
                                             ],
                                     )
                                     ->label(false)
                            ?>
                        </div>

                        <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                            <?= ButtonHelper::submit()
                            ?>
                        </div>
                    </div>

                </div>

                <div class='col-xl-6'>
                    <div class='card h-100'>

                        <div class="card-header bg-body-secondary">
                            <strong>Девиз сайта</strong>
                            <small>(не больше 120 знаков)</small>
                        </div>

                        <div class='card-body'>
                            <?= $form->field($model, 'title')
                                     ->textarea(
                                             [
                                                     'rows'      => 3,
                                                     'maxlength' => true,
                                             ],
                                     )
                                     ->label(false)
                            ?>
                        </div>

                        <div class='card-header bg-body-secondary'>
                            <strong>Текст в блоке Контакты</strong>
                        </div>
                        <div class='card-body'>
                            <?= $form->field(
                                    $model, 'description',
                            )
                                     ->textarea(
                                             [
                                                     'rows'      => 8,
                                                     'maxlength' => 255,
                                             ],
                                     )
                                     ->label(false)
                            ?>

                        </div>

                        <div class='card-header bg-body-secondary'>
                            <strong>Дополнительный текст о сайте (не более 500 знаков)</strong>
                        </div>
                        <div class='card-body'>
                            <?= $form->field(
                                    $model, 'advert',
                            )
                                     ->textarea(
                                             [
                                                     'rows'      => 6,
                                                     'maxlength' => 255,
                                             ],
                                     )
                                     ->label(false)
                            ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
<?php

    echo Html::activeHiddenInput(
            $model, 'parentId',
            [
                    'value' => Constant::STATUS_ROOT,
            ],
    );

    echo Html::activeHiddenInput(
            $model, 'siteMode',
            [
                    'value' => $info->site_mode,
            ],
    );

    echo Html::activeHiddenInput(
            $model, 'appType',
            [
                    'value' => $info->app_type,
            ],
    );

    echo Html::activeHiddenInput(
            $model, 'color',
            [
                    'value' => $info->color,
            ],
    );

    echo Html::activeHiddenInput(
            $model, 'color',
            [
                    'value' => $info->color,
            ],
    );
?>


<?php
    ActiveForm::end();
