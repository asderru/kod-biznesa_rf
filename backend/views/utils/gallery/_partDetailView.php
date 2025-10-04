<?php
    
    use core\edit\entities\Utils\Gallery;
    use core\edit\search\Utils\PhotoSearch;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Gallery */
    /* @var $parent Model */
    /* @var $uploadForm core\edit\forms\UploadPhotoForm */
    /* @var $searchModel PhotoSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const GALLERY_PARTDETAIL_LAYOUT = '#utils_gallery_partDetailView';
    echo PrintHelper::layout(GALLERY_PARTDETAIL_LAYOUT);
    
    try {
        echo DetailView::widget(
            [
                'model'      => $model,
                'attributes' => [
                    'id',
                    [
                        // Получение имени типа текста через хелпер
                        'value'  => static function ($model) {
                            return $model->text_type ? TypeHelper::getName($model->text_type) : '(не указано)';
                        },
                        'label'  => 'Тип родительского текста',
                        'format' => 'raw',
                    ],
                    [
                        // Генерация ссылки для родительского текста
                        'value'  => static function () use ($model, $parent) {
                            return Html::a(
                                Html::encode($parent->name),
                                TypeHelper::getView($model->text_type, $model->parent_id),
                            );
                        },
                        'label'  => 'Родительская модель',
                        'format' => 'raw',
                    ],
                    'name',
                    'slug',
                    'title',
                    //'description:ntext',
                    //'created_at',
                    [
                        'attribute' => 'created_at',
                        'format'    => 'dateTime',
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format'    => 'dateTime',
                    ],
                    [
                        'attribute' => 'reference',
                        'value'     => static function (Gallery $model) {
                            return Html::a(
                                $model->reference,
                                [
                                    $model->reference,
                                ],
                                [
                                    'target' => '_blank',
                                ],
                            );
                            
                        },
                        'format'    => 'raw',
                    ],                                                    //'status',
                    //'color',
                    'sort',
                ],
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'DetailView-widget ', GALLERY_PARTDETAIL_LAYOUT, $e,
        );
    }
