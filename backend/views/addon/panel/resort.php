<?php
    
    use backend\helpers\ModalHelper;
    use core\edit\search\Utils\PhotoSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $searchModel PhotoSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $model core\edit\forms\SortForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID                = '#addon_panel_resort';
    
    $this->title = 'Сортировка панелей. Сайт ' . $site->name . '. #' . $site->id;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Панели',
        'url'   => [
            'index',
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
                <?= ButtonHelper::createType($textType, $site->id, 'Добавить', 'success') ?>
                <?= ButtonHelper::clearCache($site->id, $textType)
                ?>
                <?= ButtonHelper::clearSort($site->id)
                ?>
            </div>
        </div>

        <div class="card-body">

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
                                    
                                    'id',
                                    'user_id',
                                    'name',
                                    'description',
                                    'status',
                                
                                ],
                            ],
                        );
                    }
                    catch (Exception|Throwable $e) {
                        PrintHelper::exception(
                            'Widget GridView', $e,
                        );
                    } ?>
            </div>
        </div>

        <div class='card-footer'>
            Сортируются только активные элементы. Сортировка возможна указанием нового порядкового номера
            либо перетаскиванием предметов мышкой.
            Массовые перетаскивания следует подтвердить нажатием на кнопку
            'Упорядочить сортировку'.
        </div>
    </div>
    <!-- Modal -->
<?= ModalHelper::setSort($model);
