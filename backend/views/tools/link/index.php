<?php
    
    use core\read\readers\Admin\SiteModeReader;
    use core\read\readers\Admin\InformationReader;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Tools\Link;
    use core\edit\search\Admin\LinkSearch;
    use core\helpers\ButtonHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\grid\ActionColumn;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    /* @var $this yii\web\View */
    /* @var $sites Information */
    /* @var $searchModel LinkSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID                = '#admin_link_index';
    
    $label    = 'Проверка ссылок';
    $textType = Constant::LINK_TYPE;
    
    $this->title = $label;
    
    $buttons = [];
    
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
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
?>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        Удалить ссылки со статусом: <br>
        <?= ButtonHelper::deleteStatus(200)
        ?>
        <?= ButtonHelper::deleteStatus(404)
        ?>
        <?= ButtonHelper::deleteStatus(500)
        ?>
        <?= ButtonHelper::deleteStatus(301)
        ?>
        <?= ButtonHelper::deleteAllLinks()
        ?>
        <hr>
        Проверить ссылки в текстах сайта: <br><?php
            foreach ($sites as $site) {
                echo ButtonHelper::checkSiteContentLink(
                    $site,
                );
            }
        ?>
        <hr>
        Проверить ссылки на страницах сайта: <br><?php
            foreach ($sites as $site) {
                echo ButtonHelper::checkSitePagesLink(
                    $site,
                );
            }
        ?>
        <hr>
        <small>Среднее время проверки всех ссылок с одной страницы от 5 до 30 секунд в зависимости от количества ссылок.
            Если на сайте более 100 страниц, может понадобиться время от 10 минут и больше.</small>
        <hr>
        Очистить базу ссылок и проверить статус страниц на сайте: <br><?php
            foreach ($sites as $site) {
                echo ButtonHelper::checkBrokenPages(
                    $site,
                );
            }
        ?>
        <hr>
        Очистить базу ссылок и проверить битые ссылки на сайте: <br><?php
            foreach ($sites as $site) {
                echo ButtonHelper::checkBrokenLinks(
                    $site,
                );
            }
        ?>
    </div>
    <div class='card-body'>
        <?=
            // Форма выбора количества строк
            
            $this->render(
                '/layouts/partials/_pageSize',
            );
        ?>
        <div class='table-responsive'>
            <?php
                try {
                    echo
                    GridView::widget(
                        [
                            'pager'          => [
                                'firstPageLabel' => 'в начало',
                                'lastPageLabel'  => 'в конец',
                            ],
                            'dataProvider'   => $dataProvider,
                            'filterModel'    => $searchModel,
                            'caption'        => 'Посты в блогах',
                            'captionOptions' => [
                                'class' => 'text-start p-2',
                            ],
                            'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                            'summaryOptions' => [
                                'class' => 'bg-secondary text-white p-1',
                            ],
                            'tableOptions'   => ['class' => 'table table-striped table-bordered'],
                            'columns'        => [
                                [
                                    'class' =>
                                        SerialColumn::class,
                                ],
                                [
                                    'attribute' => 'url',
                                    'label'     => 'Ссылка',
                                    'value'     => static function (Link $model) {
                                        $class = ($model->status === 200) ? 'text-dark' : 'text-danger';
                                        return Html::a(
                                            $model->url,
                                            $model->url,
                                            [
                                                'class'  => $class,
                                                'target' => '_blank',
                                            ],
                                        );
                                    },
                                    'format'    => 'raw',
                                ],
                                [
                                    'attribute' => 'status',
                                    'label'     => 'Статус',
                                ],
                                [
                                    'attribute' => 'site_id',
                                    'label'     => 'Сайт',
                                    'value'     => static function (Link $model) {
                                        return ParametrHelper::getSiteName($model->site_id);
                                    },
                                    'format'    => 'raw',
                                    'filter'    => InformationReader::getDropDownFilter(0),
                                    'visible'   => ParametrHelper::isServer(),
                                ],
                                [
                                    'attribute' => 'text_type',
                                    'label'     => 'Тип текста',
                                    'value'     => static function (Link $model) {
                                        return TypeHelper::getName($model->text_type);
                                    },
                                    'format'    => 'raw',
                                    'filter' => SiteModeReader::getTextTypesMap(),
                                ],
                                [
                                    'label'  => 'Источник',
                                    'value'  => static function (Link $model) {
                                        $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
                                        $url = TypeHelper::getView($model->text_type, $model->parent_id);
                                        return
                                            Html::a(
                                                $parent?->name,
                                                $url,
                                                [
                                                    'class'  => 'btn btn-sm btn-outline-dark',
                                                    'target' => '_blank',
                                                ],
                                            );
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'class'    => ActionColumn::class,
                                    'template' => '{delete}',
                                ],
                            ],
                        ],
                    );
                }
                catch (Throwable $e) {
                    PrintHelper::exception(
                        $actionId, 'GridView_widget' . LAYOUT_ID, $e,
                    );
                }
            ?>
        </div>
    </div>

<?php
    echo '</div>';
