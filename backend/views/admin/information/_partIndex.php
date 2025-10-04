<?php
    
    use backend\assets\TopScriptAsset;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Admin\Information;
    use core\helpers\IconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\InformationReader;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel core\edit\search\Admin\InformationSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const ADMIN_INFO_PARTINDEX_LAYOUT = '#admin_information_partIndex';
    echo PrintHelper::layout(ADMIN_INFO_PARTINDEX_LAYOUT);

?>

<?= $this->render(
    '/layouts/partials/_pageSize',
);
?>

<div class='table-responsive'>
    <?php
        try {
            
            echo GridView::widget([
                'pager'          => [
                    'firstPageLabel' => 'в начало',
                    'lastPageLabel'  => 'в конец',
                ],
                'dataProvider'   => $dataProvider,
                'filterModel'    => $searchModel,
                'caption'        => Html::encode($this->title),
                'captionOptions' => [
                    'class' => 'text-end p-2',
                ],
                'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                'summaryOptions' => [
                    'class' => 'bg-secondary text-white p-1',
                ],
                
                'tableOptions' => [
                    'id'    => 'point-of-grid-view',
                    'class' => 'table table-striped table-bordered',
                ],
                'columns'      => [
                    ['class' => SerialColumn::class],
                    
                    //'site_id',
                    //'user_id',
                    
                    [
                        'value'          => static function (Information $model) {
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
                        'attribute' => 'id',
                        'label'     => 'Сайт',
                        'value'     => static function (Information $model) {
                            return $model->getDepthReference();
                        },
                        'filter'    => InformationReader::getDropDownFilter(0),
                        'format'    => 'raw',
                    ],
                    [
                        'attribute' => 'depth',
                        'label'     => 'Глубина вложенности',
                        'filter'    =>
                            Information::getDepthAmount(),
                        'visible'   => ParametrHelper::isServer(),
                    ],
                    //'color',
                    //'updated_at',
                    [
                        'attribute' => 'status',
                        'label'     => 'статус',
                        'filter'    => StatusHelper::statusList(),
                        'value'     => static function (Information $model) {
                            return
                                StatusHelper::statusLabel($model->status)
                                . '<hr>' .
                                StatusHelper::activation($model->id, $model->status);
                        },
                        'format'    => 'raw',
                    ],
                    //'rating',
                    //'tree',
                    //'lft',
                    //'rgt',
                    //'depth',
                    //'title',
                    //'description:ntext',
                    //'text:ntext',
                    //'content_id',
                    
                    
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
                'GridView-widget ', ADMIN_INFO_PARTINDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
