<?php
    
    use backend\widgets\PagerWidget;
    use consynki\yii\input\ImageInput;
    use core\edit\forms\Utils\PictureUploadPhotoForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model PictureUploadPhotoForm */
    /* @var $photo string */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $width int */
    /* @var $height int */
    
    
    const LAYOUT_ID = '#utils_picture_update';
    
    $this->title = 'Замена картинки';
    
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
            $actionId, 'PagerWidget' . LAYOUT_ID, $e,
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
            'model' => null,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>


    <h1><?= Html::encode($this->title)
        ?></h1>

    <div class='row'>

        <div class='col-xl-6'>

            <div class='card h-100'>

                <div class="card-header bg-body-secondary">
                    Замена картинки по адресу: <br>
                    <strong>
                        <?= Html::encode($photo)
                        ?>
                    </strong>
                </div>

                <div class='card-body'>
                    <img src="<?= Html::encode($photo)
                    ?>" class="img-fluid" alt="Current Photo">
                </div>

                <div class="card-footer">
                    Ширина картинки - <?= Html::encode($width)
                    ?>пикс.<br>
                    Высота картинки - <?= Html::encode($height)
                    ?>пикс.
                </div>

            </div>

        </div>

        <div class='col-xl-6'>
            <div class='card h-100'>

                <div class='card-body'>
                    
                    <?php
                        $form = ActiveForm::begin(
                            [
                                'options' => [
                                    'enctype' => 'multipart/form-data',
                                ],
                            ],
                        ); ?>

                    <!-- Additional ImageInput widget -->
                    
                    <?php
                        try {
                            echo
                            $form->field($model, 'imageFile')->widget(ImageInput::class, [
                                'options' => ['accept' => 'image/jpeg, image/jpg'], // Additional options for the widget
                            ])->label(false);
                        }
                        catch (Exception $e) {
                            PrintHelper::exception(
                                $actionId, 'Выбор сайта ' . LAYOUT_ID, $e,
                            );
                        } ?>

                </div>

                <div class='card-footer'>
                    <?= Html::submitButton('Upload', ['class' => 'btn btn-primary'])
                    ?>
                </div>
                
                <?php
                    ActiveForm::end(); ?>
            </div>
        </div>
    </div>

<?php
    echo '</div>';
