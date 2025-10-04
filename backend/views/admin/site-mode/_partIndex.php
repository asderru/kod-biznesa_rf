<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\search\Admin\SiteModeSearch;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel SiteModeSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const ADMIN_SITEMODE_PARTINDEX_LAYOUT = '#admin_siteMode_partIndex';
    echo PrintHelper::layout(ADMIN_SITEMODE_PARTINDEX_LAYOUT);

?>

<?= $this->render(
    '/layouts/partials/_pageSize',
);
?>

<div class='table-responsive'>
    <?php
        try {
            echo
            GridView::widget(
                [
                    'pager'          => [
                        'firstPageLabel' => 'в начало',
                        'lastPageLabel'  => 'в конец',
                    ],
                    'dataProvider'   => $dataProvider,
                    'filterModel'    => $searchModel,
                    'caption'        => Html::encode($this->title),
                    'captionOptions' => [
                        'class' => 'bg-secondary text-white p-2',
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
                        
                        [
                            'attribute' => 'name',
                            'label'     => 'Название',
                            'value'     => static function (
                                core\edit\entities\Admin\SiteMode $model,
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
                        'name',
                        'description',
                        'lft',
                        'rgt',
                        //'depth',
                        
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
                'GridView-widget ', ADMIN_SITEMODE_PARTINDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
