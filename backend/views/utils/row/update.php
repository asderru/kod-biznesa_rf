<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\edit\forms\Utils\Table\RowForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $table core\edit\entities\Utils\Table */
    /* @var $model RowForm */
    /* @var $row core\edit\entities\Utils\Row */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_row_update';
    
    $this->title = 'Переименовать: ' . $row->name;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Таблицы',
        'url'   => [
            'utils/table/index',
        ],
    ];
    
    $this->params['breadcrumbs'][] = [
        'label' => $table->name,
        'url'   => [
            '/utils/table/view',
            'id' => $table->id,
        ],
    ];
    
    $this->params['breadcrumbs'][] = 'Правка строки';
    
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
                'model'  => $table,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
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
    );
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_updateHeader',
        [
            'model' => $row,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>
    
        <div class='card-body'>

            <div class="row">

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <?= $form->field($model, 'name')->textInput(
                                [
                                    'maxlength' => true,
                                    'required'  => true,
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

                <div class='col-md-4'>
                    
                    <?= ButtonHelper::activation($row)
                    ?>

                </div>
            </div>
        </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
