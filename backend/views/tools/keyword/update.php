<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\edit\entities\Tools\Keyword;
    use core\edit\forms\Tools\KeywordForm;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $keyword Keyword */
    /* @var $parent Model */
    /* @var $model KeywordForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_keyword_update';
    
    $this->title                   = $keyword->name . '. Правка';
    
    
    $this->render(
        '/layouts/tops/_parentBreadcrumbs.php',
        [
            'model'  => $keyword,
            'parent' => $parent,
        ],
    );
    
    $this->params['breadcrumbs'][] = ['label' => 'Ключевые слова', 'url' => ['index']];
    $this->params['breadcrumbs'][] = [
        'label' => $keyword->name,
        'url'   => ['view', 'id' => $keyword->id],
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
            'model' => $keyword,
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
                            Ключевое слово
                        </strong>
                    </div>

                    <div class='card-body'>
                        <?= $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        )->label(false)
                        ?>
                        <hr>

                    </div>

                    <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                        <?= ButtonHelper::submit()
                        ?>
                    </div>

                </div>

            </div>
            <div class='col-xl-6'>
                <div class='card h-100'>

                    <div class='card-header bg-light'>
                        <strong>
                            Другие ключевые слова (с новой строки).
                        </strong>
                    </div>

                    <div class='card-body'>
                        
                        <?= $form->field(
                            $model, 'keywords',
                        )
                                 ->textarea(
                                     [
                                         'rows' => 9,
                                     ],
                                 )
                            ->label(false)
                        ?>

                    </div>

                </div>

            </div>

        </div>
    </div>

<?php
    echo '</div>';
    
    
    echo Html::activeHiddenInput(
        $model, 'site_id',
        [
            'value' => $keyword->site_id,
        ],
    );
    
    echo Html::activeHiddenInput(
        $model, 'typeId',
        [
            'value' => $keyword->text_type,
        ],
    );
    
    echo Html::activeHiddenInput(
        $model, 'parentId',
        [
            'value' => $keyword->parent_id,
        ],
    );
    
    ActiveForm::end();
