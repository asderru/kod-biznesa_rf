<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Shop\Razdel;
    use core\edit\search\Blog\CategorySearch;
    use core\edit\search\Content\PageSearch;
    use core\edit\search\Forum\GroupSearch;
    use core\edit\search\Library\BookSearch;
    use core\edit\search\Magazin\SectionSearch;
    use core\edit\search\Shop\RazdelSearch;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    /* @var $searchModel RazdelSearch|SectionSearch|BookSearch|CategorySearch|GroupSearch|PageSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $textType int */
    /* @var $full bool */
    
    const LAYOUT_ID = '#site_view1';
    echo PrintHelper::layout(LAYOUT_ID);
    
    $caption = TypeHelper::getName($textType, null, false, true);
    $modelDir = TypeHelper::getLongEditUrl($textType);
    $expressDir = '/express/' . TypeHelper::getShortEditUrl($textType);
    
    // Форма выбора количества строк
    echo $this->render(
        '/layouts/partials/_pageSize',
    );
    
    try {
        echo GridView::widget([
            'pager'          => [
                'firstPageLabel' => 'в начало',
                'lastPageLabel'  => 'в конец',
            ],
            'dataProvider'   => $dataProvider,
            'filterModel'    => $searchModel,
            'caption'        => $caption,
            'captionOptions' => [
                'class' => 'text-start p-2',
            ],
            'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
            'summaryOptions' => [
                'class' => 'bg-secondary text-white p-1',
            ],
            'tableOptions'   => [
                'id'    => 'point-of-grid-view',
                'class' => 'table table-sm table-border border-light table-striped',
            ],
            'columns'        => [
                [
                    'class' => SerialColumn::class,
                ],
                [
                    'attribute' => 'name',
                    'label'     => 'Название',
                    'value'     => static function (Razdel|Section|Book|Category|Group|Page $model)
                    use ($modelDir) {
                        // Создаем отступ в зависимости от глубины модели
                        $indent = str_repeat('*&nbsp;&nbsp;', $model->depth - 1);
                        
                        // Возвращаем ссылку с отступом перед именем
                        return $indent . Html::a(
                                Html::encode($model->name),
                                [
                                    $modelDir . '/view',
                                    'id' => $model->id,
                                ],
                            );
                    },
                    'format'    => 'raw',
                ],
                [
                    'header'         => Html::button(
                        '<i class="bi bi-pencil-square"></i>',
                        [
                            'class' => 'btn btn-outline-primary toggle-visibility-btn',
                            'id'    => 'toggle-visibility-btn', // Уникальный ID кнопки
                        ],
                    ),
                    'headerOptions'  => ['style' => 'white-space: nowrap;'],
                    'contentOptions' => ['class' => 'text-center tools-column'],
                    'value'          => static function (Razdel|Section|Book|Category|Group|Page $model)
                    use ($modelDir, $expressDir) {
                        // Возвращаем кнопку с иконкой и всплывающей подсказкой, обернутую в div
                        return Html::a(
                                '<i class="bi bi-pencil-square"></i>',
                                [
                                    $expressDir . '/view',
                                    'id' => $model->id,
                                ],
                                [
                                    'class'             => 'btn btn-sm btn-warning text-dark',
                                    'type'              => 'button',
                                    'data-bs-toggle'    => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'data-bs-title'     => 'Экспресс-правка',
                                ],
                            )
                               . Html::tag(
                                'div',
                                Html::a(
                                    '<i class="bi bi-filetype-html"></i>',
                                    [
                                        $modelDir . 'updateHtml',
                                        'id' => $model->id,
                                    ],
                                    [
                                        'class'             => 'btn btn-sm btn-secondary',
                                        'type'              => 'button',
                                        'data-bs-toggle'    => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'data-bs-title'     => 'HTML-правка',
                                    ],
                                )
                                . Html::a(
                                    'Su',
                                    [
                                        'admin/update/view',
                                        'textType' => $model::TEXT_TYPE,
                                        'id'       => $model->id,
                                        'editId'   => 1,
                                    ],
                                    [
                                        'class'             => 'btn btn-sm btn-primary',
                                        'type'              => 'button',
                                        'data-bs-toggle'    => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'data-bs-title'     => 'SimpleMDE',
                                    ],
                                )
                                . Html::a(
                                    'Qu',
                                    [
                                        'admin/update/view',
                                        'textType' => $model::TEXT_TYPE,
                                        'id'       => $model->id,
                                        'editId'   => 2,
                                    ],
                                    [
                                        'class'             => 'btn btn-sm btn-info',
                                        'type'              => 'button',
                                        'data-bs-toggle'    => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'data-bs-title'     => 'Quill',
                                    ],
                                )
                                . Html::a(
                                    'CK',
                                    [
                                        'admin/update/view',
                                        'textType' => $model::TEXT_TYPE,
                                        'id'       => $model->id,
                                        'editId'   => 3,
                                    ],
                                    [
                                        'class'             => 'btn btn-sm btn-secondary',
                                        'type'              => 'button',
                                        'data-bs-toggle'    => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'data-bs-title'     => 'CKEditor',
                                    ],
                                )
                                . Html::a(
                                    'Tr',
                                    [
                                        'admin/update/view',
                                        'textType' => $model::TEXT_TYPE,
                                        'id'       => $model->id,
                                        'editId'   => 4,
                                    ],
                                    [
                                        'class'             => 'btn btn-sm btn-success',
                                        'type'              => 'button',
                                        'data-bs-toggle'    => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'data-bs-title'     => 'Trumbowyg',
                                    ],
                                )
                                . Html::a(
                                    'Im',
                                    [
                                        'admin/update/view',
                                        'textType' => $model::TEXT_TYPE,
                                        'id'       => $model->id,
                                        'editId'   => 5,
                                    ],
                                    [
                                        'class'             => 'btn btn-sm btn-dark',
                                        'type'              => 'button',
                                        'data-bs-toggle'    => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'data-bs-title'     => 'Imperavi',
                                    ],
                                )
                                . Html::a(
                                    'Jo',
                                    [
                                        'admin/update/view',
                                        'textType' => $model::TEXT_TYPE,
                                        'id'       => $model->id,
                                        'editId'   => 6,
                                    ],
                                    [
                                        'class'             => 'btn btn-sm btn-danger',
                                        'type'              => 'button',
                                        'data-bs-toggle'    => 'tooltip',
                                        'data-bs-placement' => 'bottom',
                                        'data-bs-title'     => 'Jodit',
                                    ],
                                ),
                                [
                                    'class' => 'btn-group toggle-content', // Класс для управления видимостью
                                    'style' => 'display: none;', // Начальная невидимость
                                    'role'  => 'group',
                                ],
                            );
                    },
                    'format'         => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'label'     => 'статус',
                    'filter'    => StatusHelper::statusList(),
                    'value'     => static function (Razdel|Section|Book|Category|Group|Page $model) {
                        return
                            StatusHelper::statusLabel($model->status);
                        
                    },
                    'format'    => 'raw',
                ],
                
                [
                    'attribute' => 'id',
                    'label'     => 'ID',
                    'value'     => 'id',
                    'filter'    => true,
                ],
                [
                    'attribute' => 'lft',
                    'label'     => 'поле Lft',
                    'value'     => 'lft',
                    'filter'    => '<div class="row g-2">' .
                                   '<div class="col py-2">' .
                                   Html::activeTextInput($searchModel, 'lft_from', [
                                       'placeholder' => 'От',
                                       'class'       => 'form-control',
                                   ]) .
                                   '</div>' .
                                   '<div class="col py-2">' .
                                   Html::activeTextInput($searchModel, 'lft_to', [
                                       'placeholder' => 'До',
                                       'class'       => 'form-control',
                                   ]) .
                                   '</div>' .
                                   '</div>',
                ],
                'rgt',
                [
                    'attribute' => 'depth',
                    'filter'    => $searchModel::depthAmountList(),
                ],
            ],
        ]);
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            $actionId, 'GridView_widget' . LAYOUT_ID, $e,
        );
    }
?>

<script>
    // Инициализация всех элементов с атрибутом data-bs-toggle="tooltip"
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

<?php
    $this->registerJs(
        "
    $('#toggle-visibility-btn').on('click', function() {
        $('.toggle-content').toggle(); // Переключение видимости для всех элементов с классом .toggle-content
    });
",
    );
?>
