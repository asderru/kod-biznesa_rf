<?php

    use core\helpers\StatusHelper;
    use core\edit\editors\User\PersonEditor;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Content\Review;
    use core\edit\search\Content\ReviewSearch;
    use core\helpers\BulkHelper;
    use core\helpers\ButtonHelper;
    use core\helpers\ModelHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\helpers\Url;

    /** @var yii\web\View $this */
    /** @var ReviewSearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */
    /* @var $sites Information[] */
    /* @var $siteId int */
    /* @var $url string */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_review_index';
    
    $this->title = $label;
    
    $buttons   = [
        ButtonHelper::expressType($textType, null, 'Экспресс-правка'),
        ButtonHelper::structure($textType, null,'Структура обзоров'),
        ButtonHelper::import($textType),
        ButtonHelper::export($textType),
        '<br>',
        ButtonHelper::clearCreatedDates()
    ];

    $buttons[] = BulkHelper::sessionButtons($textType);
    
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
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>

    <div class='card-body'>

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
                                'id',
                                [
                                    'attribute' => 'name',
                                    'label'     => 'Обзор',
                                    'value'     => static function (Review $model)  {
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
                                'vote',
                                [
                                    'attribute' => 'parent_id',
                                    'label'     => 'Тема обзора',
                                    'value'     => static function (Review $model) {
                                        $parent = $model->getParent();
                                        if ($parent) {
                                            return Html::a(
                                                Html::encode($parent->name),
                                                ModelHelper::getView($parent),
                                            );
                                        }
                                        return null;
                                    },
                                    'format'    => 'raw',
                                ],
                                [
                                    'attribute' => 'person_id',
                                    'label'     => 'Профиль',
                                    'value'     => static function (Review $model) {
                                        return Html::a(
                                                Html::encode($model->person->name),
                                                [
                                                    '/user/person/view',
                                                    'id' => $model->person_id,
                                                ],
                                            ) . '<hr>' . Html::encode($model->person->fullName);
                                    },
                                    'format'    => 'raw',
                                    'filter' => PersonEditor::getSimpleDropDownFilter(0, null, null, Constant::STATUS_BLOG),
                                    'visible'   => ParametrHelper::isServer(),
                                ],
                                'user_id',
                                [
                                    'attribute' => 'status',
                                    'label' => 'статус',
                                    'value' => static function (Review $model) use ($textType) {
                                        return
                                                StatusHelper::statusLabel($model->status, $textType)
                                                . '<hr>' .
                                                StatusHelper::activation($model->id, $model->status);
                                    },
                                    'filter' => StatusHelper::statusMap($textType),
                                    'format'    => 'raw',
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'format'    => 'dateTime',
                                
                                ],
                                [
                                    'class'      => ActionColumn::className(),
                                    'urlCreator' => static function ($action, Review $model) {
                                        return Url::toRoute([$action, 'id' => $model->id]);
                                    },
                                ],
                            ],
                        ],
                    );
            ?>
        </div>

    </div>

</div>
