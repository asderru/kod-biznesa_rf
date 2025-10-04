<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\edit\forms\Utils\Table\ColumnForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $table core\edit\entities\Utils\Table */
    /* @var $model ColumnForm */
    /* @var $column core\edit\entities\Utils\Column */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_column_update';
    
    $this->title = 'Переименовать колонку: ' . $column->name;
    
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
    $this->params['breadcrumbs'][] = 'Правка колонки';
    
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
                'model'  => $column,
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
            'model'    => $column,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>
    <div class='card-body'>
        <div class="row">
            <div class='col-xl-6'>
                <div class="card">

                    <div class='card-body'>
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

            <div class='col-xl-6'>
                
                <?= ButtonHelper::activation($column)
                ?>

            </div>


        </div>

    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
