<?php
    
    use backend\helpers\ModalHelper;
    use core\edit\entities\Tools\Keyword;
    use core\edit\search\Tools\KeywordSearch;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $searchModel KeywordSearch */
    /* @var $model core\edit\entities\Tools\Keyword */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $keywordForm core\edit\forms\Tools\KeywordForm */
    /* @var $sites array */
    /* @var $siteId int */
    /* @var $typeId int */
    /* @var $parentId int */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_keyword_resort';
    
    $this->title = 'Сортировка';
    
    $this->params['breadcrumbs'][] = ['label' => 'Ключевые слова', 'url' => ['index']];
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
            <?= Html::a(
                'Обновить сортировку',
                [
                    'resort',
                    'siteId'   => $siteId,
                    'typeId'   => $typeId,
                    'parentId' => $parentId,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-secondary',
                ],
            )
            ?>
        </div>
    </div>
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
                                'value'     => static function (Keyword $model) {
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
                                'label'     => 'Ключевое слово',
                                'value'     => static function (Keyword $model) {
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
                            'id',
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

<!-- Modal -->
<?= ModalHelper::setSort($model)
?>
