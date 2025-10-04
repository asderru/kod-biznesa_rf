<?php
    
    use backend\tools\TinyHelper;
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Admin\Tariff */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_tariff_update';
    
    $this->title                   = 'Правка тарифа: ' . $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Тарифы', 'url' => ['index']];
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

        <div class="card">

            <div class='card-body'>

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
                    
                    <?= $form->field($model, 'description')->textarea(['rows' => 6])
                    ?>
                    
                    <?= $form->field($model, 'price')->textInput()
                    ?>
                    
                    <?= $form->field($model, 'payment')->textInput()
                    ?>
                    
                    <?= $form->field($model, 'period')->textInput()
                    ?>
                    
                    <?= $form->field($model, 'lft')->textInput()
                    ?>
                    
                    <?= $form->field($model, 'rgt')->textInput()
                    ?>
                    
                    <?= $form->field($model, 'depth')->textInput()
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
    TinyHelper::getText();
    TinyHelper::getDescription();
