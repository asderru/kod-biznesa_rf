<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\tools\TinyHelper;
    use core\helpers\ButtonHelper;
    use core\helpers\ParametrHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Library\Author */
    /* @var $label string */
    /* @var $actionId string */
    /* @var $textType int */
    /* @var $prefix string */
    
    const LAYOUT_ID = '#library_author_updateRoot';
    
    $site = ParametrHelper::getSite();
    
    $this->title = 'Правка корневого автора';
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::structure($textType);
    if ($model::hasModels($model->site_id)) {
        $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    }
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
?>
    <div class='card bg-light'>
        <div class='card-header bg-light h4'>
            <?= Html::encode($this->title)
            ?>
        </div>

        <div class='card-body'>

            <div class='row mb-3'>

                <div class='col-xl-6'>

                    <div class='card h-100'>

                        <div class='card-body'>
                            
                            <?= $form->field($model, 'name')
                                     ->textInput(
                                         [
                                             'maxlength' => true,
                                             'value'     => $label,
                                         ],
                                     )
                                ->label('Общее название для авторов сайта')
                            ?>
                            
                            <?= $form->field($model, 'title')
                                     ->textInput(
                                         [
                                             'maxlength' => true,
                                             'value'     => $label,
                                         ],
                                     )
                                     ->label(
                                         'Полное название всех авторов сайта для главных
				страниц сайта',
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
                                             'value' => $label,
                                         ],
                                     )
                                ->label('Краткое описание всех авторов сайта')
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='card mb-3'>

        <div class='card-header bg-light'>
            <strong>
                Полный текст для описания всех авторов сайта
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
                             'value' => $label,
                         ],
                     )
                ->label(false)
            ?>
        </div>
        <div class='card-footer'>
            <?= ButtonHelper::submit()
            ?>
        </div>
    </div>


<?= Html::activeHiddenInput(
    $model, 'site_id',
    [
        'value' => Parametr::siteId(),
    ],
)
?>

<?= Html::activeHiddenInput(
    $model, 'user_id',
    [
        'value' => Yii::$app->user->id,
    ],
)
?>

<?= Html::activeHiddenInput(
    $model, 'type_id',
    [
        'value' => Yii::$app->params['siteMode'],
    ],
)
?>

<?= Html::activeHiddenInput(
    $model, 'contact',
    [
        'value' => null,
    ],
)
?>

<?= Html::activeHiddenInput(
    $model, 'website',
    [
        'value' => null,
    ],
)
?>

<?= Html::activeHiddenInput(
    $model, 'photo',
    [
        'value' => null,
    ],
)
?>

<?= Html::activeHiddenInput(
    $model, 'status',
    [
        'value' => Constant::STATUS_ROOT,
    ],
)
?>

<?= Html::activeHiddenInput(
    $model, 'sort',
    [
        'value' => Constant::STATUS_ROOT,
    ],
)
?>

<?= Html::activeHiddenInput(
    $model, 'rating',
    [
        'value' => Constant::STATUS_ROOT,
    ],
)
?>

<?= Html::activeHiddenInput(
    $model, 'updated_at',
    [
        'value' => time(),
    ],
)
?>

<?php
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
