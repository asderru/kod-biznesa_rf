<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\edit\entities\Admin\PhotoSize;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Admin\PhotoSizeForm */
    /* @var $photo PhotoSize */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_photo_update';
    
    $this->title                   = $photo::getTypeName() . '. Правка размеров основного изображения';
    $this->params['breadcrumbs'][] = [
        'label' => 'Размеры изображений',
        'url'   =>
            ['index'],
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
        '/layouts/tops/_updateHeader',
        [
            'model'    => $photo,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>

    <div class="row">

        <div class='col-xl-6'>

            <div class='card-body'>
                
                <?= $form->field($model, 'width')->textInput(
                    [
                        'type'  => 'number',
                        'value' => $photo->width,
                    ],
                
                )
                ?>
                
                <?= $form->field($model, 'height')->textInput(
                    [
                        'type'  => 'number',
                        'value' => $photo->height,
                    ],
                
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'site_id',
                    [
                        'value' => $photo->site_id,
                    ],
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'typeId',
                    [
                        'value' => $photo->text_type,
                    ],
                )
                ?>


            </div>

            <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                
                <?= ButtonHelper::submit()
                ?>

            </div>

        </div>


        <div class='col-xl-6'>
            <div class='card-body'>
                
                <?= $form->field($model, 'watermark')->radioList(
                    [
                        0 => 'Отсутствует',
                        1 => 'Присутствует',
                    ],
                    [
                        'item' => function ($index, $label, $name, $checked, $value) use ($photo) {
                            $checked = $value == $photo->watermark;
                            return '<label class="radio-inline">' .
                                   Html::radio($name, $checked, ['value' => $value]) . ' ' . $label .
                                   '</label>';
                        },
                    ],
                )->label('Ватермарк на больших фото')
                ?>
            </div>
        </div>

    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();;
