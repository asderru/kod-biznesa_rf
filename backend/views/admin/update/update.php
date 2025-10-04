<?php
    
    use backend\helpers\StatusHelper;
    use backend\helpers\UrlHelper;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Note;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Content\Review;
    use core\edit\entities\Content\Tag;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Seo\Footnote;
    use core\edit\entities\Seo\Material;
    use core\edit\entities\Seo\News;
    use core\edit\entities\Shop\Brand;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\edit\entities\Utils\Gallery;
    use core\edit\forms\ModelEditForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $this yii\web\View */
    /* @var $parent Article|Author|Book|Brand|Category|Chapter|Group|Material|News|Page|Post|Product|Razdel|Section| */
    /* @var $prevModel Article|Author|Book|Brand|Category|Chapter|Group|Material|News|Page|Post|Product|Razdel|Section| */
    /* @var $siblings array */
    /* @var $model ModelEditForm */
    /* @var $footnotes Footnote[] */
    /* @var $faqs Faq[] */
    /* @var $reviews Review[] */
    /* @var $galleries Gallery[] */
    /* @var $notes Note[] */
    /* @var $tags Tag[] */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $editorName string */
    /* @var $textType int */
    /* @var $parentId int */
    /* @var $editId int */
    
    const LAYOUT_ID = '#admin_update_update_';
    echo PrintHelper::layout(LAYOUT_ID);
    
    $status  = $parent->status;
    $editUrl = $parent->getEditPath();
    $count   = strlen($parent->text);
    
    $this->title = $parent->name . '. Ред. ' . $editorName;
    
    $parentIndexName = 'Index';
    $parentViewUrl   = TypeHelper::getView($textType, $parent->id);
    $parentIndex     = TypeHelper::getView($textType);
    $parentIndexName = TypeHelper::getLabel($parent::TEXT_TYPE);
    
    $siblingLink = match ($textType) {
        Constant::RAZDEL_TYPE   => 'razdel-link',
        Constant::PRODUCT_TYPE  => 'product-link',
        Constant::BOOK_TYPE     => 'book-link',
        Constant::CHAPTER_TYPE  => 'chapter-link',
        Constant::CATEGORY_TYPE => 'category-link',
        Constant::POST_TYPE     => 'post-link',
        Constant::PAGE_TYPE     => 'page-link',
        Constant::NEWS_TYPE     => 'news-link',
        Constant::FAQ_TYPE      => 'faq-link',
        Constant::FOOTNOTE_TYPE => 'footnote-link',
    };
    
    $this->params['breadcrumbs'][] = [
        'label' => $parentIndexName,
        'url'   => [$parentIndex],
    ];
    
    $this->params['breadcrumbs'][] = [
        'label' => $parent->name,
        'url'   => [
            $parentViewUrl,
        ],
    ];
    $this->params['breadcrumbs'][] = 'ред. ' . $editorName;

