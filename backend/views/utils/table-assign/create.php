<?php
    
    use core\helpers\ButtonHelper;
    use backend\widgets\TableWidget;
    use core\edit\entities\Utils\Table;
    use core\edit\forms\Utils\Table\AssignTableForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $siteId int */
    /* @var $typeId int */
    /* @var $parentId int */
    /* @var $tables Table */
    /* @var $form ActiveForm */
    /* @var $model AssignTableForm */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_tableAssign_create';
    
    $this->title                   = 'Выбрать';
    $this->params['breadcrumbs'][] = [
        'label' => 'Таблицы',
        'url'   => [
            '/utils/table/index',
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

            <div class="col-lg-3">

                <div class="card h-100">

                    <div class="card-header bg-body-secondary">
                        Выбрать таблицы (не более трех)
                    </div>

                    <div class='card-body'>
                        
                        <?= $form->field($model, 'tables')
                                 ->checkboxList
                                 (
                                     $model->tablesList(),
                                 )
                                 ->label(false)
                        ?>
                        
                        <?= Html::activeHiddenInput(
                            $model, 'site_id',
                            [
                                'value' => $siteId,
                            ],
                        )
                        ?>
                        
                        <?= Html::activeHiddenInput(
                            $model, 'typeId',
                            [
                                'value' => $typeId,
                            ],
                        )
                        ?>
                        
                        <?= $form
                            ->field($model, 'parentId')
                            ->hiddenInput(
                                [
                                    'value' => $parentId,
                                ],
                            )
                            ->label(
                                false,
                            )
                        ?>
                    </div>

                    <div class="card-footer">
                        <?= ButtonHelper::submit()
                        ?>
                    </div>

                </div>

            </div>

            <div class="col-lg-9">

                <div class="card">

                    <div class="card-header bg-body-secondary">
                        Все активные таблицы
                    </div>

                    <div class="card-body">

                        <div
                                class='accordion accordion-flush'
                                id='accordionFlushTables'
                        >
                            <?php
                                $i = 1;
                                foreach ($tables as $table) { ?>
                                    <div class='accordion-item'>
                                        <h5
                                                class='accordion-header'
                                                id='flush-heading<?= $table->id ?>'
                                        >
                                            <button
                                                    class='accordion-button collapsed'
                                                    type='button'
                                                    data-bs-toggle='collapse'
                                                    data-bs-target='#flush-collapse<?= $table->id ?>'
                                                    aria-expanded='false'
                                                    aria-controls='flush-collapse<?= $table->id ?>'
                                            >
                                                # <?= $i ?>
                                                . <?= $table->name ?>
                                            </button>
                                        </h5>
                                        <div
                                                id='flush-collapse<?= $table->id ?>'
                                                class='accordion-collapse collapse'
                                                aria-labelledby='flush-heading<?= $table->id ?>'
                                                data-bs-parent='#accordionFlushTables'
                                        >
                                            <div
                                                    class='accordion-body
												small'
                                            >
                                                <?php
                                                
                                                ?>
                                                <?php
                                                    try {
                                                        echo
                                                        TableWidget::widget(
                                                            [
                                                                'table' => $table,
                                                            ],
                                                        );
                                                    }
                                                    catch (Exception|Throwable $e) {
                                                        PrintHelper::exception(
                                                            'TableWidget', LAYOUT_ID, $e,
                                                        );
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php
                                    $i++;
                                } ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
