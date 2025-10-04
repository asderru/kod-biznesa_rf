<?php
    
    use backend\helpers\BreadCrumbHelper;
    use core\edit\entities\Library\Book;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\QueryHelper;
    use core\read\widgets\nestable\Nestable;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $data Book */
    /* @var $query  yii\db\ActiveQuery */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $sites core\edit\entities\Admin\Information[] */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_book_resort';
    
    $this->title = 'Сортировка книг. Сайт ' . $site->name . '. #' . $site->id;
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::structure($textType);
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
    <div class='card'>

        <div class='card-header bg-light d-flex justify-content-between'>
            <div class='h5'>
                <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($this->title)
                ?>
            </div>
            <div>
                <?= ButtonHelper::createType($textType, $site->id, 'Добавить', 'success') ?>
                <?= ButtonHelper::clearCache($site->id, $textType)
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
                '/express/book/index',
                'btn-outline-warning text-dark',
            )
            ?>
            <?=
                ButtonHelper::structure($textType, null, 'Структура книг')
            ?>
            <hr>
            <?php
                if (!ParametrHelper::isServer()) { ?>
                    <?= ButtonHelper::resort
                    (
                        Parametr::siteId(), 'Сортировать',
                    )
                    ?>
                    <?php
                }
                else {
                    echo
                    '<span class="small strong">
						Сортировать книги в сайтах:
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
                            'ParametrHelper_getInformations ', LAYOUT_ID, $e,
                        );
                    }
                }
            ?>
            <hr>

            <span class='small strong'>
				Упорядочить главы в книгах:
			</span>
            
            <?php
                try {
                    foreach (
                        QueryHelper::getBooks(null, null, Constant::THIS_FIRST_NODE) as $book
                    ): ?>
                        <?= Html::a(
                            $book->name,
                            [
                                '/library/chapter/resort',
                                'id' => $book->id,
                            ],
                            [
                                'class' => 'btn btn-sm btn-outline-primary',
                            ],
                        );
                        ?>
                    <?php
                    endforeach;
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        'ParametrHelper ', LAYOUT_ID, $e,
                    );
                }
            ?>

        </div>

        <div class="card-body">
            <div class="row">

                <div class='col-xl-6'>
                    <div class='card'>
                        <div id='page-header'
                             class='card-header bg-light'>
                            Все книги
                        </div>
                        <div class="card-body menu-index">
                            <div class='table-responsive'>
                                <?php
                                    try {
                                        echo
                                        Nestable::widget(
                                            [
                                                'type'          => Nestable::TYPE_WITH_HANDLE,
                                                'query'         => $query,
                                                'modelOptions'  => [
                                                    'name' => 'name',
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
                                            'Nestable_widget ', LAYOUT_ID, $e,
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
                                class='card-header bg-light'
                        >
                            Книга
                        </div>
                        <div class='card-body'>

                            <div id="page-id"></div>
                            <div id='page-description' class="card-body">

                            </div>
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
        url: "/library/book/view-ajax",
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
