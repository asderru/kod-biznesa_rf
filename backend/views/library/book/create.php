<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use backend\widgets\select\SelectSiteWidget;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Content\Tag;
    use core\edit\entities\Library\Book;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Library\AuthorReader;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Library\BookForm */
    /* @var $site Information */
    /* @var $form yii\bootstrap5\ActiveForm */
    /* @var $isAlone bool */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $book Book */
    /* @var $hasBooks bool */
    
    const LAYOUT_ID = '#library_book_create_';
    
    $this->title = 'Создать новый том';
    
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
    );
?>

    <div class='card'>
        
        <?= $this->render('/layouts/tops/_createHeader', [
            'title'    => $this->title,
            'textType' => $textType,
        ]) ?>

        <div class='card-body'>

            <div class='row mb-3'>

                <div class='col-xl-6'>

                    <div class='card h-100 '>

                        <div class='card-header bg-light '>
                            <strong>Новый том</strong>
                        </div>

                        <div class="card-body">
                            <?= $this->render(
                                '../../seo/type/_slugGenerator',
                                [
                                    'model' => $model,
                                    'form'  => $form,
                                ],
                            )
                            ?>
                            
                            <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>
                            <?php
                                try {
                                    echo
                                    $form->field($model, 'authorId')
                                         ->dropDownList
                                         (
                                             AuthorReader::getDropDownFilter(
                                                 null,
                                                 null,
                                                 Constant::STATUS_ACTIVE,
                                             ),
                                             ['prompt' => ' -- -- '],
                                         )
                                         ->label('Выбрать автора (обязательно)')
                                    ;
                                }
                                catch (Exception $e) {
                                    PrintHelper::exception(
                                        'ParametrHelper_getPersonsMap', LAYOUT_ID, $e,
                                    );
                                } ?>

                            <div class='d-flex justify-content-between'>
                                <?= $form->field($model, 'rating')
                                         ->textInput([
                                             'type'  => 'number',
                                             'min'   => 1,
                                             'max'   => 100,
                                             'value' => 10,
                                         ])
                                         ->label('Рейтинг SEO от 1 до 100') ?>
                                <?= SelectHelper::status($form, $model) ?>
                            </div>
                            <div class='btn-group-sm text-end'>
                                <?= ButtonHelper::submit() ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class='col-xl-6'>
                    <div class='card h-100'>
                        <?php
                            if (!$book):
                                try {
                                    echo SelectSiteWidget::widget(
                                        [
                                            'form'     => $form,
                                            'model'    => $model,
                                            'textType' => $textType,
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        'SelectSiteWidget_widget ', LAYOUT_ID, $e,
                                    );
                                    
                                }
                            
                            else: ?>

                                <div class='card-header bg-light'>
                                    Выбранный том:

                                    <strong>
                                        <?= Html::a(
                                            Html::encode($book->name),
                                            [
                                                'library/book/view',
                                                'id' => $book->id,
                                            ],
                                        ) ?></strong>
                                </div>
                                <div class="card-body">

                                    <div class='row'>
                                        <div class='col-md-4'>
                                            <?php
                                                if ($book->photo):
                                                    ?>
                                                    <img
                                                            src="<?= $book->getImageUrl(3) ?>"
                                                            class="img-fluid rounded mb-3"
                                                            alt="<?= Html::encode($book->name) ?>">
                                                    <hr>
                                                <?php
                                                endif; ?>
                                            <?= Html::a(
                                                'Открыть в новом окне',
                                                [
                                                    '/library/book/view',
                                                    'id' => $book->id,
                                                ],
                                                [
                                                    'class'  => 'btn btn-sm btn-primary',
                                                    'target' => '_blank',
                                                ],
                                            )
                                            ?>
                                            <?= Html::a(
                                                'Редактировать в новом окне',
                                                [
                                                    '/library/book/update',
                                                    'id' => $book->id,
                                                ],
                                                [
                                                    'class'  => 'btn btn-sm btn-outline-primary',
                                                    'target' => '_blank',
                                                ],
                                            )
                                            ?>
                                        </div>
                                        <div class="col-md-8">
                                            <dl class="row">
                                                <dt class="col-sm-4">ID тома:</dt>
                                                <dd class="col-sm-8"><?= $book->id ?></dd>
                                                <dt class='col-sm-4'>Заголовок:</dt>
                                                <dd class='col-sm-8'><?= Html::encode($book->title) ?></dd>
                                                <dt class='col-sm-4'>Описание:</dt>
                                                <dd class='col-sm-8'><?= FormatHelper::asDescription($book, 20) ?></dd>
                                            </dl>
                                        </div>
                                    </div>

                                </div>

                                <div class='card-header bg-light'>
                                    <strong>Метки</strong>
                                </div>

                                <div class='card-body'>
                                    
                                    <?= $form->field($model->tags, 'existing')
                                             ->inline()
                                             ->checkboxList(
                                                 Tag::getTagsForCheckboxList($book->site_id),
                                                 ['id' => 'tags-existing'],
                                             )
                                             ->label('Выбрать существующие метки:')
                                    ?>
                                    
                                    <?= $form->field($model->tags, 'textNew')
                                             ->label('Добавить новые метки, через запятую:') ?>
                                    
                                    
                                    <?php
                                        echo $form->field($model, 'site_id')->hiddenInput(['value' => $book->site_id])->label(false);
                                        echo $form->field($model, 'parentId')->hiddenInput(['value' => $book->id])->label(false);
                                    ?>

                                </div>
                            
                            <?php
                            endif; ?>

                        <div class='card-footer'>
                            <div class='d-flex justify-content-between'>
                                <?= $form->field($model, 'rating')
                                         ->textInput([
                                             'type'  => 'number',
                                             'min'   => 1,
                                             'max'   => 10,
                                             'value' => 1,
                                         ])
                                         ->label('Рейтинг SEO от 1 до 10') ?>
                                <?= SelectHelper::status($form, $model) ?>
                            </div>

                            <div class='btn-group-sm'>
                                <?= ButtonHelper::submit() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                         ->label(false)
                ?>
            </div>

            <div class='card-footer'>
                <?= ButtonHelper::submit() ?>
            </div>

            <div class='card-header bg-light'>
                <strong>
                    Текст о томе
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

            <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                <?= ButtonHelper::submit()
                ?>
            </div>
        </div>

    </div>


<?php
    
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
