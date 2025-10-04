<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\tools\TinyHelper;
    use backend\widgets\PagerWidget;
    use core\read\readers\Library\BookReader;
    use core\edit\entities\Library\Book;
    use core\edit\forms\Library\BookForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $book Book */
    /* @var $model BookForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_book_update';
    
    $this->title = $book->name . '. Правка';
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::BOOK_TYPE);
    $this->params['breadcrumbs'][] = [
        'label' => $book->name,
        'url'   => ['view', 'id' => $book->id],
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
                'model'  => $book,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
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
        '/layouts/tops/_updateHeader',
        [
            'model'    => $book,
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
                        
                        <?= $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        )
                        ?>
                        
                        <?= $form->field($model, 'title')->textarea(
                            [
                                'rows' => 3,
                            ],
                        )
                        ?>
                        
                        <?php
                            try {
                                echo
                                $form
                                    ->field($model, 'parentId')
                                    ->dropDownList(
                                        BookReader::getDropDownFilter(
                                            Constant::THIS_FIRST_NODE,
                                            $book->site_id,
                                            $book->id,
                                        ),
                                    )
                                    ->label(
                                        'Родительский том (ID тома)',
                                    )
                                ;
                            }
                            catch (Exception|Throwable $e) {
                                PrintHelper::exception(
                                    'Widget ParentModelsList'
                                    . $actionId, LAYOUT_ID, $e,
                                );
                            }
                        ?>
                        
                        <?= $form->field($model, 'rating')
                                 ->textInput(
                                     [
                                         'type'    => 'number',
                                         'min'     => 1,
                                         'max'     => 100,
                                         'value' => $model->rating ?: 1, // Устанавливаем значение по умолчанию 1
                                     ],
                                 )
                                 ->label('Рейтинг SEO от 1 до 100')
                        ?>

                        <strong>Время обновления: </strong>
                        <?php
                            echo
                            FormatHelper::asDateTime
                            (
                                $book->updated_at,
                            ); ?>

                    </div>

                    <div class='card-footer'>
                        Сайт - <?= Html::encode
                        (
                            $book->site->name,
                        ) . '. ID сайта #' .
                                   $book->site_id ?>
                    </div>

                </div>

            </div>

            <div class='col-xl-6'>

                <div class='card h-100'>

                    <div class='card-header bg-light'>
                        <strong>
                            Описание тома
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
                            ->label(false) ?>

                    </div>
                </div>
            </div>
        </div>

    </div>

<?= $this->render(
    '/layouts/partials/_keywords',
    [
        'model' => $book,
    ],
)
?>

    <div class="card-header bg-body-secondary">
        <strong>
            Текст тома
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
                 ->label(false) ?>

    </div>

    <div class='card-footer text-end'>
        <?= ButtonHelper::submit()
        ?>
    </div>

    <div class='card-header bg-light'>
        <strong>
            Метки
        </strong>
    </div>
    <div class='card-body'>
        
        <?= $form->field($model->tags, 'textNew')
                 ->label('Добавить новые метки, через запятую:')
        ?>
        <hr>
        
        <?= $form->field($model->tags, 'existing')
                 ->inline()
                 ->checkboxList($model->tags::tagsList($book->site_id))
                 ->label('Отметить:') ?>
    </div>

<?php
    
    echo '</div>';
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
