<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use backend\tools\TinyHelper;
    use core\edit\entities\Admin\PhotoRatio;
    use core\edit\forms\Admin\PhotoRatioForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $ratio PhotoRatio */
    /* @var $model PhotoRatioForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_ratio_update';
    
    $this->title                   = $ratio->type->name;
    $this->params['breadcrumbs'][] = ['label' => 'Пропорции картинок', 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Правка ' . $ratio->type->name;
    
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
            'model'    => $ratio,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>

    <div class='card-body'>

        <div class="card">

            <div class='card-header bg-light'>
                <strong>
                    Соотношение высоты к ширине (условно в 10000пкс.)
                </strong>
            </div>
            <div class='card-body'>
                <div class="row">
                    <div class="col-xl-6">
                        
                        <?= $form->field($model, 'ratio1')->textInput()
                        ?>
                        
                        <?= $form->field($model, 'ratio2')->textInput()
                        ?>
                        
                        <?= $form->field($model, 'ratio3')->textInput()
                        ?>
                    </div>
                </div>
                
                <?= Html::activeHiddenInput(
                    $model, 'site_id',
                    [
                        'value' => $ratio->site_id,
                    ],
                )
                ?>
                
                <?= Html::activeHiddenInput(
                    $model, 'typeId',
                    [
                        'value' => $ratio->text_type,
                    ],
                )
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
    TinyHelper::getText();
    TinyHelper::getDescription();
