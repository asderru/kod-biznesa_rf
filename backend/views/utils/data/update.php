<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Data */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_data_update';
    
    $this->title                   = 'Правка ячейки №: ' . $model->id;
    $this->params['breadcrumbs'][] = ['label' => 'Таблицы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = [
        'label' => $model->table->name,
        'url'   => [
            '/utils/table/view',
            'id' =>
                $model->table_id,
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
                'model' => $model,
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
            'model'    => $model,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>

    <div class='card-body'>
        <div class="row">

            <div class="col-xl-6">
                
                <?= $form->field(
                    $model, 'value',
                    [
                        'inputOptions' =>
                            [
                                'autofocus' => 'autofocus',
                                'tabindex'  => '1',
                            ],
                    ],
                )->textarea(
                    [
                        'rows' => 6,
                    ],
                )
                         ->label(false)
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'site_id',
                    [
                        'value' => $model->site_id,
                    ],
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'table_id',
                    [
                        'value' => $model->table_id,
                    ],
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'col_id',
                    [
                        'value' => $model->col_id,
                    ],
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'row_id',
                    [
                        'value' => $model->row_id,
                    ],
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'updated_at',
                    [
                        'value' => time(),
                    ],
                )
                ?>
                
                <?= ButtonHelper::submit()
                ?>
            </div>

            <div class="col-xl-6">
                <table class='table table-sm table-borderless table-striped'>
                    <tbody>
                    <tr>
                        <td>Название таблицы:</td>
                        <td><?= Html::encode($model->table->name)
                            ?></td>
                    </tr>
                    <tr>
                        <td>Название колонки:</td>
                        <td><?= Html::encode($model->column->name)
                            ?></td>
                    </tr>
                    <tr>
                        <td>Название строки:</td>
                        <td><?= Html::encode($model->row->name)
                            ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
