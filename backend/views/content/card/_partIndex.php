<?php


    use core\edit\entities\Content\ContentCard;
    use core\edit\search\Content\CardSearch;
    use core\helpers\ModelHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\helpers\types\TypeUrlHelper;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;

    /* @var $this yii\web\View */
    /* @var $searchModel CardSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $books array */
    /* @var $url string */
    /* @var $textType int */
    /* @var $full bool */
    
    const CONTENTCARD_PARTINDEX_LAYOUT = '#content_card_partIndex';
    echo PrintHelper::layout(CONTENTCARD_PARTINDEX_LAYOUT) .
         $this->render('/layouts/partials/_pageSize', ['url' => 'index']);
?>

<div class='table-responsive'>
    <?php
        echo
        GridView::widget(
            [
                'pager'          => [
                    'firstPageLabel' => 'в начало',
                    'lastPageLabel'  => 'в конец',
                ],
                'dataProvider'   => $dataProvider,
                'filterModel'    => $searchModel,
                'caption'        => 'Тексты',
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
                    [
                        'attribute' => 'id',
                        'label'     => 'ID',
                        'value'     => 'id',
                        'filter'    => true,
                    ],
                    [
                        'attribute' => 'name',
                        'label'     => 'Название',
                        'value'     => static function (ContentCard $model) {
                            return Html::a(
                                $model->name,
                                [
                                    'view',
                                    'id' => $model->id,
                                ],
                            );
                            
                        },
                        'format'    => 'raw',
                    ],
                    [
                        'attribute' => 'updated_at',
                        'label'     => 'Время обновления',
                        'format'    => 'dateTime',
                    ],
                    [
                        'attribute' => 'text_type',
                        'label'     => 'Тип контента',
                        'value'     => static function (ContentCard $model) {
                            return TypeHelper::getName($model->text_type);
                        },
                    ],
                    [
                        'attribute' => 'parent_id',
                        'label'     => 'Контент',
                        'value'     => static function (ContentCard $model) {
                            $parent = ModelHelper::getModel($model->text_type, $model->parent_id);
                            return Html::a(
                                $parent->name,
                                [
                                        TypeUrlHelper::getLongEditUrl($model->text_type) . 'view',
                                    'id' => $model->parent_id,
                                
                                ],
                            );
                        },
                        'format'    => 'raw',
                    ],
                ],
            ],
        );
    
    ?>
</div>
