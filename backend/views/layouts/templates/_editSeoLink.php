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
    use core\edit\forms\ModelEditForm;
    use core\edit\useCases\Inflector;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model ModelEditForm */
    /* @var $parent Model|Product|Article|Chapter|Post|Thread|Razdel|Section|Book|Category|Page|Information|Group */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const TEMPLATES_EDIT_SEO_LAYOUT = '#layouts_templates_editSeoLink';
    
    $status = $parent->status;
    
    $preTitle = match ($textType) {
        Constant::RAZDEL_TYPE   => 'Ссылка для раздела',
        Constant::PRODUCT_TYPE  => 'Ссылка для товара/услуги',
        Constant::BRAND_TYPE    => 'Ссылка для бренда',
        Constant::SECTION_TYPE  => 'Ссылка для рубрики',
        Constant::ARTICLE_TYPE  => 'Ссылка для статьи',
        Constant::BOOK_TYPE     => 'Ссылка для тома',
        Constant::CHAPTER_TYPE  => 'Ссылка для главы',
        Constant::CATEGORY_TYPE => 'Ссылка для блога',
        Constant::POST_TYPE     => 'Ссылка для поста',
        Constant::PAGE_TYPE     => 'Ссылка для страницы',
        Constant::GROUP_TYPE    => 'Ссылка для форума',
        Constant::THREAD_TYPE   => 'Ссылка для треда',
        Constant::NEWS_TYPE     => 'Ссылка для новости',
        Constant::MATERIAL_TYPE => 'Ссылка для материала',
        Constant::AUTHOR_TYPE   => 'Ссылка для автора',
        Constant::ANONS_TYPE    => 'Ссылка для анонса',
        Constant::GALLERY_TYPE  => 'Ссылка для галереи',
        Constant::FAQ_TYPE      => 'Ссылка для комментария',
        Constant::FOOTNOTE_TYPE => 'Ссылка для примечания',
    };
    
    $this->title = $preTitle . ' ' . $parent->name;
    
    $url = TypeHelper::getLongEditUrl($textType);
    
    $this->params['breadcrumbs'][] = [
        'label' => $parent->name,
        'url'   => [$url . 'view', 'id' => $parent->id],
    ];
    
    $this->params['breadcrumbs'][] = $this->title;
    
    //PrintHelper::print($keywords );
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => TEMPLATES_EDIT_SEO_LAYOUT,
        ],
    );
    
    $form                           = ActiveForm::begin(
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
        <div class='card-header bg-light d-flex justify-content-between'>
            <div> <?= $preTitle ?>
                <strong>
                    <?= Html::encode($parent->name)
                    ?>
                </strong>
            </div>
            <?php
                if ($status < Constant::STATUS_NEW):
                    echo
                    StatusHelper::statusLabel
                    (
                        $status,
                    );
                
                endif; ?>
        </div>
        <div class='card-body'>

            <div class="row">

                <div class='col-md-6 col-sm-12'>

                    <div class="card">
                        <div class='card-header bg-light'>
                            <strong>
                                Текущая ссылка
                            </strong>
                        </div>
                        <div class='card-body'>
                            <?php
                                try {
                                    echo
                                    Html::a(
                                        $parent->getFullUrl(),
                                        $parent->getFullUrl(),
                                    );
                                }
                                catch (Exception $e) {
                                    PrintHelper::exception(
                                        'parent->getFullUrl', TEMPLATES_EDIT_SEO_LAYOUT, $e,
                                    );
                                }
                            ?>
                        </div>
                        <div class='card-header bg-body-tertiary'>
                            <strong>
                                Новая ссылка
                            </strong>
                        </div>
                        <div class='card-body'>
                            <div class='input-group p-2'>
                                <span class='input-group-text'>https://<?= $parent->site->name ?>/</span>
                                <?= $form->field($model, 'link', [
                                    'options'  => ['class' => 'mb-0'],
                                    'template' => '{input}',
                                ])->textInput([
                                    'maxlength' => true,
                                    'class'     => 'form-control',
                                ])->label(false)
                                ?>
                                <span class="input-group-text">/</span>
                            </div>
                            <small>
                                Разрешены только разнообразные латинские буквы, цифры и нижнее подчеркивание</small>
                        </div>
                        <div class="card-footer bg-light">
                            <div class='btn-group-sm d-grid gap-2 d-sm-block'>
                                <?= ButtonHelper::submit()
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class='card-header bg-light'>
                            <strong>
                                Справка
                            </strong>
                        </div>
                        <div class='card-body'>
                            <p>
                                Префикс всех моделей: <strong>
                                    <?= TypeHelper::getSlug($parent::TEXT_TYPE, $parent->site_id) ?>
                                </strong>
                            </p>
                            <p>
                                Ссылка модели в базе данных: <strong>
                                    <?= $parent->slug ?>
                                </strong>
                            </p>
                            <p>
                                Краткое название: <strong>
                                    <?= Html::encode($parent->name)
                                    ?>
                                </strong>
                            </p>
                            <p>
                                Латинизация краткого названия:
                                <?= Inflector::slug($parent->name)
                                ?>
                            </p>
                            <p>
                                Полное название
                                <strong>
                                    <?= Html::encode($parent->title)
                                    ?>
                                </strong>
                            </p>
                            <p>
                                Латинизация полного названия:
                                <?= Inflector::slug($parent->title)
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer bg-danger text-white ">
            Внимание! При изменении идентификатора страница выбывает из поиска!
        </div>

    </div>

<?php
    ActiveForm::end();
