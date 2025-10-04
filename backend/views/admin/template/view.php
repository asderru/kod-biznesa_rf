<?php
    
    use backend\widgets\BannerWidget;
    use backend\widgets\PagerWidget;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Admin\Template */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_template_view';
    
    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Шаблоны', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        ButtonHelper::update($model->id, 'Редактировать'),
        ButtonHelper::create(),
        ButtonHelper::clearCache($model->site_id, $textType, $model->id),
        ButtonHelper::delete($model),
    ];
    
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
        '/layouts/tops/_viewHeader-start',
        [
            'id'     => $model->id,
            'status' => null,
            'title'  => $this->title,
            'buttons' => $buttons,
        ],
    )
?>

    <div class='card-body'>


        <div class='row'>

            <div class='col-xl-6'>
                <div class='card mb-3'>

                    <div class='card-header bg-light'>
                        <strong>
                            Информация
                        </strong>
                    </div>
                    <div class='card-body'>

                        <div class='table-responsive'>
                            
                            <?php
                                try {
                                    echo DetailView::widget(
                                        [
                                            'model'      => $model,
                                            'attributes' => [
                                                'id',
                                                'name',
                                                'slug',
                                                'repository',
                                                'lft',
                                                'rgt',
                                                'depth',
                                            ],
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        'DetailView-widget ', LAYOUT_ID, $e,
                                    );
                                } ?>
                        </div>
                    </div>
                    <div class='card-header text-white bg-secondary'>
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

            <div class='col-xl-6'>

                <!--####### Одна картинка #############-->
                
                <?= $this->render(
                    '/layouts/images/_images',
                    [
                        'model'      => $model,
                        'uploadForm' => $uploadForm,
                    ],
                )
                ?>

                <!--######  BannerWidget ########################-->
                
                <?php
                    try {
                        echo
                        BannerWidget::widget(
                            [
                                'model' => $model,
                                'title' => 'Баннер, закрепленный за брендом',
                            ],
                        );
                    }
                    catch (Exception|Throwable $e) {
                        PrintHelper::exception(
                            ' BannerWidget_widget ', LAYOUT_ID
                            , $e,
                        );
                    }
                ?>

                <!--###### Конец BannerWidget ###################-->
            </div>
        </div>

    </div>

<?php
    echo '</div>';
