<?php
    
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\PrintHelper;
    use core\read\widgets\nestable\Nestable;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $data core\edit\entities\Admin\Template */
    /* @var $query  yii\db\ActiveQuery */
    /* @var $searchModel core\edit\search\Admin\TemplateSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_template_resort';
    
    $this->title = 'Сортировка шаблонов';
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Сортировка';
    
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

    <div class="card">

        <div class='card-header bg-light d-flex justify-content-between'>
            <div class='h5'>
                <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($this->title)
                ?>
            </div>
            <div class='btn-group-sm d-grid gap-2 d-sm-block'>
                <?= ButtonHelper::create('Добавить')
                ?>
                
                <?php
                    echo
                    ButtonHelper::refreshSort(); ?>
                <?= ButtonHelper::collapse()
                ?>
            </div>
        </div>

        <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
            <?= ButtonHelper::urlTo(
                'Экспресс-панель ' . $label,
                '/express/category/index',
                'btn-outline-warning text-dark',
            )
            ?>

        </div>

        <div class="card-body">

            <div class='row'>
                <div class='col-xl-6'>
                    <div class='card mb-3'>
                        <div
                                id='page-header'
                                class='card-header bg-light'
                        >Структура
                        </div>
                        <div class='card-body menu-index'>

                            <div class='table-responsive'>
                                <?php
                                    try {
                                        echo
                                        Nestable::widget(
                                            [
                                                'type'          => Nestable::TYPE_WITH_HANDLE,
                                                'query'         => $query,
                                                'modelOptions'  => [
                                                    'name' => 'name', //поле из БД с названием элемента (отображается в дереве)
                                                ],
                                                'pluginEvents'  => [
                                                    'change' => 'function(e) {}', //js событие при выборе элемента
                                                ],
                                                'pluginOptions' => [
                                                    'maxDepth' => 10, //максимальное кол-во уровней вложенности
                                                ],
                                            ],
                                        );
                                    }
                                    catch (Exception|Throwable $e) {
                                        PrintHelper::exception(
                                            'Nestable::widget', LAYOUT_ID, $e,
                                        );
                                    }
                                ?>
                            </div>
                            <div id='nestable-menu' class="card-footer">
                                <button
                                        class='btn btn-sm btn-secondary'
                                        type='button'
                                        data-action='expand-all'
                                >Растянуть
                                </button>
                                <button
                                        class='btn  btn-sm btn-success'
                                        type='button'
                                        data-action='collapse-all'
                                >Сжать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-xl-6'>

                    <div class="card">
                        <div
                                id='page-header'
                                class='card-header bg-light'
                        >
                            Блог
                        </div>
                        <div id='page-id'></div>
                        <div id='page-description' class='card-body'>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
<?php
    
    $js             = <<<JS
$(".dd3-content").on('click', function () { 
    var data_id = $(this.parentNode).data("id");
    $.ajax({
        url: "/admin/template/view-ajax",
        data: {id: data_id},
        type: "get",
                success: function(data){
                    $("#page-description").html(data);
                    $("#btn-delete").attr("href", actionDelete + data_id);
                },
                error: function () {
                    $("#page-description").html("Ошибка");
        }
    });
    event.stopPropagation(); 
});
JS;
    
    $this->registerJs($js);
