<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\User\UserStatistic;
    use core\edit\search\User\UserStatSearch;
    use core\helpers\IconHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    use yii\grid\SerialColumn;
    use yii\helpers\Url;
    
    TopScriptAsset::register($this);
    
    /** @var yii\web\View $this */
    /** @var UserStatSearch $searchModel */
    /** @var yii\data\ActiveDataProvider $dataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_userstat_index';
    echo PrintHelper::layout(LAYOUT_ID);
    
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
    )
?>

<div class='card'>
    
    <?= $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>

    <div class='card-body'>

        <div class='table-responsive'>
            <?php
                try {
                    echo
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel'  => $searchModel,
                        'columns'      => [
                            ['class' => SerialColumn::class],
                            //'id',
                            //'site_id',
                            //'edit',
                            [
                                
                                'attribute' => 'text_type',
                                'value'     => static function (
                                    UserStatistic $model,
                                ) {
                                    return TypeHelper::getName($model->text_type);
                                    
                                },
                                'format'    => 'raw',
                            ],
                            [
                                
                                'attribute' => 'parent_id',
                                'label'     => 'Название страницы',
                                'value'     => static function (
                                    UserStatistic $model,
                                ) {
                                    return ParentHelper::getModel($model->text_type, $model->parent_id)?->name;
                                    
                                },
                                'format'    => 'raw',
                            ],
                            'page_url:url',
                            'referer_url:url',
                            //'exit_time:datetime',
                            [
                                'attribute'      => 'ip_address',
                                'label'          => 'IP адрес',
                                'value'          => static function (
                                    UserStatistic $model,
                                ) {
                                    return $model->ip_address . '<hr>' . '  ' . Html::a(
                                            IconHelper::biEye('смотреть'),
                                            Url::to('https://whatismyipaddress.com/ip/' . $model->ip_address),
                                            [
                                                'class'  => 'btn btn-sm btn-primary',
                                                'target' => '_blank',
                                            ],
                                        ) . '  ' . Html::a(
                                            IconHelper::biEye('смотреть'),
                                            Url::to('https://dnschecker.org/ip-whois-lookup.php?query=' . $model->ip_address),
                                            [
                                                'class'  => 'btn btn-sm btn-success',
                                                'target' => '_blank',
                                            ],
                                        ) . '  ' . Html::a(
                                            IconHelper::biTrash('удалить'),
                                            [
                                                'create',
                                                'id'        => $model->id,
                                                'ipAddress' => $model->ip_address,
                                            ],
                                            [
                                                'class' => 'btn btn-sm btn-dark',
                                            ],
                                        ) . '  ' . Html::a(
                                            IconHelper::biTrash('удалить'),
                                            [
                                                'delete',
                                                'id' => $model->id,
                                            ],
                                            [
                                                'class' => 'btn btn-sm btn-danger',
                                            ],
                                        );
                                    
                                },
                                'format'         => 'raw',
                                'contentOptions' => [
                                    'style' => 'min-width:100px',
                                ],
                            ],
                            'entry_time:datetime',
                        ],
                    ]);
                }
                catch (Throwable $e) {
                    PrintHelper::exception(
                        'GridView_widget', LAYOUT_ID, $e,
                    );
                } ?>
        </div>
    </div>

</div>
