<?php
    
    use backend\helpers\StatusHelper;
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
    use core\edit\forms\SlugEditForm;
    use core\edit\useCases\Inflector;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $this yii\web\View */
    /* @var $model SlugEditForm */
    /* @var $parent Model|Product|Article|Chapter|Post|Thread|Razdel|Section|Book|Category|Page|Information|Group */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const TEMPLATES_UPDATE_SLUG_LAYOUT = '#layouts_templates_updateSlug';
    
    $status   = $parent->status;
    
    $title = match ($textType) {
        Constant::RAZDEL_TYPE   => 'Идентификатор раздела',
        Constant::PRODUCT_TYPE  => 'Идентификатор товара/услуги',
        Constant::BRAND_TYPE    => 'Идентификатор бренда',
        Constant::SECTION_TYPE  => 'Идентификатор рубрики',
        Constant::ARTICLE_TYPE  => 'Идентификатор статьи',
        Constant::BOOK_TYPE     => 'Идентификатор тома',
        Constant::CHAPTER_TYPE  => 'Идентификатор главы',
        Constant::CATEGORY_TYPE => 'Идентификатор блога',
        Constant::POST_TYPE     => 'Идентификатор поста',
        Constant::PAGE_TYPE     => 'Идентификатор страницы',
        Constant::GROUP_TYPE    => 'Идентификатор форума',
        Constant::THREAD_TYPE   => 'Идентификатор треда',
        Constant::NEWS_TYPE     => 'Идентификатор новости',
        Constant::MATERIAL_TYPE => 'Идентификатор материала',
        Constant::AUTHOR_TYPE   => 'Идентификатор автора',
        Constant::ANONS_TYPE    => 'Идентификатор анонса',
        Constant::GALLERY_TYPE  => 'Идентификатор галереи',
        Constant::TAG_TYPE      => 'Идентификатор метки',
        Constant::FAQ_TYPE      => 'Идентификатор комментария',
        Constant::FOOTNOTE_TYPE => 'Идентификатор примечания',
    };
    
    $this->title = $title;
    
    
    $url = TypeHelper::getLongEditUrl($textType);
    
    $this->params['breadcrumbs'][] = [
        'label' => TypeHelper::getName($textType, null, true, true),
        'url'   => [$url . 'index'],
    ];
    
    $this->params['breadcrumbs'][] = [
        'label' => $label,
        'url'   => [$url . 'view', 'id' => $parent->id],
    ];
    
    $this->params['breadcrumbs'][] = $this->title;
    
    $link = Url::to($parent->link, true);
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => TEMPLATES_UPDATE_SLUG_LAYOUT,
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
    <div class="card">
        <div class="card-header bg-body-secondary">
            <strong>
                Полный адрес:
            </strong>
            <?=
                Html::a(
                    $link,
                    $link,
                )
            ?></div>
        <div class='card-body'>

            <div class="row">
                <div class='col-md-5 col-sm-12'>
                    <div class='card'>
                        <div class='card-header bg-light d-flex justify-content-between'>
                            <strong>
                                Идентификатор
                            </strong>
                        </div>
                        <div class='card-body'>
                            <?= $form->field(
                                $model, 'slug',
                            )
                                     ->textInput()
                                ->label(false)
                            ?></div>
                        <div class="card-footer bg-light">
                            Разрешены только разнообразные латинские буквы, цифры и нижнее подчеркивание
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-sm-12">
                    <div class='card'>
                        <div class='card-header bg-light d-flex justify-content-between'>
                            <strong>
                                Справка
                            </strong>
                        </div>
                        <div class='card-body'>
                            <strong>Название модели:</strong> <br>
                            <?= $parent->name ?>
                            <hr>
                            <strong>Полное название модели:</strong> <br>
                            <?= $parent->title ?>
                        </div>
                        <div class='card-header bg-light d-flex justify-content-between'>
                            <strong>
                                Латинизация
                            </strong>
                        </div>
                        <div class='card-body'>
                            <strong>Название модели:</strong> <br>
                            <?= Inflector::slug($parent->name)
                            ?>
                            <hr>
                            <strong>Полное название модели:</strong> <br>
                            <?= Inflector::slug($parent->title)
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <div class='btn-group-sm d-grid gap-2 d-sm-block'>
                        <?php
                            if ($status < Constant::STATUS_NEW):
                                    echo
                                    StatusHelper::statusLabel
                                    (
                                        $status,
                                    );
                            endif; ?>
                        
                        
                        <?php
                            if ($status >= Constant::STATUS_NEW):
                                try {
                                    echo
                                    StatusHelper::marketStatusLabel
                                    (
                                        $status,
                                    );
                                }
                                catch (Exception $e) {
                                    PrintHelper::exception(
                                        'StatusHelper Market ', TEMPLATES_UPDATE_SLUG_LAYOUT, $e,
                                    );
                                }
                            endif; ?>
                        <?= ButtonHelper::collapse()
                        ?>
                        <br>
                        <?= ButtonHelper::submit()
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-warning">
            Внимание! При изменении идентификатора страница выбывает из поиска!
        </div>
    </div>

<?php
    ActiveForm::end();
