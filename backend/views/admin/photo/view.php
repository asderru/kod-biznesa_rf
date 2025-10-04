<?php
    
    use backend\widgets\PagerWidget;
    use core\edit\entities\Admin\PhotoRatio;
    use core\edit\entities\Admin\PhotoSize;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model PhotoSize */
    /* @var $ratio PhotoRatio */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_photo_view';
    
    $this->title = TypeHelper::getLabel($model->text_type);
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [];
    
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

    <div class="row">

        <div class="col-lg-6">

            <div class='card rounded-0'>
                <div class='card-header bg-light'>
                    Размеры фото
                </div>

                <div class='card-body'>

                    <div class='table-responsive'>
                        
                        <?= $this->render(
                            '@app/views/admin/photo/_partView',
                            [
                                'model' => $model,
                            ],
                        )
                        ?>
                    </div>

                </div>
                <div class='card-footer text-end'>
                    
                    <?= Html::a(
                        'Править размеры фото',
                        [
                            '/admin/photo/update',
                            'id' => $model->id,
                        ],
                        [
                            'class' => 'btn btn-sm btn-outline-primary',
                        ],
                    )
                    ?>
                </div>

            </div>

        </div>

        <div class="col-lg-6">

            <div class='card rounded-0'>
                <div class="card-header bg-light">
                    Пропорции фото
                </div>

                <div class='card-body'>

                    <div class='table-responsive'>
                        
                        <?= $this->render(
                            '@app/views/admin/ratio/_partView',
                            [
                                'model' => $ratio,
                            ],
                        )
                        ?>

                    </div>

                </div>

                <div class='card-footer text-end'>
                    
                    <?= Html::a(
                        'Править пропорции фото',
                        [
                            '/admin/ratio/update',
                            'id' => $model->id,
                        ],
                        [
                            'class' => 'btn btn-sm btn-outline-dark',
                        ],
                    )
                    ?>
                </div>

            </div>
        </div>
    </div>

<?php
    echo '</div>';
