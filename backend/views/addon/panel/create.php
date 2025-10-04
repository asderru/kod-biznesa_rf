<?php
    
    use backend\helpers\SelectHelper;
    use core\edit\forms\Addon\PanelForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model PanelForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#addon_panel_create';
    
    $this->title                   = 'Создание панели';
    $this->params['breadcrumbs'][] = ['label' => 'Панели', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
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
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_createHeader',
        [
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>

    <div class='card-body'>
        <div class='row mb-3'>

            <div class='col-xl-6'>

                <div class='card h-100'>

                    <div class='card-header bg-light'>
                        <strong>
                            Панель
                        </strong>
                    </div>

                    <div class='card-body'>
                        <?php
                            try {
                                echo
                                SelectHelper::getSites($form, $model, true);
                            }
                            catch (Exception $e) {
                                PrintHelper::exception(
                                    $actionId, 'Выбор сайта ' . LAYOUT_ID, $e,
                                );
                            } ?>
                        
                        <?= $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        )
                        ?>
                    </div>
                    <div class="card-footer">
                                <?= SelectHelper::status($form, $model) ?>
                    </div>


                </div>
            </div>


            <div class='col-xl-6'>

                <div class='card h-100'>
                    <div class='card-header bg-light'>
                        <strong>
                            Краткое описание
                        </strong>
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
                                 ->textarea()
                        ?>


                    </div>
                    <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                        <?= ButtonHelper::submit()
                        ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
