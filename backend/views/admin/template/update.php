<?php
    
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Admin\Template */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_template_update';
    
    $this->title                   = 'Правка типа: ' . $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Типы контента', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
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
            'model'    => $model,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>
    <div class='card-body'>

    <div class='row'>

        <div class='col-xl-6'>
            <div class='card mb-3'>
                <div class='card-header bg-light'>
                    <strong>
                        Общая информация
                    </strong>
                </div>
                <div class='card-body'>
                    
                    <?= $form->field($model, 'name')->textInput(
                        [
                            'maxlength' => true,
                            'required'  => true,
                        ],
                    )
                    ?>
                    
                    <?= $form->field($model, 'slug')->textInput(['maxlength' => true])
                    ?>
                    
                    <?= $form->field($model, 'repository')->textInput(['maxlength' => true])
                    ?>

                </div>
            </div>
        </div>
        <div class='col-xl-6'>
            <div class='card mb-3'>
                <div class='card-header bg-light'>
                    <strong>
                        Описание
                    </strong>
                </div>
                <div class='card-body'>
                    <?= $form->field($model, 'description')->textarea(['rows' => 6])
                    ?>

                </div>
                <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                    <?= ButtonHelper::submit()
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
