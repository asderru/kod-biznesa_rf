<?php
    
    use core\edit\entities\Tools\Keyword;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Tools\Keyword */
    
    const TOOLS_KEYWORD_PART_DETAIL_LAYOUT = '#tools_keyword_partDetailView';
    $label = 'Ключевые слова';
    
    try {
        echo DetailView::widget(
            [
                'model'      => $model,
                'attributes' => [
                    'id',
                    //'site_id',
                    [
                        'attribute' => 'text_type',
                        'value'     => static function (Keyword $keyword) {
                            return $keyword->getModelType()->name;
                        },
                        'format'    => 'raw',
                        'label'     => 'Тип текста',
                    ],
                    [
                        'value'  => static function (Keyword $keyword) {
                            $model = $keyword->parentModel;
                            return Html::a(
                                $model->name,
                                TypeHelper::getView($model),
                            );
                        },
                        'label'  => 'Родительская модель',
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format'    => 'dateTime',
                    ],
                    'sort',
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'DetailView-widget', TOOLS_KEYWORD_PART_DETAIL_LAYOUT, $e,
        );
    }
