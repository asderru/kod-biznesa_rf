<?php
    
    use backend\assets\TopScriptAsset;
    use backend\helpers\LogHelper;
    use backend\widgets\PagerWidget;
    use core\edit\entities\Tech\Log;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\data\ArrayDataProvider;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $dataProvider ArrayDataProvider */
    /* @var $model Log */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $logType int */
    /* @var $countLogs int */
    
    const LAYOUT_ID = '#link_log_view';
    
    $this->title                   = $label;
    
    $buttons = [
    
    ];
    
    $this->params['breadcrumbs'][] = ['label' => 'Логи', 'url' => ['index']];
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
            'model'    => $model,
            'textType' => $textType,
            'buttons'  => $buttons, // передаем массив кнопок
        ],
)
?>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <div class='row'>
            <div class='col-lg-6'>
                <h3>Сервер APACHE</h3>
                <div class='table-responsive'>

                    <table class='table table-sm table-bordered table-striped'>
                        <thead>
                        <tr>
                            <th scope='col' class='text-center p-2'>Получить логи</th>
                            <th scope='col' class='text-center p-2'>50</th>
                            <th scope='col' class='text-center p-2'>200</th>
                            <th scope='col' class='text-center p-2'>500</th>
                            <th scope='col' class='text-center p-2'>1000</th>
                            <th scope='col' class='text-center p-2'>2000</th>
                            <th scope='col' class='text-center p-2'>5000</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope='row'>Доступ к сайту</th>
                            <td class='text-center p-2'>
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ACCESS,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ACCESS,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ACCESS,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ACCESS,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ACCESS,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ACCESS,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Ошибка доступа к сайту</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ERROR,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ERROR,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ERROR,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ERROR,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ERROR,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_WEB_ERROR,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Доступ к панели</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ACCESS,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ACCESS,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ACCESS,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ACCESS,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ACCESS,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ACCESS,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Ошибка доступа к панели</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ERROR,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ERROR,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ERROR,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ERROR,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ERROR,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_PANEL_ERROR,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Доступ к статике</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ACCESS,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ACCESS,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ACCESS,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ACCESS,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ACCESS,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ACCESS,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Ошибка доступа к статике</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ERROR,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ERROR,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ERROR,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ERROR,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ERROR,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_APACHE_STATIC_ERROR,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </div>
            <div class='col-lg-6'>
                <h3>Сервер NGINX</h3>
                <div class="table-responsive">

                    <table class='table table-sm table-bordered table-striped'>
                        <thead>
                        <tr>
                            <th scope='col' class='text-center p-2'>Получить логи</th>
                            <th scope='col' class='text-center p-2'>50</th>
                            <th scope='col' class='text-center p-2'>200</th>
                            <th scope='col' class='text-center p-2'>500</th>
                            <th scope='col' class='text-center p-2'>1000</th>
                            <th scope='col' class='text-center p-2'>2000</th>
                            <th scope='col' class='text-center p-2'>5000</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope='row'>Доступ к сайту</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ACCESS,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ACCESS,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ACCESS,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ACCESS,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ACCESS,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ACCESS,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Ошибка доступа к сайту</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ERROR,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ERROR,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ERROR,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ERROR,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ERROR,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_WEB_ERROR,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Доступ к панели</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ACCESS,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ACCESS,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ACCESS,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ACCESS,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ACCESS,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ACCESS,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Ошибка доступа к панели</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ERROR,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ERROR,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ERROR,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ERROR,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ERROR,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_PANEL_ERROR,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Доступ к статике</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ACCESS,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ACCESS,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ACCESS,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ACCESS,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ACCESS,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ACCESS,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope='row'>Ошибка доступа к статике</th>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ERROR,
                                        50,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ERROR,
                                        200,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ERROR,
                                        500,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ERROR,
                                        1000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ERROR,
                                        2000,
                                    )
                                ?>
                            </td>
                            <td class="text-center p-2">
                                <?=
                                    LogHelper::logView(
                                        Constant::LOG_NGINX_STATIC_ERROR,
                                        5000,
                                    )
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </div>


        </div>
    </div>

    <div class='card-body'>

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
                            'caption'        => $label,
                            'captionOptions' => [
                                'class' => 'text-start p-2',
                            ],
                            'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                            'summaryOptions' => [
                                'class' => 'bg-secondary text-white p-1',
                            ],
                            
                            'tableOptions' => [
                                'id'    => 'point-of-grid-view',
                                'class' => 'table table-striped table-bordered',
                            ],
                            'columns'      => [
                                [
                                    'class' =>
                                        SerialColumn::class,
                                ],
                                'ip_address',
                                [
                                    'attribute' => 'time',
                                    'format'    => 'datetime',
                                    'label'     => 'Время',
                                ],
                                'link',
                                'status_code',
                                'parent',
                            ],
                        ],
                    );
                }
                catch (Throwable $e) {
                }
            ?>

        </div>

    </div>

<?php echo '</div>';
