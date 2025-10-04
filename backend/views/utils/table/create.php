<?php
    
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use core\edit\entities\Utils\Table;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model Table */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_table_create';
    
    $this->title                   = 'Создать таблицу';
    $this->params['breadcrumbs'][] = ['label' => 'Таблицы', 'url' => ['index']];
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

        <div class="card">

            <div class='card-body'>
                <div class='row'>
                    <div class='col-xl-6'>
                        <div class='card'>
                            <div class='card-body'>
                                
                                <?= $form->field($model, 'name')->textInput(
                                    [
                                        'maxlength' => true,
                                        'required'  => true,
                                    ],
                                )
                                ?>
                                
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

                            </div>
                            <div class="card-footer">
                                <?= SelectHelper::status($form, $model) ?>
                            </div>

                            <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                                <?= ButtonHelper::submit()
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class='col-xl-6'>
                        <div class='card'>
                            <div class='card-body'>
                                <?= $form->field(
                                    $model, 'text',
                                    [
                                        'inputOptions' => [
                                            'id' => 'text-edit-area',
                                        ],
                                    ],
                                )
                                         ->textarea()
                                ?>

                            </div>
                        </div>
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
