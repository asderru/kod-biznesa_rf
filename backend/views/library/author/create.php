<?php
    
    use core\edit\entities\Admin\Information;
    use core\helpers\ButtonHelper;
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Library\AuthorForm */
    /* @var $site Information */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_author_create';
    
    $this->title = 'Добавить в команду!';
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Команда',
        'url'   => ['index'],
    ];
    
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

        <div class="row mb-3">

            <div class="col-xl-6">

                <div class="card h-100">
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
                        )->label('Имя (никнейм)')
                        ?>
                        
                        <?= $form->field($model, 'slug')->textInput(['maxlength' => true])->label('английское написание')
                        ?>
                        
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('ФИО полностью')
                        ?>
                                                <?= $form->field($model, 'website')->textInput(['maxlength' => true])->label('ссылка (при необходимости)')
                        ?>
                        
                        <?= $form->field($model, 'contact')->textarea(['rows' => 3])->label('контактные данные (при необходимости) / должность')
                        ?>

                    </div>
                </div>

            </div>


            <div class='col-xl-6'>

                <div class='card h-100'>
                    <div class='card-header bg-body-secondary'>
                        <strong>Биографическаая справка</strong>
                    </div>
                    <div class='card-body'>
                        
                        <?= $form->field(
                            $model, 'description',
                            [
                                'inputOptions' => [
                                    'id' => 'description-edit-area',
                                ],
                            ],
                        )
                                 ->textarea()
                                 ->label(false)
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="card-header bg-body-secondary">
        <strong>Био, интересы, прочее</strong>
    </div>
    <div class='card-body'>
        <?= $form->field(
            $model, 'text',
            [
                'inputOptions' => [
                    'id' => 'text-edit-area',
                ],
            ],
        )
                 ->textarea()
                 ->label(false)
        ?>
    </div>
    <div class='card-footer'>
        <?= ButtonHelper::submit()
        ?>
    </div>

<?php
    echo '</div>';
    
    echo $form->field($model, 'site_id')->hiddenInput(['value' => Parametr::siteId()])->label(false);
    echo $form->field($model, 'typeId')->hiddenInput(['value' => 1])->label(false);
    echo $form->field($model->tags, 'textNew')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model->tags, 'existing')->hiddenInput(['value' => ''])->label(false);
    
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
