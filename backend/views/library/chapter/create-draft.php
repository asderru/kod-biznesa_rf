<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use core\read\readers\Admin\InformationReader;
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Library\ChapterForm */
    /* @var $chapter core\edit\entities\Library\Chapter */
    /* @var $form yii\bootstrap5\ActiveForm */
    /* @var $draft core\edit\entities\Tools\Draft */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_chapter_createDraft';
    
    $this->title = 'Создать главу из черновика ' . $draft->name;
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::chapters();
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
    )
?>

    <div class='card'>
        
        <?= $this->render(
            '/layouts/tops/_createHeader',
            [
                'title'    => $this->title,
                'textType' => $textType,
            ],
        )
        ?>

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
                                         InformationReader::getDropDownFilter(0),
                                         [
                                             'prompt' => ' -- ',
                                         ],
                                     )
                                     ->label('Выбрать сайт')
                            ; ?>
                            
                            <?= $form->field($model, 'name')->textInput([
                                'maxlength' => true,
                                'required' => true,
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

                        <div class='card-header bg-light'>
                            <strong>
                                Описание главы
                            </strong>
                        </div>

                        <div class='card-body'>
                            
                            <?= $form->field(
                                $model, 'description',
                                [
                                    'inputOptions' =>
                                        [
                                            'id' => 'description-edit-area',
                                        ]
                                    ,
                                ],
                            )
                                     ->textarea(
                                         [
                                             'value' => $draft->description,
                                         ],
                                     )
                                     ->label(false)
                            ?>
                            <?= Html::activeHiddenInput(
                                $model, 'rating',
                                ['value' => 1,],
                            )
                            ?>
                            
                            <?= Html::activeHiddenInput(
                                $model, 'video',
                                ['value' => null,],
                            )
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
                        Текст главы
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

    </div>

<?php
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
