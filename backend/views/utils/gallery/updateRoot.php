<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\tools\TinyHelper;
    use core\helpers\ButtonHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Shop\Brand */
    
    const LAYOUT_ID = '#utils_gallery_updateRoot';
    $label    = 'Галереи';
    
    $this->title = 'Правка корневой галереи';
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::structure($textType);
    if ($model::hasModels($model->site_id)) {
        $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    }
    $this->params['breadcrumbs'][] = $this->title; ?>


    <div class='row'>

        <div class='col-12'>

            <div class='card bg-light'>
                <div class='card-header bg-light'>
                    <h4>
                        <?= Html::encode($this->title)
                        ?>
                    </h4>
                </div>

                <div class='card-body'>
                    
                    <?php
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
                        )
                    ?>
                    
                    <?= $form->field($model, 'name')
                             ->textInput([
                                 'maxlength' => true,
                             ])
                        ->label('Общее название для всех галерей сайта')
                    ?>
                    
                    <?= $form->field($model, 'title')
                             ->textInput([
                                 'maxlength' => true,
                             ])
                             ->label(
                                 'Полное название всех галерей для главной
				страницы.',
                             )
                    ?>
                    
                    <?= $form->field($model, 'description')
                             ->textarea()
                        ->label('Краткое описание всех галерей сайта')
                    ?>
                    
                    <?= $form->field($model, 'text')
                             ->textarea()
                        ->label('Полный текст для описания всех галерей сайта')
                    ?>
                    
                    <?= Html::activeHiddenInput(
                        $model, 'site_id',
                        [
                            'value' => Parametr::siteId(),
                        ],
                    )
                    ?>
                    
                    <?= Html::activeHiddenInput(
                        $model, 'created_at',
                        [
                            'value' => $model->created_at,
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
                </div>

                <div class='card-footer bg-light'>
                    
                    <?= ButtonHelper::submit()
                    ?>
                </div>
                
                <?php
                    ActiveForm::end(); ?>


            </div>

        </div>

    </div>

<?= TinyHelper::getText()
?>
<?= TinyHelper::getDescription();
