<?php
    
    use backend\tools\TinyHelper;
    use backend\widgets\PagerWidget;
    use core\edit\forms\Addon\PanelForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model PanelForm */
    /* @var $panel core\edit\entities\Addon\Panel */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    
    const LAYOUT_ID = '#addon_panel_update';
    
    $this->title                   = $panel->name . '. Правка';
    $this->params['breadcrumbs'][] = ['label' => 'Панели', 'url' => ['index']];
    $this->params['breadcrumbs'][] = [
        'label' => $panel->name,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
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
    
    try {
        echo
        PagerWidget::widget(
            [
                'model'  => $panel,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
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
        '/layouts/tops/_updateHeader',
        [
            'model'    => $panel,
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
                            Краткое описание
                        </strong>
                    </div>

                    <div class='card-body'>
                        
                        <?= $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        )
                        ?>
                        
                        <?= $form->field($model, 'description')->textInput(['maxlength' => true])
                        ?>

                    </div>
                </div>

            </div>

            <div class='col-xl-6'>

                <div class='card h-100'>
                    <div class='card-header bg-light'>
                        <strong>
                            Общая информация
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
    TinyHelper::getText();
    TinyHelper::getDescription();
