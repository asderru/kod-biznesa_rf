<?php
    
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Addon\Banner */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#addon_banner_create';
    
    $this->title                   = 'Создание баннера';
    $this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => ['index']];
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

        <div class='card'>
            <div class='card-header bg-light'>
                <strong>
                    Общая информация
                </strong>
            </div>

            <div class='card-body'>
                <?= $form->field($model, 'site_id')->textInput()
                ?>
                
                <?= $form->field($model, 'siteMode')->textInput()
                ?>
                
                <?= $form->field($model, 'typeId')->textInput()
                ?>
                
                <?= $form->field($model, 'parentId')->textInput()
                ?>
                
                <?= $form->field($model, 'name')->textInput(
                    [
                        'maxlength' => true,
                        'required'  => true,
                    ],
                )
                ?>
                
                <?= $form->field($model, 'description')->textInput(['maxlength' => true])
                ?>
                
                <?= $form->field($model, 'picture')->textInput(['maxlength' => true])
                ?>
                
                <?= $form->field($model, 'rating')->textInput(
                    [
                        'type'  => 'number',
                        'min'   => 1,
                        'max'   => 10,
                        'value' => 1,
                    
                    ],
                )
                ?>
                
                <?= $form->field($model, 'status')->textInput()
                ?>


            </div>

            <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                <?= ButtonHelper::submit()
                ?>
            </div>


        </div>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
