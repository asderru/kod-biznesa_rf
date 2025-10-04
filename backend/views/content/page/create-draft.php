<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use core\edit\editors\Admin\InformationEditor;
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Content\PageForm */
    /* @var $page core\edit\entities\Content\Page */
    /* @var $form yii\bootstrap5\ActiveForm */
    /* @var $draft core\edit\entities\Tools\Draft */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_page_createDraft';
    
    $this->title = 'Создать страницу из черновика ' . $draft->name;
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::pages();
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
    ); ?>
    <div class='card'>
        <?= $this->render(
            '/layouts/tops/_createHeader',
            [
                'title'    => $this->title,
                'textType' => $textType,
            ],
        );
            if ($model->hasErrors()): ?>
                <div class="container alert alert-danger p-4">
                    <?= Html::errorSummary($model) ?>
                </div>
            <?php
            endif; ?>
        <div class='card-body'>
            <div class='row mb-3'>

                <div class='col-xl-6'>

                    <div class='card h-100'>
                        <div class='card-header bg-light'>
                            <strong>
                                Общая информация
                            </strong>
                        </div>

                        <div class='card-body'>
                            
                            <?= $form->field($model, 'site_id')
                                     ->dropDownList(
                                         InformationEditor::getDropDownFilter(0),
                                         [
                                             'prompt' => ' -- ',
                                         ],
                                     )
                                     ->label('Выбрать сайт')
                            ; ?>
                            
                            <?= $form->field($model, 'name')->textInput([
                                'maxlength' => true,
                                'required'  => true,
                                'value'     => $draft->name,
                            ])
                            ?>
                            <?= $form->field($model, 'slug')->textInput(['maxlength' => true])
                            ?>
                            
                            
                            <?= $form->field($model, 'title')
                                     ->textarea(
                                         [
                                             'rows'  => 3,
                                             'value' => $draft->title,
                                         ],
                                     )
                            ?>
                        </div>

                    </div>

                </div>

                <div class='col-xl-6'>

                    <div class='card h-100'>
                        
                        <?= $this->render(
                            '/layouts/partials/_description',
                            [
                                'form'     => $form,
                                'model'    => $model,
                                'textType' => $textType,
                            ],
                        ) ?>
                        
                        <?= $form->field($model, 'rating')
                                 ->textInput(
                                     [
                                         'type'  => 'number',
                                         'min'   => 1,
                                         'max'   => 100,
                                         'value' => 10,
                                     ],
                                 )
                                 ->label('Рейтинг SEO от 1 до 100')
                        ?>
                    </div>
                    <div class='card-footer'>
                        <?= SelectHelper::status($form, $model) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class='card'>

            <div class='card-header bg-light'>
                <strong>
                    Текст страницы
                </strong>
            </div>

            <div class='card-body'>
                <?= $form->field(
                    $model, 'text',
                    [
                        'inputOptions' =>
                            ['id' => 'text-edit-area',],
                    ],
                )->textarea(
                    [
                        'value' => $draft->text,
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
    
    ActiveForm::end();
    TinyHelper::getText();
