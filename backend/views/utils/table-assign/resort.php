<?php
    
    use backend\helpers\ModalHelper;
    use backend\helpers\StatusHelper;
    use core\edit\assignments\TableAssignment;
    use core\edit\search\Content\TableAssignmentSearch;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $searchModel TableAssignmentSearch */
    /* @var $parent Model */
    /* @var $model core\edit\forms\SortForm */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_tableAssign_resort';
    
    $this->title = 'Таблицы для ' . $parent->name;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Все таблицы',
        'url'   => [
            'utils/table/index',
        ],
    ];
    $this->params['breadcrumbs'][] = $this->title;
    
    $tables = $dataProvider->getModels();
    
    $object_id = array_column($tables, 'id')['0'];
    
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
?>

<div class='card bg-light mb-3'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= Html::a(
                'Вернуться к таблицам',
                [
                    'view',
                    'id' => $object_id,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-secondary mr-1 mb-1',
                ],
            )
            ?>
            <?= Html::a(
                'Вернуться на ' . $parent->name,
                [
                    $parent->viewUrl,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-dark mr-1 mb-1',
                ],
            )
            ?>
        </div>
    </div>
    <div class="card-body">
        <?=
            // Форма выбора количества строк
            
            $this->render(
                '/layouts/partials/_pageSize',
            );
        ?>
        <div class='table-responsive'>
            <?php
                try {
                    echo
                    SortableGridView::widget(
                        [
                            'pager' => [
                                'firstPageLabel' => 'в начало',
                                'lastPageLabel'  => 'в конец',
                            ],
                            
                            'dataProvider' => $dataProvider,
                            'filterModel'  => $searchModel,
                            'layout'       => "{summary}\n{pager}\n{items}\n{pager}",
                            'tableOptions' => ['class' => 'table table-striped table-bordered'],
                            'columns'      => [
                                [
                                    'attribute' => 'sort',
                                    'label'     => 'Номер по порядку',
                                    'value'     => static function (TableAssignment $model) {
                                        return $model->sort . ' -
                                        <button
                                                type = "button"
                                                class="badge bg-primary"
                                                data-bs-toggle = "modal"
                                                data-bs-target = "#sortModal"
                                                data-bs-whatever=' . $model->id . '
                                                >
                                                изменить вручную
                                                </button >';
                                    },
                                    'format'    => 'raw',
                                ],
                                [
                                    'attribute' => 'name',
                                    'label'     => 'Название таблицы',
                                    'value'     => static function (TableAssignment $model) {
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
                                'id',
                                [
                                    'attribute' => 'status',
                                    'label'     => 'статус',
                                    'filter'    => StatusHelper::simpleList(),
                                    'value'     => static function (TableAssignment $model) {
                                        return
                                            StatusHelper::statusLabel($model->status)
                                            . '<hr>' .
                                            StatusHelper::activation($model->id, $model->status);
                                    },
                                    'format'    => 'raw',
                                ],
                            ],
                        ],
                    );
                }
                catch (Exception|Throwable $e) {
                    PrintHelper::exception(
                        'Widget SortableGridView', $e,
                    );
                } ?>

        </div>

    </div>

</div>


<!-- Modal -->
<?= ModalHelper::setSort($model)
?>
