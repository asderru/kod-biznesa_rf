<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\search\Blog\PostSearch;
    use core\edit\search\Forum\ThreadSearch;
    use core\edit\search\Library\ChapterSearch;
    use core\edit\search\Magazin\ArticleSearch;
    use core\edit\search\Shop\ProductSearch;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    use yii\web\View;
    
    
    /* @var $searchModel ProductSearch|ArticleSearch|ChapterSearch|PostSearch|ThreadSearch| */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $textType int */
    /* @var $full bool */
    
    const LAYOUT_ID = '#site_view1';
    echo PrintHelper::layout(LAYOUT_ID);
    
    $caption = TypeHelper::getName($textType, null, false, true);
    $modelDir = TypeHelper::getLongEditUrl($textType);
    $expressDir = '/express/' . TypeHelper::getShortEditUrl($textType);
    
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
                    'value'     => static function (Product|Article|Chapter|Post|Thread $model)
                    use ($modelDir) {
                        
                        // Возвращаем ссылку с отступом перед именем
                        return Html::a(
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
                    'header'         => '<button type="button" class="btn btn-sm btn-outline-primary toggle-column"
                                        data-column="tools-column">
                                        <i class="bi bi-pencil-square"></i> - <i class="bi bi-chevron-down"></i>
                                        </button>',
                    'headerOptions'  => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center tools-column'],
                    'value'          => function (Product|Article|Chapter|Post|Thread $model)
                    use ($modelDir, $expressDir) {
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
                               . Html::a(
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
                                'Разметка',
                                [
                                    'admin/update/view',
                                    'textType' => $model::TEXT_TYPE,
                                    'id'       => $model->id,
                                    'editId'   => 7,
                                ],
                                [
                                    'class'             => 'btn btn-sm btn-dark',
                                    'type'              => 'button',
                                    'data-bs-toggle'    => 'tooltip',
                                    'data-bs-placement' => 'bottom',
                                    'data-bs-title'     => 'Разметка',
                                ],
                            )
                               . '<div class="btn-group" role="group" aria-label="Basic example">'
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
                                    'class'             => 'btn btn-sm btn-outline-secondary',
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
                                    'class'             => 'btn btn-sm btn-outline-dark',
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
                            )
                               . '</div>';
                    },
                    'format'         => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'label'     => 'статус',
                    'filter'    => StatusHelper::statusList(),
                    'value'     => static function (Product|Article|Chapter|Post|Thread $model) {
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
    $js = <<<JS
$(document).ready(function() {
    $('.toggle-column').click(function() {
        var columnClass = $(this).data('column');
        $('.' + columnClass).toggle();
        $(this).find('i').toggleClass('bi-chevron-down bi-chevron-up');
    });
    
    // Инициализация тултипов Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
JS;
    
    $this->registerJs($js, View::POS_READY);
    
    // Добавляем начальные стили
    $css = <<<CSS
.tools-column {
    display: none;
}
CSS;
    
    $this->registerCss($css);
?>
