<?php
    
    use backend\helpers\ModalHelper;
    use core\edit\entities\Utils\Photo;
    use core\edit\search\Utils\PhotoSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\IconHelper;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $gallery core\edit\entities\Utils\Gallery */
    /* @var $searchModel PhotoSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $model core\edit\forms\SortForm */
    /* @var $sites array */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_photo_resort';
    
    $label = 'Сортировка';
    
    $this->title                   = $label;
    $this->params['breadcrumbs'][] = [
        'label' => 'Галереи',
        'url'   => [
            '/utils/gallery/index',
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => 'Галерея ' . $gallery->name,
        'url'   => [
            '/utils/gallery/view',
            'id' => $gallery->id,
        ],
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
            <?= ButtonHelper::clearSort($gallery->id)
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
                                    'value'     => static function (Photo $model) {
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
                                    'label'     => 'Изображение',
                                    'value'     => static function (Photo $model) {
                                        return
                                            Html::img(
                                                $model->getImageUrl(1),
                                                [
                                                    'class' => 'img-fluid',
                                                ],
                                            )
                                            ??
                                            IconHelper::biEyeFill('смотреть');
                                    },
                                    'format'    => 'raw',
                                ],
                                [
                                    'attribute' => 'description',
                                    'format'    => 'raw',
                                ],
                            
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
<?= ModalHelper::setSort($model)
?>
