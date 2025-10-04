<?php
    
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\read\widgets\nestable\Nestable;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $query  yii\db\ActiveQuery */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_information_resort';
    
    $this->title = $label;
        $buttons = [
        ButtonHelper::index('Стандартная правка'),
        ButtonHelper::expressType($textType, null, 'Экспресс-правка'),
        ButtonHelper::import($textType),
        ButtonHelper::export($textType),
    ];
    
    $this->params['breadcrumbs'][] = ['label' => 'Сайты', 'url' => ['index']];
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
    <div class='card bg-light mb-3'>
        <?= $this->render(
            '/layouts/tops/_viewHeaderResort',
            [
                'textType' => $textType,
                'title'    => $label,
                'buttons'  => $buttons,
                'sortId'   => $site->id,
            ],
        )
        ?>
        
        <div class="card-body">
        <div class="row">

            <div class='col-xl-6'>
                <div class="card">
                    <div class="card-body">

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
                    </div>
                    <div id='nestable-menu' class="card-footer">
                        <button
                                class='btn btn-sm btn-secondary'
                                type='button'
                                aria-label='Растянуть'
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
                            Сайт <strong>
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


<?php
    
    $js             = <<<JS
$(".dd3-content").on('click', function () { 
    var data_id = $(this.parentNode).data("id");
    var actionView = "view?id=";
    var actionUpdate = "update?id=";   
    var actionDelete = "delete?id=";   
    $.ajax({
        url: "/admin/information/view-ajax",
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
