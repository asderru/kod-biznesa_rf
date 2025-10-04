<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Tools\Draft */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#tools_draft_view';
    
    $this->title = $model->name;
    
    
    $this->params['breadcrumbs'][] = [
        'label' => $label,
        'url'   => [
            '/tools/draft/index',
        ],
    ];
    
        $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
         ButtonHelper::create('Добавить черновик'),
         ButtonHelper::updateHtml($model),
         ButtonHelper::keywords($model),
         ButtonHelper::clearCache($model->site_id, $textType, $model->id),
         ButtonHelper::delete($model),
            ];
    
    $parent = $model->getParent();
    
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
            'model' => $model,
            'textType' => $textType,
            'buttons'  => $buttons, // передаем массив кнопок
        ],
)
?>

    <div class='card-body'>

        <div class='row mb-3'>

            <div class='col-lg-6'>

                <div class='card mb-3'>
                    <div class='card-header bg-light'>
                        <strong>
                            Информация
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?php
                            
                            try {
                                echo DetailView::widget(
                                    [
                                        'model'      => $model,
                                        'attributes' => [
                                            'id',
                                            'name',
                                            [
                                                'attribute' => 'updated_at',
                                                'format'    => 'dateTime',
                                            ],
                                        ],
                                    ],
                                );
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'DetailView-widget ', LAYOUT_ID, $e,
                                );
                            }
                        ?>
                    </div>
                </div>

                <div class='card mb-3 р-100'>
                    <div class='card-header bg-light'>
                        <strong>
                            Создать из черновика:
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?= Html::a(
                            'раздел'
                            ,
                            [
                                'shop/razdel/create-from-draft',
                                'id' => $model->id,
                            ],
                            [
                                'class' => 'btn btn-sm btn-outline-secondary mb-1 mr-1',
                            ],
                        )
                        ?>
                        <?= Html::a(
                            'продукт'
                            ,
                            [
                                'shop/product/create-from-draft',
                                'id' => $model->id,
                            ],
                            [
                                'class' => 'btn btn-sm btn-outline-secondary mb-1 mr-1',
                            ],
                        )
                        ?>
                        <?= Html::a(
                            'бренд'
                            ,
                            [
                                'shop/brand/create-from-draft',
                                'id' => $model->id,
                            ],
                            [
                                'class' => 'btn btn-sm btn-outline-secondary mb-1 mr-1',
                            ],
                        )
                        ?>
                        <?= Html::a(
                            'страницу'
                            ,
                            [
                                'content/page/create-from-draft',
                                'id' => $model->id,
                            ],
                            [
                                'class' => 'btn btn-sm btn-outline-secondary mb-1 mr-1',
                            ],
                        )
                        ?>
                        <?= Html::a(
                            'пост в блоге'
                            ,
                            [
                                'blog/post/create-from-draft',
                                'id' => $model->id,
                            ],
                            [
                                'class' => 'btn btn-sm btn-outline-secondary mb-1 mr-1',
                            ],
                        )
                        ?>
                        <?= Html::a(
                            'главу в книге'
                            ,
                            [
                                'library/chapter/create-from-draft',
                                'id' => $model->id,
                            ],
                            [
                                'class' => 'btn btn-sm btn-outline-secondary mb-1 mr-1',
                            ],
                        )
                        ?>
                        <?= Html::a(
                            'статью в журнал'
                            ,
                            [
                                'magazin/article/create-from-draft',
                                'id' => $model->id,
                            ],
                            [
                                'class' => 'btn btn-sm btn-outline-secondary mb-1 mr-1',
                            ],
                        )
                        ?>
                        <?= Html::a(
                            'тред на форуме'
                            ,
                            [
                                'forum/thread/create-from-draft',
                                'id' => $model->id,
                            ],
                            [
                                'class' => 'btn btn-sm btn-outline-secondary mb-1 mr-1',
                            ],
                        )
                        ?>
                    </div>
                </div>
            </div>

            <div class='col-lg-6'>
                <div class='card mb-3'>
                    <div class='card-header bg-light'>
                        <strong>
                            Описание
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?= FormatHelper::asHtml($model->description)
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->render(
                '/layouts/templates/_textWidget',
                [
                    'model' => $model,
                ],
            )
        ?>
    </div>

<?php echo '</div>';
