<?php
    
    use core\helpers\ButtonHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\read\widgets\nestable\Nestable;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $data core\edit\entities\Tools\Draft[] */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $query  yii\db\ActiveQuery */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#tools_draft_resort';
    $label = 'Сортировка страниц. Сайт ' . $site->name;
    
    $siteId = $site->id;
    
    $this->title                   = $label;
    $this->params['breadcrumbs'][] = ['label' => 'Черновики', 'url' => ['index']];
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
        <?= ButtonHelper::urlTo(
            'Экспресс-панель ' . $label,
            '/express/draft/index',
            'btn-outline-warning text-dark',
        )
        ?>
        <hr>
        <?php
            if (!ParametrHelper::isServer()) { ?>
                <?= ButtonHelper::resort
                (
                    $siteId, 'Сортировать',
                )
                ?>
                <?php
            }
            else {
                echo
                '<span class="small strong">
						Сортировать страницы в сайтах:
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
                        id='draft-header'
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
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div
                        id='draft-header'
                        class="card-header bg-light"
                >
                    Страница
                </div>
                <div id="draft-id"></div>
                <div id='draft-description' class="card-body">

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
        url: "/tools/draft/view-ajax",
        data: {id: data_id},
        type: "get",
                success: function(data){
                    $("#draft-description").html(data);
                    $("#btn-view").attr("href", actionView + data_id);                                       
                    $("#btn-update").attr("href", actionUpdate + data_id);                                  
                    $("#btn-delete").attr("href", actionDelete + data_id);
                },
                error: function () {
                    $("#draft-description").html("Ошибка");
        }
    });
    event.stopPropagation(); 
});
JS;
    
    $this->registerJs($js);
