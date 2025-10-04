<?php
    
    use backend\widgets\DateTimePicker\DateTimePicker;
    use core\edit\entities\Content\Review;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $isAlone bool */
    /* @var $model Review */
    /* @var $timeForm core\edit\forms\TimeForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#blog_post_time';
    
    $this->title = 'Изменение времени публикации';
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
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
            <div class="row mb-3">

                <div class="col-xl-6">

                    <div class='card h-100'>

                        <div class='card-header bg-light'>

                            Пост <strong>
                                <?= Html::encode($model->name)
                                ?>
                            </strong>

                        </div>
                        <div class="card-body">
                            <?= FormatHelper::asHtml($model->description)
                            ?>

                        </div>


                    </div>

                </div>

                <div class="col-xl-6">

                    <div class='card h-100'>

                        <div class='card-header bg-light'>
                            Установить новое время публикации
                        </div>
                        <div class='card-body'>
                            <?php
                                try {
                                    echo
                                    $form
                                        ->field($timeForm, 'createdAt')
                                        ->widget(
                                            DateTimePicker::classname(),
                                        )
                                        ->label(false)
                                    ;
                                }
                                catch (Exception|Throwable $e) {
                                    PrintHelper::exception(
                                        'DatePicker ' .
                                        $actionId, LAYOUT_ID, $e,
                                    );
                                }
                            ?>
                            <small>Если оставить поле пустым, будет установлено текущее время</small>
                        </div>
                        <div class='card-footer'>
                            <?= ButtonHelper::submit()
                            ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

<?php
    ActiveForm::end();
