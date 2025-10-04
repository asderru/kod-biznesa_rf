<?php
    
    use backend\helpers\StatusHelper;
    use backend\widgets\PagerWidget;
    use core\edit\entities\Admin\Edit;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model Edit */
    /* @var $parent Model|Chapter|Razdel */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_edit_view';
    
    $this->title                   = 'Информация по правке ' . $parent->name;
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [];
    
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
    
    try {
        echo
        PagerWidget::widget(
            [
                'model'  => $model,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_viewHeaderModel',
        [
            'model' => $model,
            'textType' => $textType,
            'buttons'  => $buttons, // передаем массив кнопок
        ],
    )
?>

    <div class='card-body'>
        <?php
            try {
                echo DetailView::widget([
                    'model'      => $model,
                    'attributes' => [
                        'id',
                        'site_id',
                        [
                            'attribute' => 'text_type',
                            'value'     => function ($model) {
                                return TypeHelper::typeNameArray()[$model->text_type] ?? $model->text_type;
                            },
                            'format'    => 'raw',
                        ],
                        'parent_id',
                        'content_id',
                        [
                            'attribute' => 'name',
                            'format'    => 'raw',
                            'value'     => function ($model) {
                                return Html::a(
                                    $model->name,
                                    [
                                        TypeHelper::getLongEditUrl($model->text_type) . 'view',
                                        'id' => $model->id,
                                    ], ['target' => '_blank'],
                                );
                            },
                        ],
                        [
                            'attribute' => 'url',
                            'format'    => 'raw',
                            'value'     => function ($model) {
                                return Html::a($model->url, $model->url, ['target' => '_blank']);
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'value'     => function ($model) {
                                return StatusHelper::statusList()[$model->status] ?? $model->status;
                            },
                        ],
                        [
                            'attribute' => 'edit_type',
                            'value'     => function ($model) {
                                return TypeHelper::editList()[$model->edit_type] ?? $model->edit_type;
                            },
                            'format'    => 'raw',
                        ],
                        'words',
                        [
                            'label'  => 'Добавлено слов',
                            'value'  => function ($model) {
                                $editWords  = $model->getEditWords($model->id);
                                $colorClass = $editWords > 0 ? 'text-success' : ($editWords < 0 ? 'text-danger' : '');
                                $icon       = '';
                                if ($editWords > 0) {
                                    $icon = '<i class="bi bi-arrow-up-short"></i> ';
                                }
                                elseif ($editWords < 0) {
                                    $icon = '<i class="bi bi-arrow-down-short"></i> ';
                                }
                                return Html::tag('span', $icon . abs($editWords), ['class' => $colorClass]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Время редактирования',
                            'value' => function ($model) {
                                return $model->getEditTime(true); // Используем длинный формат
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'value'     => function ($model) {
                                return $model->getCreatedTime(true);
                            },
                        ],
                        [
                            'attribute' => 'updated_at',
                            'value'     => function ($model) {
                                return $model->getUpdatedTime(true);
                            },
                        ],
                    ],
                ]);
            }
            catch (Throwable $e) {
                PrintHelper::exception(
                    'DetailView-widget ', LAYOUT_ID, $e,
                );
            } ?>
    </div>

<?php
    echo '</div>';
