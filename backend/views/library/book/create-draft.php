<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\tools\TinyHelper;
    use core\read\readers\Admin\InformationReader;
    use core\read\readers\Library\AuthorReader;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Library\BookForm */
    /* @var $book core\edit\entities\Library\Book */
    /* @var $form yii\bootstrap5\ActiveForm */
    /* @var $draft core\edit\entities\Tools\Draft */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_book_createDraft';
    
    $this->title = 'Создать книгу из черновика ' . $draft->name;
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::BOOK_TYPE);
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
                            
                            <?php
                                try {
                                    echo
                                    $form->field($model, 'authorId')
                                         ->dropDownList(
                                             AuthorReader::getDropDownArray(null, null, Constant::THIS_FIRST_NODE),
                                             ['prompt' => ' -- -- '],
                                         )
                                         ->label('выбрать автора (обязательно):')
                                    ;
                                }
                                catch (Exception $e) {
                                    PrintHelper::exception(
                                        $actionId, 'Выбор автора ' . LAYOUT_ID, $e,
                                    );
                                } ?>
                            
                            <?= $form->field($model, 'name')->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'value'     => $draft->name,
                            ])
                            ?>
                            <?= $form->field($model, 'slug')->textInput(['maxlength' => true])
                            ?>
                            
                            
                            
                            
                            <?= $form->field($model, 'rating')->textInput(
                                [
                                    'type'  => 'number',
                                    'value' => 1,
                                    'min'   => 1,
                                    'max'   => 100,
                                
                                ],
                            )
                            ?>
                        </div>
                        <div class='card-footer'>
                            <?= ButtonHelper::submit()
                            ?>
                        </div>
                    </div>
                </div>
                <div class='col-xl-6'>
                    <div class='card h-100'>
                        <div class="card-header bg-body-secondary">
                            <strong>Полное название книги</strong>
                        </div>
                        <div class='card-body'>
                            <<?= $form->field($model, 'title')
                                      ->textarea(
                                          [
                                              'rows'  => 3,
                                              'value' => $draft->title,
                                          ],
                                      )
                                      ->label(false)
                            ?>
                        </div>
                        <div class="card-header bg-body-secondary">
                            <strong>Краткое описание книги</strong>
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
                        </div>
                    </div>
                </div>
            </div>

            <div class='card'>

                <div class='card-header bg-light'>
                    <strong>
                        Текст книги
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
