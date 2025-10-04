<?php
    
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use backend\widgets\PagerWidget;
    use core\edit\assignments\TableAssignment;
    use core\edit\forms\Utils\Table\AssignTableEditForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model AssignTableEditForm */
    /* @var $tableAss TableAssignment */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    
    const LAYOUT_ID = '#utils_tableAssign_update';
    
    $this->title                   = 'Правка связанной таблицы для : ' .
                                     $tableAss->parent->name;
    $this->params['breadcrumbs'][] = [
        'label' => 'Таблицы',
        'url'   => [
            '/utils/table/index',
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $tableAss->parent->name,
        'url'   => [
            'view',
            'id'       => $tableAss->id,
            'siteId'   => $tableAss->site_id,
            'typeId'   => $tableAss->text_type,
            'parentId' => $tableAss->parent_id,
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
                'model'  => $tableAss,
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
            'model'    => $tableAss,
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
    TinyHelper::getDescription();
