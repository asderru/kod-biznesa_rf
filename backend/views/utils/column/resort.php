<?php
    
    use backend\helpers\ModalHelper;
    use core\edit\entities\Utils\Column;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $table core\edit\entities\Utils\Table */
    /* @var $searchModel core\edit\search\Utils\ColumnSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $model core\edit\forms\SortForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID                = '#utils_column_resort';
    
    $this->title = 'Сортировка ' . $table->name;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Таблицы',
        'url'   => ['/utils/table/index'],
    ];
    $this->params['breadcrumbs'][] = $label;
    
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
                <?= ButtonHelper::create()
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
                                'layout'       => "{summary}\n{pager}\n{items}\n{pager}",
                                'tableOptions' => ['class' => 'table table-striped table-bordered'],
                                'columns'      => [
                                    [
                                        'attribute' => 'sort',
                                        'label'     => 'Номер по порядку',
                                        'value'     => static function (Column $model) {
                                            return $model->sort . ' -
                                    <button 
                                            type = "button" 
                                            class="badge bg-primary" 
                                            data-bs-toggle = "modal" 
                                            data-bs-target = "#sortModal"
                                            data-bs-whatever="' . $model->id . '"
                                            > 
                                            изменить вручную 
                                            </button >';
                                        },
                                        'format'    => 'raw',
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'label'     => 'Колонка',
                                        'value'     => static function (Column $model) {
                                            return Html::a(
                                                Html::encode($model->name),
                                                [
                                                    'view',
                                                    'id' => $model->id,
                                                ],
                                            );
                                        },
                                        'format'    => 'raw',
                                    ],
                                ],
                            ],
                        );
                    }
                    catch (Exception|Throwable $e) {
                        PrintHelper::exception(
                            $actionId, 'Widget GridView ' . LAYOUT_ID, $e,
                        );
                    } ?>
            </div>

        </div>
        <div class='card-footer'>
            <p class="small">
                Сортируются только активные элементы. Сортировка возможна указанием нового порядкового номера
                либо перетаскиванием предметов мышкой.
                Массовые перетаскивания следует подтвердить нажатием на кнопку
                'Упорядочить сортировку'
            </p>
        </div>
    </div>

    <!-- Modal -->
<?= ModalHelper::setSort($model);
