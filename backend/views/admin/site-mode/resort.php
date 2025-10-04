<?php
    
    use core\helpers\PrintHelper;
    use core\read\widgets\nestable\Nestable;
    use yii\bootstrap5\Html;
    
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    /* @var $this yii\web\View */
    /* @var $query  yii\db\ActiveQuery */
    
    const LAYOUT_ID = '#admin_siteMode_resort';
    
    $this->title = $label;
    
    $this->params['breadcrumbs'][] = ['label' => 'Типы сайтов', 'url' => ['index']];
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

        <div class='card-header bg-light d-flex justify-content-between'>
            <div class='h4'>
                <?= Html::encode($this->title)
                ?>
            </div>
            <div class='btn-group-sm d-grid gap-2 d-sm-block'>

            </div>
        </div>

        <div class="card-body">

            <div class="row">

                <div class='col-xl-6'>
                    <div class="card">
                        <div class="card-body">

                            <div class='table-responsive'>
                                <?php
                                    try {
                                        echo
                                        Nestable::widget([
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
                                        ]);
                                    }
                                    catch (Exception|Throwable $e) {
                                        PrintHelper::exception(
                                            LAYOUT_ID, $e,
                                        );
                                    }
                                ?>
                            </div>
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
                <div class="col-xl-6">
                    <div class="card">
                        <div
                                id='page-header'
                                class='card-header bg-light  d-flex justify-content-between'
                        >
                            <div>
                                Тип <strong>
                                    <?= Html::encode($this->title)
                                    ?>

                                </strong>
                            </div>


                            <div>
                                <a
                                        id='btn-update' href=''
                                        class='btn btn-sm btn-success m-1'
                                >Редактировать</a>

                            </div>
                        </div>
                        <div class="card-body">

                            <div id="page-id"></div>

                            <div id='page-description' class="card-body">

                            </div>
                        </div>
                        <div class="card-footer">
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
    var actionView = "view?id=";
    var actionUpdate = "update?id=";   
    var actionDelete = "delete?id=";   
    $.ajax({
        url: "/admin/site-mode/view-ajax",
        data: {id: data_id},
        type: "get",
                success: function(data){
                    $("#page-description").html(data);
                    $("#btn-view").attr("href", actionView + data_id);                                       
                    $("#btn-update").attr("href", actionUpdate + data_id);                                  
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
