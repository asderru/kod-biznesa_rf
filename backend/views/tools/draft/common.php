<?php
    
    use core\helpers\ButtonHelper;
    use backend\tools\TinyHelper;
    use core\edit\entities\Shop\Razdel;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\ModelEditForm */
    /* @var $form ActiveForm */
    /* @var $parent Model|Razdel */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#tools_draft_common';
    
    $title = 'Черновики. ' . $label;
    
    $this->title                   = 'Создать';
    $this->params['breadcrumbs'][] = ['label' => 'Черновики', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $this->render(
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

            <div class="row mb-3">

                <div class="col-xl-6">

                    <div class="card h-100">

                        <div class='card-header bg-light'>
                            <strong>
                                Общая информация
                            </strong>
                        </div>

                        <div class="card-body">
                            
                            <?= $form->field($model, 'name')->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'value'     => $parent->name,
                            ])
                            ?>
                            
                            <?= $form->field($model, 'title')
                                     ->textarea(
                                         [
                                             'rows'  => 3,
                                             'value' => $parent->title,
                                         ],
                                     )
                            ?>
                        </div>
                        
                        <?php
                            if ($parent?->hasKeywords()) { ?>

                                <div class='card-header bg-light'>
                                    Ключевые слова
                                </div>

                                <div class='card-body'>
                                    <p>
                                        <button type='button'
                                                data-action='seek-words'
                                                class='btn btn-sm btn-primary'>
                                            <?= $parent->getMainKeyword()
                                            ?>
                                        </button>
                                        
                                        <?php
                                            foreach ($parent->getKeywordsArray() as $keyword) { ?>

                                                <button type='button'
                                                        data-action='seek-words'
                                                        class='btn btn-sm btn-outline-secondary'>
                                                    <?= $keyword ?>
                                                </button>
                                                
                                                
                                                <?php
                                            } ?>
                                    </p>
                                </div>
                                <?php
                            } ?>
                    </div>

                    <div class="card-footer">
                        <?= ButtonHelper::submit()
                        ?>
                    </div>

                </div>

            </div>

            <div class="col-xl-6">

                <div class='card h-100'>

                    <div class='card-header bg-light'>
                        <strong>
                            Описание
                        </strong>
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
                                 ->textarea(
                                     [
                                         'value' => $parent->description,
                                     ],
                                 )
                                 ->label(false)
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Полный текст
                </strong>
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
                         ->textarea(
                             [
                                 'value' => $parent->text,
                             ],
                         )
                         ->label(false)
                ?>

            </div>

            <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                
                <?= ButtonHelper::submit()
                ?>

            </div>

        </div>
        
        <?= $form->field($model, 'text_type')
                 ->hiddenInput(['value' => $parent::TEXT_TYPE])->label(false)
        ?>
        <?= $form->field($model, 'parent_id')
                 ->hiddenInput(['value' => $parent->id])->label(false)
        ?>

    </div>

<?php
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
