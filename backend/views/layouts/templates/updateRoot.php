<?php
    
    use backend\tools\TinyHelper;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Forum\Thread;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\ModelEditForm;
    use core\helpers\ButtonHelper;
    use core\helpers\DateHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model ModelEditForm */
    /* @var $root Model|Product|Article|Chapter|Post|Thread|Razdel|Section|Book|Category|Page|Information|Group */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#layouts_templates_updateRoot';
    
    $this->title = TypeHelper::getName($textType, null, true, true) . '. Правка корневой модели';
    
    
    $url = TypeHelper::getLongEditUrl($textType);
    
    $this->params['breadcrumbs'][] = [
        'label' => TypeHelper::getName($textType, null, true, true),
        'url'   => [$url . 'index'],
    ];
    
    $this->params['breadcrumbs'][] = $this->title;
    
    echo $this->render(
        '/layouts/tops/_infoHeader', [
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
    )
?>

    <div class='card'>
        
        <?= $this->render(
            '/layouts/tops/_updateHeader',
            [
                'model' => $root,
                'title'    => $this->title,
                'textType' => $textType,
            ],
        )
        ?>

        <div class='card-body'>
            <small><?= DateHelper::timeDifferenceMessage($root)
                ?></small>

            <div class='row mb-3'>

                <div class="col-xl-6">

                    <div class='card h-100'>

                        <div class='card-header bg-light'>
                            <strong>
                                <?= Html::encode($this->title)
                                ?>
                            </strong>
                        </div>

                        <div class='card-body'>
                            
                            
                            <?= $form->field($model, 'name')
                                     ->textInput(
                                         [
                                             'maxlength' => true,
                                             'value'     => $label,
                                         ],
                                     )
                                ->label('Общее название для всех ' . TypeHelper::getName($textType, 1, true) . ' сайта')
                            ?>
                            
                            <?= $form->field($model, 'title')
                                     ->textInput(
                                         [
                                             'maxlength' => true,
                                             'value'     => $label,
                                         ],
                                     )
                                     ->label(
                                         'Полное название всех ' . TypeHelper::getName($textType, 1, true) . ' сайта для главной страницы.',
                                     )
                            ?>


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
                                Краткое описание
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
                                ->label('Краткое описание для всех ' . TypeHelper::getName($textType, 1, true) . ' сайта')
                            ?>
                        </div>

                    </div>

                </div>

            </div>
            
            <?php
                if ($textType !== Constant::ANONS_TYPE) { ?>

                    <div class='card mb-3'>

                        <div class='card-header bg-light'>
                            <strong>
                                Полный текст для описания всех <?= TypeHelper::getName($textType, 1, true) ?> сайта
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
                            ?>
                        </div>

                        <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                            <?= ButtonHelper::submit()
                            ?>
                        </div>
                    </div>
                    
                    <?php
                } ?>

        </div>

    </div>

<?php
    
    echo $form->field($model, 'site_id')
              ->hiddenInput(['value' => $root->site_id])->label(false)
    ;
    echo $form->field($model->tags, 'existing')
              ->hiddenInput(['value' => ''])->label(false)
    ;
    echo $form->field($model->tags, 'textNew')
              ->hiddenInput(['value' => ''])->label(false)
    ;
    
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
