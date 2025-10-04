<?php
    
    use backend\tools\TinyHelper;
    use backend\widgets\PagerWidget;
    use core\edit\forms\Utils\Gallery\GalleryPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model GalleryPhotoForm */
    /* @var $photo core\edit\entities\Utils\Photo */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    
    const LAYOUT_ID = '#utils_photo_update';
    
    $gallery = $photo->gallery;
    
    $this->title                   = 'Правка фото';
    $this->params['breadcrumbs'][] = [
        'label' => 'Галерея ' . $gallery->name,
        'url'   => [
            '/utils/gallery/view',
            'id' => $gallery->id,
        ],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $photo->name, 'url' => [
            'view', 'id'
            => $photo->id,
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
                'model'  => $photo,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
    $form = ActiveForm::begin(
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
            'model'    => $photo,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>

    <div class='card-body'>

        <div class='row'>

            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <?= $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        )->label('Подпись к фото')
                        ?>
                        
                        <?= $form->field($model, 'reference')->textInput(['type' => 'url'])
                        ?>

                    </div>
                    <div class="card-body bg-light">
                        
                        <?= $form->field(
                            $model, 'description',
                        )
                                 ->textarea(
                                     [
                                         'rows' => 8,
                                     ],
                                 )
                            ->label('Описание фото, не больше 500 знаков.')
                        ?>

                    </div>
                </div>
            </div>

            <div class='col-xl-6'>

                <div class='card h-100'>
                    <div class='card-body'>
                        
                        
                        <?php
                            try {
                                echo
                                Html::img(
                                    $photo->getImageUrl(6),
                                    [
                                        'class' => 'card-img-top',
                                    ],
                                );
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    $actionId, LAYOUT_ID, $e,
                                );
                            }
                        ?>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
        <?= ButtonHelper::submit()
        ?>
    </div>

    <div class='card-header bg-light'>
        <strong>
            Необязательный текст, но если очень хочется...
        </strong>
    </div>

    <div class='card-body'>
        <?= $form->field(
            $model, 'text',
            [
                'inputOptions' =>
                    ['id' => 'text-edit-area',],
            ],
        )->textarea()
        ?>

    </div>
    <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
        <?= ButtonHelper::submit()
        ?>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
    TinyHelper::getText();
