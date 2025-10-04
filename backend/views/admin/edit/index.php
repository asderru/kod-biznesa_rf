<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Admin\Edit;
    use core\edit\entities\Admin\Information;
    use core\edit\search\Admin\EditSearch;
    use core\helpers\IconHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    use yii\db\ActiveRecord;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    /* @var $this yii\web\View */
    /* @var $searchModel EditSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $sites array|Information|Information[]|ActiveRecord[] */
    
    const LAYOUT_ID = '#admin_edit_index';
    
    $buttons = [];
    
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
    
    <?= $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $this->title,
            'buttons'  => $buttons,
        ],
    )
    ?>

    <div class='card-body'>
        
        <?= $this->render(
            '/layouts/partials/_pageSize',
        );
        ?>

        <div class='table-responsive'>
            <?php
                try {
                    echo
                    GridView::widget([
                        'pager'          => [
                            'firstPageLabel' => 'в начало',
                            'lastPageLabel'  => 'в конец',
                        ],
                        'dataProvider'   => $dataProvider,
                        'filterModel'    => $searchModel,
                        'caption'        => $this->title,
                        'captionOptions' => [
                            'class' => 'text-start p-2',
                        ],
                        'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                        'summaryOptions' => [
                            'class' => 'bg-secondary text-white p-1',
                        ],
                        'tableOptions'   => [
                            'id'    => 'point-of-grid-view',
                            'class' => 'table table-striped table-bordered',
                        ],
                        'columns'        => [
                            ['class' => SerialColumn::class],
                            'id',
                            'site_id',
                            [
                                'attribute' => 'text_type',
                                'filter'    => TypeHelper::typeNameArray(),
                                'value'     => static function (Edit $model) {
                                    return TypeHelper::getName($model->text_type);
                                },
                                'format'    => 'raw',
                            ],
                            'parent_id',
                            [
                                'attribute' => 'name',
                                'label'     => 'Название текста',
                                'value'     => static function (Edit $model) {
                                    return Html::a(
                                        $model->name,
                                        [
                                            TypeHelper::getLongEditUrl($model->text_type) . 'view',
                                            'id' => $model->id,
                                        ],
                                    );
                                },
                                'format'    => 'raw',
                            ],
                            [
                                'attribute' => 'status',
                                'label'     => 'статус',
                                'filter'    =>
                                    StatusHelper::statusList(),
                                'value'     => static function (Edit $model) {
                                    return
                                        StatusHelper::statusLabel($model->status)
                                        . '<hr>' .
                                        StatusHelper::activation($model->id, $model->status);
                                },
                                'format'    => 'raw',
                            ],
                            [
                                'attribute' => 'edit_type',
                                'filter'    => TypeHelper::editList(),
                                'value'     => function ($model) {
                                    return TypeHelper::editList()[$model->edit_type] ?? $model->edit_type;
                                },
                            ],
                            'words',
                            [
                                'attribute' => 'created_at',
                                'filter'    => $this->render(
                                    '/layouts/partials/_date_filter', [
                                    'model'          => $searchModel,
                                    'attribute_from' => 'created_at_from',
                                    'attribute_to'   => 'created_at_to',
                                ],
                                ),
                                'label'     => 'Время создания',
                                'format'    => ['date', 'php:Y-m-d'],
                            ],
                            [
                                'attribute' => 'updated_at',
                                'filter'    => $this->render(
                                    '/layouts/partials/_date_filter', [
                                    'model'          => $searchModel,
                                    'attribute_from' => 'updated_at_from',
                                    'attribute_to'   => 'updated_at_to',
                                ],
                                ),
                                'label'     => 'Время правки',
                                'format'    => ['date', 'php:Y-m-d'],
                            ],
                            [
                                'class'    => 'yii\grid\ActionColumn',
                                'template' => '{view}{delete}',
                                'buttons'  => [
                                    'view'   => function ($url) {
                                        return Html::a(IconHelper::biEye('смотреть'), $url, [
                                            'title' => Yii::t('yii', 'View'),
                                            'class' => 'btn btn-sm btn-info',
                                        ]);
                                    },
                                    'delete' => function ($url) {
                                        return Html::a(IconHelper::biTrash('удалить'), $url, [
                                            'title'        => Yii::t('yii', 'Delete'),
                                            'class'        => 'btn btn-sm btn-danger',
                                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                            'data-method'  => 'post',
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]);
                }
                catch (Throwable $e) {
                    PrintHelper::exception(
                        'GridView-widget ', LAYOUT_ID, $e,
                    );
                }
            ?>
        </div>
    </div>

</div>
