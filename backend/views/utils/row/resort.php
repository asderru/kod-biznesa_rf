<?php
    
    use core\helpers\ButtonHelper;
    use backend\helpers\ModalHelper;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Utils\Row;
    use core\edit\search\Utils\RowSearch;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $table core\edit\entities\Utils\Table */
    /* @var $searchModel RowSearch */
    /* @var $model core\edit\forms\SortForm */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_row_resort';
    
    $this->title = 'Сортировка ' . $table->name;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Таблицы',
        'url'   => [
            'utils/table/index',
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
?>
<div class='card bg-light mb-3'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= Html::a(
                'Вернуться на таблицу',
                [
                    'utils/table/view',
                    'id' => $table->id,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-secondary',
                ],
            )
            ?>
            <?= ButtonHelper::clearSort($table->id)
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
                                    'value'     => static function (Row $model) {
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
                                    'label'     => 'Название',
                                    'value'     => static function (Row $model) {
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
                                    'filter'    => StatusHelper::statusList(),
                                    'value'     => static function (Row $model) {
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
