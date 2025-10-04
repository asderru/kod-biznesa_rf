<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\assignments\TableAssignment;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $parent Model */
    /* @var $models TableAssignment[] */
    
    const UTILS_TABLE_ASSIGN_PART_LAYOUT = '#utils_tableAssign_partView';
    echo PrintHelper::layout(UTILS_TABLE_ASSIGN_PART_LAYOUT);

?>

<div class='row'>
    <?php
        foreach ($models as $model) { ?>
            <div class='col-xl-6'>
                <div class='card mb-3'>
                    <div class='card-header bg-light'>
                        <strong>
                            <?= Html::encode($model->name)
                            ?>
                        </strong>
                    </div>
                    <div class='card-body'>

                        <div class='table-responsive'>
                            <div class='table'>
                                
                                <?php
                                    try {
                                        echo DetailView::widget(
                                            [
                                                'model'      => $model,
                                                'attributes' => [
                                                    'name',
                                                    [
                                                        'attribute' => 'description',
                                                        'format'    => 'raw',
                                                    ],
                                                    [
                                                        'attribute' => 'status',
                                                        'label'     => 'статус',
                                                        'value'     => static function (TableAssignment $model) {
                                                            return
                                                                StatusHelper::statusLabel($model->status);
                                                        },
                                                        'format'    => 'raw',
                                                    ],
                                                    'sort',
                                                ],
                                            ],
                                        );
                                    }
                                    catch (Throwable $e) {
                                        PrintHelper::exception(
                                            'DetailView-widget ', UTILS_TABLE_ASSIGN_PART_LAYOUT, $e,
                                        );
                                    } ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <?= ButtonHelper::update($model->id, 'Редактировать связь с таблицей')
                        ?>
                        <?= ButtonHelper::activation($model)
                        ?>
                        <?= Html::a(
                            'Перейти на таблицу',
                            [
                                '/utils/table/view',
                                'id' => $model->table_id,
                            ],
                            [
                                'class'  => 'btn btn-sm btn-outline-primary',
                                'target' => '_blank',
                            ],
                        )
                        ?>
                        <?= ButtonHelper::delete($model)
                        ?>
                    </div>
                </div>
            </div>
            <?php
        } ?>

</div>
