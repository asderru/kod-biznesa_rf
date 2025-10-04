<?php
    
    use backend\assets\TopScriptAsset;
    use backend\tools\TinyHelper;
    use core\helpers\ButtonHelper;
    use backend\helpers\SelectHelper;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Utils\Column;
    use core\edit\forms\Utils\Table\ColumnForm;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $table core\edit\entities\Utils\Table */
    /* @var $model ColumnForm */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_column_create';
    
    $this->title                   = $table->name;
    $this->params['breadcrumbs'][] = [
        'label' => 'Таблицы',
        'url'   => [
            'utils/table/index',
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => 'Колонки',
        'url'   => [
            'index',
            'id' => $table->id,
        
        ],
    ];
    $this->params['breadcrumbs'][] = 'Добавить колонку';
    
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

            <div class='col-xl-6'>
                <div class='card h-100'>
                    <div class="card-header bg-body-secondary">

                        <div class='h5'>
                            Добавить колонку
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <?= $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
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
                            $model, 'tableId',
                            [
                                'value' => $table->id,
                            ],
                        )
                        ?>
                                <?= SelectHelper::status($form, $model) ?>
                        
                        <?= ButtonHelper::submit('Добавить колонку')
                        ?>


                    </div>


                </div>
            </div>

            <div class='col-xl-6'>
                <div class='card h-100'>
                    <div class="card-header bg-body-secondary">

                        <div class='h5'>
                            Колонки в таблице
                        </div>
                    </div>
                    <div class='card-body'>
                        <?=
                            $this->render(
                                '/layouts/partials/_pageSize',
                            );
                        ?>
                        <div class='table-responsive'>
                            <?php
                                try {
                                    echo
                                    GridView::widget(
                                        [
                                            'pager'          => [
                                                'firstPageLabel' => 'в начало',
                                                'lastPageLabel'  => 'в конец',
                                            ],
                                            'dataProvider'   => $dataProvider,
                                            'captionOptions' => [
                                                'class' => 'text-end p-2',
                                            ],
                                            'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                                            'summaryOptions' => [
                                                'class' => 'bg-secondary text-white p-1',
                                            ],
                                            
                                            'tableOptions' => [
                                                'id'    => 'point-of-grid-view',
                                                'class' => 'table table-striped table-bordered',
                                            ],
                                            'columns'      => [
                                                ['class' => SerialColumn::class],
                                                
                                                [
                                                    'attribute' => 'name',
                                                    'label'     => 'Название колонки',
                                                    'value'     => static function (
                                                        Column $model,
                                                    ) {
                                                        return Html::a(
                                                            Html::encode
                                                            (
                                                                $model->name,
                                                            ),
                                                            [
                                                                'view',
                                                                'id' => $model->id,
                                                            ],
                                                        );
                                                        
                                                    },
                                                    'format'    => 'raw',
                                                ],
                                                
                                                //'id',
                                                //'table_id',
                                                //'col_id',
                                                //'name',
                                                //'updated_at',
                                                //'sort',
                                                [
                                                    'attribute' => 'status',
                                                    'label'     => 'статус',
                                                    'value'     => static
                                                    function (
                                                        Column $model,
                                                    ) {
                                                        return
                                                            StatusHelper::statusLabel($model->status);
                                                    },
                                                    'format'    => 'raw',
                                                ],
                                                
                                                
                                                [
                                                    'class'    => ActionColumn::class,
                                                    'template' => '{update} {delete}',
                                                ],
                                            ],
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        'GridView-widget ', LAYOUT_ID, $e,
                                    );
                                } ?>
                        </div>
                    </div>
                </div>

                <div class='card-footer'>
                    <?= Html::a(
                        'Завершить добавление колонок',
                        [
                            'utils/table/view',
                            'id' => $table->id,
                        ],
                        [
                            'class' => 'btn  btn-danger',
                        ],
                    )
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
