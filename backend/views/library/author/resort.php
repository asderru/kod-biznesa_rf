<?php
    
    use backend\helpers\ModalHelper;
    use core\edit\entities\Library\Author;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\IconHelper;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $searchModel core\edit\search\Library\AuthorSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $model core\edit\forms\SortForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_author_resort';
    
    $this->title = 'Сортировка авторов. Сайт ' . $site->name . '. #' . $site->id;
    
    $this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
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
            <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($this->title)
            ?>
        </div>
        <div>
            <?= ButtonHelper::createType($textType, $site->id, 'Добавить', 'success') ?>
            <?= ButtonHelper::clearCache($site->id, $textType) ?>
            <?= ButtonHelper::refreshSort($site->id); ?>
            <?= ButtonHelper::collapse()
            ?>
        </div>
    </div>
    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?= ButtonHelper::urlTo(
            'Экспресс-панель ' . $label,
            '/express/author/index',
            'btn-outline-warning text-dark',
        )
        ?>
        <?=
            ButtonHelper::structure($textType, null, 'Структура авторов')
        ?>
    </div>
    <div class='card-body'>
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
                                    'value'     => static function (Author $model) {
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
                                    'value'          => static function (Author $model) {
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
                                    'label'          => 'Изображение',
                                    'format'         => 'raw',
                                    'contentOptions' => [
                                        'style' => 'width: 130px',
                                    ],
                                ],
                                [
                                    'attribute' => 'name',
                                    'label'     => 'Имя (псевдоним)',
                                    'value'     => static function (Author $model) {
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
                        $actionId, 'Widget GridView ' . LAYOUT_ID, $e,
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
<?= ModalHelper::setSort($model)
?>
