<?php
    
    use yii\grid\SerialColumn;
    use core\edit\entities\Utils\Table;
    use core\edit\search\Utils\TableSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use himiklab\sortablegrid\SortableGridView;
    use yii\grid\ActionColumn;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $searchModel TableSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_table_resort';
    
    $this->title = $label;
    
    $this->params['breadcrumbs'][] = ['label' => 'Таблицы', 'url' => ['index']];
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

<div class='card'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::create()
            ?>
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
                                ['class' => SerialColumn::class],
                                
                                [
                                    'attribute' => 'name',
                                    'label'     => 'Название',
                                    'value'     => static function (
                                        Table $model,
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
                                
                                [
                                    'class'    => ActionColumn::class,
                                    'template' => '{update} {delete}',
                                ],
                            ],
                        ],
                    );
                }
                catch (Throwable $e) {
                    PrintHelper::exception(
                        'GridView-widget ', LAYOUT_ID, $e,
                    );
                } ?>
        </div>
    </div>

</div>