?>

    <div class='row'>

    <div class='col-lg-9 overflow-hidden'>
        <div class="card">
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
                );
            ?>

            <div class='card-header bg-secondary-subtle d-flex justify-content-between'>
                <div>
                    категория: <strong><?= $parentIndexName ?></strong><br>
                    название: <strong><?= ButtonHelper::updateType($parent::TEXT_TYPE, $parent->id); ?></strong><br>
                    заголовок: <strong><?= $parent->title ?></strong><br>
                </div>
                <div class="text-center">
                    редактор: <strong><?= $editorName ?></strong><br>

                </div>
                <div class='btn-group-sm d-grid gap-2 d-sm-block'>
                    
                    <?php
                        echo ButtonHelper::submitAction('return', 'Сохранить', 'btn btn-sm btn-primary');
                        
                        echo ButtonHelper::submitAction('next', 'Сохранить и перейти на след.', 'btn btn-sm btn-success');
                        
                        echo ButtonHelper::collapse()
                    ?>

                    <br>
                    
                    <?= FaviconHelper::home() ?>
                    <?= FaviconHelper::view($textType, $parent->id) ?>
                    <?= FaviconHelper::update($textType, $parent->id) ?>
                    <?= FaviconHelper::updateHtml($textType, $parent->id) ?>
                    <?= FaviconHelper::updateUpdate($textType, $parent->id) ?>
                    <?= FaviconHelper::updateView($textType, $parent->id) ?>

                </div>
            </div>

            <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
                <?= ButtonHelper::indexPanel(); ?>
                <?= ButtonHelper::viewType($textType); ?>
                <?= ButtonHelper::editPanelsSet($parent, $editId, 3); ?>

            </div>

            <div class="card-body">
                <?=
                    $this->render(
                        'widgets/update' . $editId,
                        [
                            'form'     => $form,
                            'model'    => $model,
                            'actionId' => $actionId,
                        ],
                    );
                
                ?>
            </div>

            <div class='card-footer bg-light d-flex justify-content-between'>
                <div>
                    <?= ButtonHelper::submitAction('return', 'Сохранить')
                    ?>
                    <?= ButtonHelper::submitAction('next', 'Сохранить и перейти на след.')
                    ?>
                </div>

                <div class='btn-group-sm d-grid gap-2 d-sm-block'>
                    
                    <?php
                        if ($status < Constant::STATUS_NEW) { ?>
                            <?= StatusHelper::statusLabel($status);
                        } ?>
                    
                    
                    <?php
                        if ($status >= Constant::STATUS_NEW) { ?>
                            <?= StatusHelper::marketStatusLabel($status);
                        } ?>
                    <button
                            onclick='triggerCopy()'
                            id='copy-button'
                            class='btn btn-sm btn-outline-dark'
                    >Скопировать текст в память браузера
                    </button>
                </div>

            </div>

            <div class='card-body mb-2 btn-group-sm gap-2'>

                <div class="row">

                    <div class='col-xl-6'>

                        <div class='card h-100'>

                            <div class='card-body bg-light'>
                                
                                <?= $form->field($model, 'name')
                                         ->textInput(
                                             [
                                                 'maxlength' => true,
                                                 'required'  => true,
                                             ],
                                         )
                                         ->label()
                                ?>
                                
                                <?= $form->field($model, 'title')
                                         ->textarea(
                                             [
                                                 'rows' => 3,
                                             ],
                                         )
                                         ->label()
                                ?>
                                
                                <?= Html::activeHiddenInput(
                                    $model, 'site_id',
                                    [
                                        'value' => $parent->site_id,
                                    ],
                                )
                                ?>

                            </div>

                            <div class="card-footer">
                                <small> Модель: </small> <?= Html::tag(
                                    'strong',
                                    Html::encode($parent->name),
                                )
                                ?>.

                                <br>

                                <small> Сайт: </small> <?= Html::tag(
                                    'strong',
                                    Html::encode($parent->site->name),
                                )
                                ?>.

                                <hr>

                                <small>
                                    ID #<strong><?= Html::encode($parent->id)
                                        ?></strong>
                                    / Контент - <strong><?= Html::encode($parent->content_id)
                                        ?>
                                    </strong>.
                                </small>
                                <br>
                                Ссылка - <?php
                                    $parentLink = Url::to(Yii::$app->params['frontendHostInfo'] . $parent->link, true);
                                    
                                    echo Html::a(
                                        $parentLink,
                                        $parentLink,
                                        [
                                            'target' => '_blank',
                                        ],
                                    )
                                ?>.
                                <br>
                                <?=
                                    UrlHelper::checkUrl($parent->status, $parentLink)
                                ?>
                            </div>

                        </div>

                    </div>

                    <div class='col-xl-6'>
                        <div class='card h-100'>
                            <div class='card-body bg-light'>
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
                                                 'rows' => 8,
                                             ],
                                         
                                         )
                                         ->label(
                                             'Краткое описание',
                                         )
                                ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class='card-footer text-end'>
                <div>
                    <?= ButtonHelper::submitAction('return', 'Сохранить')
                    ?>
                    <?= ButtonHelper::submitAction('next', 'Сохранить и перейти на след.')
                    ?>
                </div>
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
                         ->checkboxList($model->tags::tagsList($parent->site_id))
                         ->label('Отметить:') ?>
            </div>
            
            <?php
                ActiveForm::end();
            ?>

        </div>
    </div>

    <div class='col-lg-3'>
        <div class='card' style='height: 150vh;'>
            <div class="card-header text-center bg-secondary">
                <h4 class="card-title text-white">
                    <?= TypeHelper::getName($textType, null, false, true)
                    ?>
                </h4>
            </div>
            <div class='card-body  overflow-auto'>
                <?php
                    foreach ($siblings as $sibling) { ?>
                        <?php
                        if ($sibling->id === $parent->id): echo '<div id="selected"></div>'; endif ?>

                        <a href='#' class='<?= $siblingLink ?>'
                           data-bs-toggle='modal'
                           data-bs-target='#faqModal'
                           data-faq-id="<?= $sibling->id ?>">
                            <strong>#<?= $sibling->id . '. ' . Html::encode($sibling->title) ?></strong>
                        </a>
                        <?= FormatHelper::asHtml($sibling->description) ?>
                        
                        <?= FaviconHelper::view($textType, $sibling->id) ?>
                        <?= FaviconHelper::update($textType, $sibling->id) ?>
                        <?= FaviconHelper::updateHtml($textType, $sibling->id) ?>
                        <?= FaviconHelper::updateUpdate($textType, $sibling->id) ?>
                        <?= FaviconHelper::updateView($textType, $sibling->id) ?>
                        
                        <?php
                        if ($sibling->faqs) {
                            echo '<p class="small">Комментарий: <br>';
                            foreach ($sibling->faqs as $faq) { ?>
                                <a href="#" class="faq-link"
                                   data-bs-toggle="modal"
                                   data-bs-target="#faqModal"
                                   data-faq-id="<?= $faq->id ?>">
                                    <strong><?= $faq->title ?></strong>
                                </a> <br>
                                <?php
                            }
                            echo '</p>';
                        }
                        else {
                            echo ButtonHelper::faqCreate($textType, $parent->id, 'Добавить комментарий');
                        }
                        ?>
                        
                        <?php
                        if ($sibling->footnotes) {
                            echo '<p class="small">Примечания: <br>';
                            foreach ($sibling->footnotes as $footnote) { ?>
                                <a href="#" class="footnote-link"
                                   data-bs-toggle="modal"
                                   data-bs-target="#faqModal"
                                   data-faq-id="<?= $footnote->id ?>">
                                    <strong><?= $footnote->title ?></strong>
                                </a> <br>
                                <?php
                            }
                            echo '</p>';
                        }
                        echo ButtonHelper::footnoteCreate($textType, $parent->id, 'Добавить примечание');
                        ?>
                        
                        <?php
                        if ($sibling->tags) {
                            echo '<p class="small">Метки: <br>';
                            foreach ($sibling->tags as $tag) { ?>
                                <strong><?= $tag->name ?></strong>,
                                
                                <?php
                            }
                            echo '</p>';
                        }
                        ?>
                        <hr>
                        <?php
                    } ?>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const urlParams = new URLSearchParams(window.location.search);
                        const parentId = urlParams.get('id');

                        if (parentId) {
                            setTimeout(function () {
                                const hiddenElement = document.getElementById('selected');

                                if (hiddenElement) {
                                    hiddenElement.scrollIntoView({
                                        block: 'center',
                                        behavior: 'smooth'
                                    });
                                }
                            }, 500); // 500 миллисекунд = 0.5 секунды
                        }
                    });
                </script>
            </div>

        </div>
    </div>

    <!-- FAQ Modal -->
    <div class='modal modal-xl fade' id='faqModal' tabindex='-1' aria-labelledby='faqModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-name' id='faqModalLabel'></h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <div class='card mb-3'
                    '>
                    <div class='row g-0'>
                        <div class='col-md-6'>
                            <img src='' class='img-fluid rounded-start' alt=''>
                        </div>
                        <div class='col-md-6'>
                            <div class='card-body'>
                                <h4 class='modal-title'></h4>
                                <p class='card-text modal-status'></p>
                                <div class='card-text modal-description'></div>
                            </div>
                            <div class="card-footer modal-editUrl">
                                <a href="" class="btn btn-sm btn-outline-secondary" target="_blank">Открыть в другом
                                    окне</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body modal-text"></div>


            </div>
        </div>
    </div>
