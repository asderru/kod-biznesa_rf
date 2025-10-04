<?php
    
    use backend\widgets\PagerWidget;
    use core\edit\forms\Addon\BannerForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model BannerForm */
    /* @var $banner core\edit\entities\Addon\Banner */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#addon_banner_update';
    
    $this->title                   = $banner->name . '. Правка';
    $this->params['breadcrumbs'][] = [
        'label' => 'Баннеры',
        'url'   => ['index'],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $banner->name,
        'url'   => [
            'view',
            'id' => $banner->id,
        ],
    ];
    
    $this->params['breadcrumbs'][] = 'Правка';
    
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
                'model'  => $banner,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
    $form           = ActiveForm::begin(
        [
            'options'     => [
                'class' => 'active__form',
            ],
            'fieldConfig' => [
                'errorOptions' => [
                    'encode' => false,
                    'class'  => 'help-block',
                ],
            ],
        ],
    );
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_updateHeader',
        [
            'model'    => $banner,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>


    <div class="row">

        <div class='col-xl-6'>
            <div class="card ">

                <div class='card-header bg-light'>
                    <strong>
                        Общая информация
                    </strong>
                </div>

                <div class='card-body'>
                    
                    <?= $form->field($model, 'picture')->textInput(['maxlength' => true])
                    ?>
                    
                    <?= $form->field($model, 'reference')->textInput(['type' => 'url'])
                    ?>
                    
                    <?= $form->field($model, 'name')->textInput(
                        [
                            'maxlength' => true,
                            'required'  => true,
                        ],
                    )
                    ?>
                    
                    <?= $form->field($model, 'description')->textarea(['rows' => 6])
                    ?>

                    <div class='d-flex justify-content-between'>
                        <?= $form->field($model, 'rating')
                                 ->textInput(
                                     [
                                         'type' => 'number',
                                         'min'  => 1,
                                         'max'  => 100,
                                         'value' => $model->rating ?: 1, // Устанавливаем значение по умолчанию 1
                                     ],
                                 )
                                 ->label('Рейтинг от 1 до 100')
                        ?>
                        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
                            <?= ButtonHelper::submit()
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class='col-xl-6'>
            <div class="card">
                <div class="card-header bg-body-secondary">
                    Баннер
                </div>

                <div class="card-body">
                    <?php
                        if ($banner) {
                            echo $this->render(
                                '/addon/banner/_bannerView',
                                [
                                    'model' => $banner,
                                ],
                            );
                        } ?>
                </div>

            </div>

        </div>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
