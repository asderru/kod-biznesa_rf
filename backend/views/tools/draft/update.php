<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use backend\tools\TinyHelper;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $draft core\edit\entities\Tools\Draft */
    /* @var $model core\edit\forms\ModelEditForm */
    /* @var $parent Model|Razdel */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $title string */
    /* @var $status ?string */
    
    const LAYOUT_ID = '#tools_draft_update';
    
    $this->title                   = $draft->name . '. Правка';
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = [
        'label' => $draft->name,
        'url'   => ['view', 'id' => $draft->id],
    ];
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
    
    try {
        echo
        PagerWidget::widget(
            [
                'model' => $draft,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
    $form = ActiveForm::begin(
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
            'model' => $draft,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>

    <div class='card-header bg-light d-flex justify-content-between'>

        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>

        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::delete($draft)
            ?>
            <?= ButtonHelper::submit()
            ?>

        </div>

    </div>

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
                        
                        
                        <?= $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        )
                        ?>
                        
                        <?= $form->field($model, 'title')
                                 ->textarea(
                                     [
                                         'rows' => 3,
                                     ],
                                 )
                        ?>
                        
                        
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
                                            }
                                        ?>
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
                                 ->textarea()
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
                         ->textarea()
                    ->label(false)
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
