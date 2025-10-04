<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\Addon\Panel;
    use core\edit\search\Addon\PanelSearch;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $searchModel PanelSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const ADDON_PANEL_PARTINDEX_LAYOUT = '#addon_panel_partIndex';
    echo PrintHelper::layout(ADDON_PANEL_PARTINDEX_LAYOUT);
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
                    'tableOptions'   => [
                        'id'    => 'point-of-grid-view',
                        'class' => 'table table-striped table-bordered',
                    ],
                    'columns'        => [
                        ['class' => SerialColumn::class],
                        [
                            'attribute' => 'name',
                            'label'     => 'Название',
                            'value'     => static function (
                                Panel $model,
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
                        [
                            'label' => 'Баннеры',
                            'value' => static function (Panel $model) {
                                return $model->getBannerNames();
                            },
                        ],
                    ],
                ],
            );
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                'GridView-widget ', ADDON_PANEL_PARTINDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
