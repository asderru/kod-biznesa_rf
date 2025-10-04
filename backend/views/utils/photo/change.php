<?php
    
    use core\helpers\ButtonHelper;
    use backend\tools\TinyHelper;
    use core\edit\forms\Utils\Gallery\GalleryPhotoForm;
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
    
    
    const LAYOUT_ID = '#utils_photo_change';
    
    $this->title                   = 'Правка фото';
    $this->params['breadcrumbs'][] = [
        'label' => 'Галерея ' . $photo->gallery->name,
        'url'   => [
            '/utils/gallery/view',
            'id' => $photo->gallery->id,
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
        '/layouts/tops/_createHeader',
        [
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>

    <div class='card-body'>

        <div class='row'>

            <div class='col-xl-6'>

                <div class='card h-100'>
                    
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
                                $actionId, 'getImageUrl' . LAYOUT_ID, $e,
                            );
                        }
                    ?>
                    <div class='card-body'>
                        
                        <?= $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        )
                        ?>
                        
                        <?= $form->field($model, 'reference')->textInput(['type' => 'url'])
                        ?>

                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-body">
                        
                        <?= $form->field(
                            $model, 'description',
                            [
                                'inputOptions' => [
                                    'id' => 'description-edit-area',
                                ],
                            ],
                        )
                                 ->textarea()
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

<?php
    echo '</div>';
    
    ActiveForm::end();
    TinyHelper::getDescription();