<?php
    $getFaqUrl      = Url::to(['/seo/faq/get-model']);
    $getFootnoteUrl = Url::to(['/seo/footnote/get-model']);
    $getRazdelUrl   = Url::to(['/shop/razdel/get-model']);
    $getProductUrl  = Url::to(['/shop/product/get-model']);
    $getBookUrl     = Url::to(['/library/book/get-model']);
    $getChapterUrl  = Url::to(['/library/chapter/get-model']);
    $getCategoryUrl = Url::to(['/blog/category/get-model']);
    $getPostUrl     = Url::to(['/blog/post/get-model']);
    $getPageUrl     = Url::to(['/content/page/get-model']);
    $getNewsUrl     = Url::to(['/seo/news/get-model']);
    
    $js             = <<<JS
$(document).ready(function() {
    const urls = {
        'faq-link': '{$getFaqUrl}',
        'footnote-link': '{$getFootnoteUrl}',
        'razdel-link': '{$getRazdelUrl}',
        'product-link': '{$getProductUrl}',
        'book-link': '{$getBookUrl}',
        'chapter-link': '{$getChapterUrl}',
        'category-link': '{$getCategoryUrl}',
        'post-link': '{$getPostUrl}',
        'page-link': '{$getPageUrl}',
        'news-link': '{$getNewsUrl}'
    };

    $('[class$="-link"]').on('click', function(e) {
        e.preventDefault();
        
        const linkClass = $(this).attr('class');
        const faqId = $(this).data('faq-id');

        $('#faqModal').modal('show');
        
        $.ajax({
            url: urls[linkClass],
            method: 'GET',
            data: { id: faqId },
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    $('#faqModalLabel').text('Ошибка');
                    $('.modal-body').html(`<div class="alert alert-danger">Не удалось загрузить информацию</div>`);
                } else {
                    $('#faqModalLabel').text(data.name);
                    $('.modal-title').text(data.title);
                    $('.modal-status').html(data.status);
                    $('.modal-description').html(data.description);
                    $('.modal-text').html(data.text);
                    $('.modal-body img').attr('src', data.image).attr('alt', data.title);
                    $('.modal-editUrl a').attr('href', data.editUrl);
                }
            },
            error: function() {
                $('#faqModalLabel').text('Ошибка');
                $('.modal-body').html('<div class="alert alert-danger">Не удалось загрузить информацию</div>');
            }
        });
    });
});

JS;
    
    $this->registerJs($js, \yii\web\View::POS_READY);
