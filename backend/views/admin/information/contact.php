<?php
    
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\widgets\MaskedInput;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Admin\ContactForm */
    /* @var $info core\edit\entities\Admin\Information */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    LAYOUT_ID    = '#admin_information_contact';
    $this->title = 'Редактировать контакты';
    
    $this->params['breadcrumbs'][] = $this->title;
    
    $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    );
?>

<h3 class='h3 mb-3'><?= Html::encode($this->title)
    ?></h3>

<div class="col-12">
    <?php
        
        $form = ActiveForm::begin(
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
        )
    ?>

    <div class='row'>
        <div class='col-xl-6'>
            <div class='card'>
                <div class='card-body'>
                    <?= $form
                        ->field(
                            $model,
                            'email',
                        )
                        ->textInput(
                            [
                                'type' => 'email',
                            ],
                        )
                    ?>                <?= $form
                        ->field
                        (
                            $model,
                            'zakazMail',
                        )
                        ->textInput(
                            [
                                'type' => 'email',
                            ],
                        )
                    ?>
                    <?php
                        try {
                            echo
                            $form
                                ->field(
                                    $model,
                                    'phone',
                                )->widget(
                                    MaskedInput::class,
                                    [
                                        'mask' => '+7 (999) 999-99-99',
                                    ],
                                )->label(
                                    'Контактный телефон. Вид +7 (495) 123-45-67',
                                )
                            ;
                        }
                        catch (Exception|Throwable $e) {
                            Yii::$app->errorHandler->logException($e);
                            Yii::$app->session->
                            setFlash(
                                'danger',
                                'Widget телефона.' . $e->getMessage(),
                            );
                        } ?>
                    <?= $form
                        ->field(
                            $model,
                            'address',
                        )
                        ->textarea(
                            [
                                'rows'      => 5,
                                'maxlength' => 255,
                            ],
                        )
                    ?>
                    <?= $form
                        ->field(
                            $model,
                            'metrica',
                        )
                        ->textInput(
                            [
                                'maxlength' => true,
                            ],
                        )
                    ?>
                    <?= $form
                        ->field(
                            $model,
                            'analytics',
                        )
                        ->textInput(
                            [
                                'maxlength' => true,
                            ],
                        )
                    ?>
                    
                    <?= $form
                        ->field(
                            $model,
                            'yandexMoney',
                        )
                        ->textInput(
                            [
                                'type' => 'number',
                            ],
                        )
                    ?>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    
                    
                    <?= Html::activeHiddenInput(
                        $model, 'yandexMap',
                        [
                            'value' => '',
                        ],
                    )
                    ?>
                    
                    <?= Html::activeHiddenInput(
                        $model, 'googleMap',
                        [
                            'value' => '',
                        ],
                    )
                    ?>
                    
                    <?= $form
                        ->field(
                            $model,
                            'facebook',
                        )
                        ->textarea(
                            [
                                'rows'      => 3,
                                'type'      => 'url',
                                'maxlength' => 255,
                            ],
                        )
                    ?>
                    <?= $form
                        ->field(
                            $model,
                            'vk',
                        )
                        ->textarea(
                            [
                                'rows'      => 3,
                                'type'      => 'url',
                                'maxlength' => 255,
                            ],
                        )
                    ?>
                    <?= $form
                        ->field(
                            $model,
                            'telegram',
                        )
                        ->textarea(
                            [
                                'rows'      => 3,
                                'type'      => 'url',
                                'maxlength' => 255,
                            ],
                        )
                    ?>
                    <?= $form
                        ->field(
                            $model,
                            'whatsup',
                        )
                        ->textarea(
                            [
                                'rows'      => 3,
                                'type'      => 'url',
                                'maxlength' => 255,
                            ],
                        )
                    ?>
                </div>
                <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                    <?= ButtonHelper::submit()
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php
        ActiveForm::end()
    ?>

</div>
