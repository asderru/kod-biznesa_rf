<?php
    
    use backend\helpers\ModalHelper;
    use core\edit\entities\Addon\Banner;
    use core\edit\search\Utils\PhotoSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $banner core\edit\entities\Utils\Gallery */
    /* @var $searchModel PhotoSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $model core\edit\forms\SortForm */
    /* @var $sites array */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID                = '#addon_banner_resort';
    $label = 'Сортировка ' . $site->name;
    
    $this->title = $label;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Баннеры',
        'url'   => ['index'],
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
                                    [
                                        'attribute' => 'name',
                                        'label'     => 'Название',
                                        'value'     => static function (
                                            Banner $model,
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
                                    'id',
                                    'site_id',
                                    'site_mode',
                                    'text_type',
                                    'parent_id',
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
