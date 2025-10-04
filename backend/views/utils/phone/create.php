<?php
    
    use core\read\readers\Admin\SiteModeReader;
    use core\edit\entities\Admin\Country;
    use core\edit\entities\Utils\Phone;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\MaskedInput;
    
    /** @var yii\web\View $this */
    /** @var Model $parent */
    /** @var Phone $model */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_phone_create_';
    
    $this->title = 'Добавить телефон к модели: ' . $parent->name;
    
    $this->params['breadcrumbs'][] = ['label' => 'Телефоны', 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Добавить телефон';
    
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

            <div class='col-lg-6'>
                
                <?= $form->field($model, 'name')->textInput(['maxlength' => true])
                         ->label('Тип номера') ?>
                
                <?php
                    echo
                    $form
                        ->field($model, 'status')
                        ->radioList(
                            SiteModeReader::getSimpleStatusesMap($textType),
                            [
                                'itemOptions' => [
                                    'labelOptions' => ['class' => 'radio-inline mr-3'],
                                    'class'        => 'mr-1',
                                ],
                                'value'       => $parent->status,
                            ],
                        )
                        ->label('Выбрать статус номера:')
                    ;
                ?>

            </div>

            <div class='col-lg-6'>
                
                
                <?php
                    try {
                        echo $form->field($model, 'country_code')->dropDownList(
                            ArrayHelper::map(
                                Country::getCountryCodesList(),
                                'code',
                                function ($country) {
                                    return ' +' . $country['code'] . ' ' . $country['name'];
                                },
                            ),
                            [
                                'prompt'   => 'Выберите страну',
                                'required' => true,
                            ],
                        );
                        
                    }
                    catch (Exception $e) {
                        PrintHelper::exception(
                            'SiteModeReader::getCountriesMap',
                            LAYOUT_ID,
                            $e,
                        );
                    } ?>
                
                <?php
                    try {
                        echo
                        $form->field($model, 'phone')->widget(MaskedInput::class, [
                            'mask'          => '(999)999-99-99',
                            'options'       => [
                                'class'         => 'form-control',
                                'placeholder'   => '(999)999-99-99',
                                'required'      => true, // HTML5 validation
                                'data-required' => 'true', // Для Yii2 validation
                            ],
                            'clientOptions' => [
                                'removeMaskOnSubmit' => true,
                            ],
                        ]);
                    }
                    catch (Exception $e) {
                        PrintHelper::exception(
                            'MaskedInput_widget',
                            LAYOUT_ID,
                            $e,
                        );
                        
                    } ?>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
                <?php
                    echo $form->field($model, 'site_id')
                              ->hiddenInput(['value' => $parent->site_id])->label(false)
                    ;
                    echo $form->field($model, 'siteMode')
                              ->hiddenInput(['value' => $parent->siteMode])->label(false)
                    ;
                    echo $form->field($model, 'textType')
                              ->hiddenInput(['value' => $parent->textType])->label(false)
                    ;
                    echo $form->field($model, 'parentId')
                              ->hiddenInput(['value' => $parent->id])->label(false)
                    ;
                ?>
            </div>

        </div>


    </div>


<?php
    ActiveForm::end();
