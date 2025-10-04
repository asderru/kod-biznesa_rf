<?php
    
    use core\edit\entities\Admin\Information;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $searchModel core\edit\search\Blog\CategorySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $sites Information[] */
    /* @var $files array */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_change_index';
    
    $this->title = $label;
    
    $buttons = [
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

        <div class="row">
            
            <?php
                foreach ($sites as $site): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">

                            <div class="card-header bg-info-subtle">
                                <h5>Смена сайта <?= $site['name'] ?></h5>

                            </div>
                            <div class="card-body">
                                <?= Html::a(
                                    'разделы',
                                    [
                                        '/admin/change/view',
                                        'siteId' => $site['id'],
                                        'textType' => Constant::RAZDEL_TYPE,
                                    ],
                                    [
                                        'class' => 'btn btn-sm btn-outline-dark',
                                    ],
                                )
                                ?>
                                
                                <?= Html::a(
                                    'бренды',
                                    [
                                        '/admin/change/view',
                                        'siteId' => $site['id'],
                                        'textType' => Constant::BRAND_TYPE,
                                    ],
                                    [
                                        'class' => 'btn btn-sm btn-outline-dark',
                                    ],
                                )
                                ?>
                                
                                <?= Html::a(
                                    'блоги',
                                    [
                                        '/admin/change/view',
                                        'siteId' => $site['id'],
                                        'textType' => Constant::CATEGORY_TYPE,
                                    ],
                                    [
                                        'class' => 'btn btn-sm btn-outline-dark',
                                    ],
                                )
                                ?>
                                
                                <?= Html::a(
                                    'форумы (сообщества)',
                                    [
                                        '/admin/change/view',
                                        'siteId' => $site['id'],
                                        'textType' => Constant::GROUP_TYPE,
                                    ],
                                    [
                                        'class' => 'btn btn-sm btn-outline-dark',
                                    ],
                                )
                                ?>
                                
                                
                                <?= Html::a(
                                    'книги',
                                    [
                                        '/admin/change/view',
                                        'siteId' => $site['id'],
                                        'textType' => Constant::BOOK_TYPE,
                                    ],
                                    [
                                        'class' => 'btn btn-sm btn-outline-dark',
                                    ],
                                )
                                ?>
                                
                                <?= Html::a(
                                    'авторы',
                                    [
                                        '/admin/change/view',
                                        'siteId' => $site['id'],
                                        'textType' => Constant::AUTHOR_TYPE,
                                    ],
                                    [
                                        'class' => 'btn btn-sm btn-outline-dark',
                                    ],
                                )
                                ?>
                                
                                <?= Html::a(
                                    'страницы',
                                    [
                                        '/admin/change/view',
                                        'siteId' => $site['id'],
                                        'textType' => Constant::PAGE_TYPE,
                                    ],
                                    [
                                        'class' => 'btn btn-sm btn-outline-dark',
                                    ],
                                )
                                ?>

                            </div>

                        </div>
                    </div>
                <?php
                endforeach; ?>

        </div>

    </div>

</div>
