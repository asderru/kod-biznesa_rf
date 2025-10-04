<?php
    
    use backend\tools\TinyHelper;
    use backend\widgets\PagerWidget;
    use core\read\readers\Tools\GalleryReader;
    use core\read\readers\Utils\GalleryReader;
    use core\edit\forms\Utils\Gallery\GalleryForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model GalleryForm */
    /* @var $gallery core\edit\entities\Utils\Gallery */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_gallery_update';
    
    $this->title                   = $gallery->name . '. Правка';
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = [
        'label' => $gallery->name,
        'url'   => ['view', 'id' => $gallery->id],
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
                'model' => $gallery,
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
            'model' => $gallery,
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
                                Информация
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
                                            GalleryReader::getDropDownFilter(
                                                Constant::THIS_FIRST_NODE,
                                            ),
                                        )
                                        ->label(
                                            'Родительская галерея (ID сайта)',
                                        )
                                    ;
                                }
                                catch (Exception|Throwable $e) {
                                    PrintHelper::exception(
                                        $actionId, 'Widget ParentModelsList' . LAYOUT_ID, $e,
                                    );
                                }
                            ?>
                        </div>
                        <div class='card-footer'>
                            Сайт - <?= Html::encode
                            (
                                $gallery->site->name,
                            ) . '. ID сайта #' .
                                       $gallery->site_id ?>
                        </div>
                    </div>

                </div>

                <div class='col-xl-6'>
                    <div class='card h-100'>
                        <div class='card-header bg-light'>
                            <strong>
                                Описание галереи
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
                        <div class='card-footer'>
                            
                            <?= ButtonHelper::submit()
                            ?>

                        </div>
                    </div>

                </div>

            </div>
        </div>

                <div class='card-header bg-light'>
                    <strong>
                        Текст галереи
                    </strong>
                </div>

                <div class="card-body">
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
                 ->checkboxList($model->tags::tagsList($gallery->site_id))
                 ->label('Отметить:') ?>
    </div>

<?php
    echo '</div>';
    echo Html::activeHiddenInput(
        $model, 'slug',
        [
            'value' => $gallery->slug,
        ],
    );
    
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
