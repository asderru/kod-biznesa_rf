<?php
    
    use backend\helpers\ModalHelper;
    use core\edit\entities\Content\Review;
    use core\edit\search\Content\ReviewSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\IconHelper;
    use core\helpers\ImageHelper;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    use yii\db\ActiveRecord;
    
    /* @var $this yii\web\View */
    /* @var $parent ActiveRecord */
    /* @var $searchModel ReviewSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $model core\edit\forms\SortForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID                = '#content_review_resort';
    
    $this->title = 'Сортировка обзоров. ' . $parent->name;
    
    $this->params['breadcrumbs'][] = ['label' => 'Обзоры', 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Сортировка';
    
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
                <?= ButtonHelper::clearSort($parent->id)
                ?>
            </div>
        </div>

        <div class="card-body">
            <?=
                // Форма выбора количества строк
                
                $this->render(
                    '/layouts/partials/_pageSize',
                [
                    'url' => 'resort?typeId=' . $parent::TEXT_TYPE . '&parentId=' . $parent->id,
                ],
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
                                        'value'     => static function (Review $model) {
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
                                        'value'          => static function (Review $model) {
                                            return
                                                Html::img(
                                                    ImageHelper::getModelImageSource($model, 1),
                                                    [
                                                        'class' => 'img-fluid',
                                                    ],
                                                )
                                                ??
                                                IconHelper::biEyeFill('смотреть');
                                        },
                                        'label'          => 'Изображение',
                                        'format'         => 'raw',
                                        'contentOptions' => [
                                            'style' => 'width: 130px',
                                        ],
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'label'     => 'Название обзора',
                                        'value'     => static function (Review $model) {
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
                                    'website',
                                ],
                            ],
                        );
                    }
                    catch (Exception|Throwable $e) {
                        PrintHelper::exception(
                            'SortableGridView_widget', LAYOUT_ID, $e,
                        );
                    } ?>

            </div>
        </div>
        <div class='card-footer'>
            Сортируются только активные элементы. Сортировка возможна указанием нового порядкового номера
            либо перетаскиванием предметов мышкой.
            Массовые перетаскивания следует подтвердить нажатием на кнопку
            "Упорядочить сортировку".
        </div>
    </div>

    <!-- Modal -->
<?= ModalHelper::setSort($model);
