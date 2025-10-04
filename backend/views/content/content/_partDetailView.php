<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Content\Content;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\read\readers\Admin\SiteModeReader;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Content\Content */
    
    const LAYOUT_ID = '#content_content_partDetailView';
    $label = 'Контент';
    
    try {
        echo DetailView::widget(
            [
                'model'      => $model,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'site.name',
                        'label'     => 'Сайт',
                    ],
                    [
                        'attribute' => 'text_type',
                        'label'     => 'Тип текста',
                        'value'     => static function (Content $model) {
                            return TypeHelper::getName($model->text_type);
                        },
                        'format'    => 'raw',
                        'filter' => SiteModeReader::getTextTypesMap(),
                    ],
                    [
                        'value'  => static function (Content $content) {
                            $model = $content->parent;
                            return TypeHelper::getView($model->text_type, $model->parent_id);
                        },
                        'label'  => 'Источник',
                        'format' => 'raw',
                    ],
                    'name',
                    'title',
                    //'text:ntext',
                    [
                        'attribute' => 'description',
                        'format'    => 'raw',
                    ],
                    'url',
                    'picture_url',
                    'view_count',
                    'rating',
                    [
                        'attribute' => 'status',
                        'label'     => 'статус',
                        'value'     => static function (Content $model) {
                            return
                                StatusHelper::statusLabel($model->status);
                        },
                        'format'    => 'raw',
                    ],
                    [
                        'attribute' => 'created_at',
                        'format'    => 'dateTime',
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format'    => 'dateTime',
                    ],
                    'property_id',
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'DetailView-widget ', LAYOUT_ID, $e,
        );
    }
