<?php

    use backend\assets\TopScriptAsset;
    use core\edit\editors\Admin\InformationEditor;
    use core\edit\entities\Content\Review;
    use core\edit\search\Content\ReviewSearch;
    use core\helpers\ModelHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\SessionHelper;
    use core\helpers\StatusHelper;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\helpers\Url;

    TopScriptAsset::register($this);

    /** @var yii\web\View $this */
    /** @var ReviewSearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $url string */

    const CONTENT_REVIEW_PARTINDEX_LAYOUT = '#content_review_partIndex';
    echo PrintHelper::layout(CONTENT_REVIEW_PARTINDEX_LAYOUT) .
         $this->render('/layouts/partials/_pageSize', ['url' => 'index']);
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
                                            'value'     => static function (Review $model) use ($url) {
                                                return Html::a(
                                                        Html::encode($model->name),
                                                        [
                                                                $url . 'view',
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
                                            'visible'   => ParametrHelper::isServer(),
                                    ],
                                    'user_id',
                                    [
                                            'attribute' => 'status',
                                            'label'     => 'статус',
                                            'value'     => static function (Review $model) use ($textType) {
                                                return
                                                        StatusHelper::statusLabel($model->status, $textType)
                                                        . '<hr>' .
                                                        StatusHelper::activation($model->id, $model->status);
                                            },
                                            'filter'    => StatusHelper::statusMap($textType),
                                            'format'    => 'raw',
                                    ],
                                    [
                                            'attribute' => 'site_id',
                                            'label'     => 'Сайт',
                                            'value'     => static function (Review $model) {
                                                return Html::a(
                                                        SessionHelper::getSiteName($model->site_id),
                                                        [
                                                                '/admin/information/view',
                                                                'id' => $model->site_id,
                                                        ],
                                                );
                                            },
                                            'format'    => 'raw',
                                            'filter'    => InformationEditor::getDropDownFilter(0),
                                            'visible'   => ParametrHelper::isServer(),
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
        }
        catch (Throwable $e) {
            PrintHelper::exception(
                    'GridView-widget ', CONTENT_REVIEW_PARTINDEX_LAYOUT, $e,
            );
        }
    ?>
</div>
