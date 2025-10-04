<?php
    
    use core\helpers\ButtonHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    
    /* @var $sites yii\web\View */
    /* @var $actionId array */
    /* @var $types array */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_structure_index';
    
    $this->title = $label;
    
    $buttons = [
        ButtonHelper::indexPanel(),
    ];
    
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
    
    <?= $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>
    <div class='card-body bg-light-subtle'>

        <div class='row'>
            <div class="col-md-6 mb-3">
                <div class="card">

                    <div class="card-header bg-info-subtle">
                        <h5>Структура сайтов</h5>
                    </div>
                    <div class='card-body'>
                        <?php
                            foreach ($sites as $site): ?>
                                <p>
                                    <?= Html::a(
                                        $site['name'],
                                        [
                                            '/admin/structure/site',
                                            'siteId' => $site['id'],
                                        ],
                                        [
                                            'class' => 'btn btn-primary',
                                        ],
                                    )
                                    ?>
                                </p>
                            
                            <?php
                            endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class='card-header bg-info-subtle'>
                        <h5>Структура текстов и сервисов</h5>
                    </div>

                    <div class='card-body'>

                        <div class='card border'>
                            <div class='card-header bg-primary-subtle'>
                                <h4>Категории сервера</h4>
                            </div>

                            <div class='card-body'>
                                <div class='d-flex flex-wrap gap-2'>
                                    <?php
                                        foreach ($types as $type):
                                            if (TypeHelper::isCategory($type)): ?>
                                                <?= Html::a(
                                                    TypeHelper::getName($type),
                                                    [
                                                        '/admin/structure/view',
                                                        'textType' => $type,
                                                    ],
                                                    [
                                                        'class' => 'btn btn-sm btn-primary',
                                                    ],
                                                )
                                                ?>
                                            <?php
                                            endif;
                                        endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class='card border'>
                            <div class='card-header bg-success-subtle'>
                                <h4>Продукты сервера</h4>
                            </div>

                            <div class='card-body'>
                                <div class='d-flex flex-wrap gap-2'>
                                    <?php
                                        foreach ($types as $type):
                                            
                                            if (TypeHelper::isProduct($type)): ?>
                                                <?= Html::a(
                                                    TypeHelper::getName($type),
                                                    [
                                                        '/admin/structure/view',
                                                        'textType' => $type,
                                                    ],
                                                    [
                                                        'class' => 'btn btn-sm btn-success',
                                                    ],
                                                )
                                                ?>
                                            <?php
                                            endif;
                                        endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class='card border'>
                            <div class='card-header bg-dark-subtle'>
                                <h4>Модели сервера</h4>
                            </div>

                            <div class='card-body'>
                                <div class='d-flex flex-wrap gap-2'>
                                    <?php
                                        foreach ($types as $type):
                                            
                                            if (TypeHelper::isModel($type)): ?>
                                                <?= Html::a(
                                                    TypeHelper::getName($type),
                                                    [
                                                        '/admin/structure/view',
                                                        'textType' => $type,
                                                    ],
                                                    [
                                                        'class' => 'btn btn-sm btn-outline-primary',
                                                    ],
                                                )
                                                ?>
                                            <?php
                                            endif;
                                        endforeach; ?>

                                </div>
                            </div>
                        </div>

                        <div class='card border'>
                            <div class='card-header bg-info-subtle'>
                                <h4>Сервисы сервера</h4>
                            </div>

                            <div class='card-body'>
                                <div class='d-flex flex-wrap gap-2'>
                                    <?php
                                        foreach ($types as $type):
                                            
                                            if (TypeHelper::isService($type)): ?>
                                                <?= Html::a(
                                                    TypeHelper::getName($type),
                                                    [
                                                        '/admin/structure/view',
                                                        'textType' => $type,
                                                    ],
                                                    [
                                                        'class' => 'btn btn-sm btn-outline-secondary',
                                                    ],
                                                )
                                                ?>
                                            <?php
                                            endif;
                                        endforeach; ?>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
