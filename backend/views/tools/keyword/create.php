<?php
    
    use backend\helpers\SelectHelper;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $parent Model|Razdel */
    /* @var $model core\edit\forms\Tools\KeywordForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_keyword_create';
    
    $this->title = 'Создать заметку';
    
    $this->params['breadcrumbs'][] = ['label' => 'Ключевые слова', 'url' => ['index']];
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

        <div class='col-md-8'>
            <div class='card mb-3'>
                <div class='card-header bg-secondary text-white'>
                    Добавить ключевое слово
                </div>
                <div class="card-body">
                    
                    <?php
                        try {
                            echo
                            SelectHelper::getSites($form, $model, true);
                        }
                        catch (Exception $e) {
                            PrintHelper::exception(
                                $actionId, 'Выбор сайта ' . LAYOUT_ID, $e,
                            );
                        } ?>
                    
                    <?= Html::activeHiddenInput(
                        $model, 'parentId',
                        [
                            'value' => 1,
                        ],
                    )
                    ?>
                    
                    <?= Html::activeHiddenInput(
                        $model, 'typeId',
                        [
                            'value' => 1,
                        ],
                    )
                    ?>
                    <?= Html::activeHiddenInput(
                        $model, 'status',
                        [
                            'value' => Constant::STATUS_DRAFT,
                        ],
                    )
                    ?>
                    
                    <?= $form
                        ->field($model, 'name')
                        ->textInput(
                            [
                                'maxlength' => true,
                            ],
                        )
                    ?>
                    <?= $form
                        ->field(
                            $model,
                            'keywords',
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

    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
