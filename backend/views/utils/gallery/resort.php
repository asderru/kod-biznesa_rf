<?php
    
    use core\helpers\ButtonHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\read\widgets\nestable\Nestable;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $data core\edit\entities\Content\Page[] */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $query  yii\db\ActiveQuery */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_gallery_resort';
    
    $this->title = 'Сортировка галерей. Сайт ' . $site->name . '. #' . $site->id;
    
    $this->params['breadcrumbs'][] = ['label' => 'Галереи', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $label;
    
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
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::createType($textType, $site->id, 'Добавить', 'success') ?>
            <?= ButtonHelper::clearCache($site->id, $textType) ?>
            ?>
            
            <?php
                echo
                ButtonHelper::refreshSort($site->id); ?>
            <?= ButtonHelper::collapse()
            ?>
        </div>
    </div>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?php
            if (!ParametrHelper::isServer()) { ?>
                <?= ButtonHelper::resort
                (
                    Parametr::siteId(), 'Сортировать',
                )
                ?>
                <?=
                ButtonHelper::structure($textType, null, 'Структура галерей')
                ?>
                <?php
            }
            else {
                echo
                '<span class="small strong">
						Сортировать галереи в сайтах:
					</span>';
                try {
                    foreach (ParametrHelper::getSites() as $site): ?>
                        <?= ButtonHelper::resort
                        (
                            $site->id, $site->name,
                        )
                        ?>
                    <?php
                    endforeach;
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        'ParametrHelper ', LAYOUT_ID, $e,
                    );
                }
            }
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
                                    'Nestable widget', $e,
                                );
                            }
                        ?>
                    </div>
                </div>

                <div id='nestable-menu' class="card-footer">
                    <button
                            class='btn btn-sm btn-secondary' type='button'
                            data-action='expand-all'
                    >Растянуть всё
                    </button>
                    <button
                            class='btn  btn-sm btn-success' type='button'
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
                        class="card-header bg-light"
                >
                    Галерея
                </div>
                <div id="page-id"></div>
                <div id='page-description' class="card-body">

                </div>
                <div class="card-footer">
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
        url: "/utils/gallery/view-ajax",
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
