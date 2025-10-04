<?php
    
    use backend\tools\TinyHelper;
    use core\edit\entities\Utils\Column;
    use core\edit\entities\Utils\Row;
    use core\edit\entities\Utils\Table;
    use core\helpers\ButtonHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\Html;
    use yii\bootstrap5\ActiveForm;
    
    /* @var Table $table */
    /* @var Column $column */
    /* @var Row $row */
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Data */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_data_create';
    
    $this->title                   = 'Заполнить ячейку';
    $this->params['breadcrumbs'][] = [
        'label' => $table->name,
        'url'   => [
            '/utils/table/view',
            'id' => $table->id,
        ],
    ];
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
                        'value' => Parametr::siteId(),
                    ],
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'table_id',
                    [
                        'value' => $table->id,
                    ],
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'col_id',
                    [
                        'value' => $column->id,
                    ],
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'row_id',
                    [
                        'value' => $row->id,
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
                        <td><?= Html::encode($table->name)
                            ?></td>
                    </tr>
                    <tr>
                        <td>Название колонки:</td>
                        <td><?= Html::encode($column->name)
                            ?></td>
                    </tr>
                    <tr>
                        <td>Название строки:</td>
                        <td><?= Html::encode($row->name)
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
    TinyHelper::getText();
    TinyHelper::getDescription();
