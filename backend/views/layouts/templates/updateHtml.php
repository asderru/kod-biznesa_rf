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
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
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
    
    const TEMPLATES_UPDATE_HTML_LAYOUT = '#layouts_templates_updateHtml';
    
    $status = $parent->status;
    $editUrl = TypeHelper::getLongEditUrl($textType);
    
    $title = match ($textType) {
        Constant::RAZDEL_TYPE   => 'Правка текста раздела',
        Constant::PRODUCT_TYPE  => 'Правка текста товара/услуги',
        Constant::BRAND_TYPE    => 'Правка текста бренда',
        Constant::SECTION_TYPE  => 'Правка текста рубрики',
        Constant::ARTICLE_TYPE  => 'Правка текста статьи',
        Constant::BOOK_TYPE     => 'Правка текста тома',
        Constant::CHAPTER_TYPE  => 'Правка текста главы',
        Constant::CATEGORY_TYPE => 'Правка текста блога',
        Constant::POST_TYPE     => 'Правка текста поста',
        Constant::PAGE_TYPE     => 'Правка текста страницы',
        Constant::GROUP_TYPE    => 'Правка текста форума',
        Constant::THREAD_TYPE   => 'Правка текста треда',
        Constant::NEWS_TYPE     => 'Правка текста новости',
        Constant::MATERIAL_TYPE => 'Правка текста материала',
        Constant::AUTHOR_TYPE   => 'Правка текста автора',
        Constant::ANONS_TYPE    => 'Правка текста анонса',
        Constant::GALLERY_TYPE  => 'Правка текста галереи',
        Constant::TAG_TYPE      => 'Правка текста метки',
        Constant::DRAFT_TYPE    => 'Правка черновика',
        Constant::FAQ_TYPE      => 'Правка комментария',
        Constant::FOOTNOTE_TYPE => 'Правка примечания',
    };
    
    $hasVideo = match ($textType) {
        Constant::CHAPTER_TYPE,
        Constant::POST_TYPE,
        Constant::THREAD_TYPE => true,
        default               => false
    };
    
    $hasRating = match ($textType) {
        Constant::RAZDEL_TYPE,
        Constant::PRODUCT_TYPE,
        Constant::SECTION_TYPE,
        Constant::ARTICLE_TYPE,
        Constant::BOOK_TYPE,
        Constant::AUTHOR_TYPE,
        Constant::CATEGORY_TYPE,
        Constant::POST_TYPE,
        Constant::PAGE_TYPE,
        Constant::GROUP_TYPE,
        Constant::THREAD_TYPE,
                => true,
        default => false
    };
    
    $hasSlug = match ($textType) {
        Constant::DRAFT_TYPE,
        Constant::ANONS_TYPE,
                => false,
        default => true
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
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => TEMPLATES_UPDATE_HTML_LAYOUT,
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

    <div class='bg-secondary-subtle border border-dark border-2 rounded'>
        <strong>
            Правка кода HTML в тексте
        </strong>
        <?= $form->field(
            $model, 'text',
            [
                'inputOptions' => [
                    'id'    => 'text-html-area',
                    'style' => 'font-family: monospace; font-size: 1.3em; line-height: 2em; background-color: #f0f0f0;',
                ],
            ],
        )
                 ->textarea(
                     [
                         'rows' => 30,
                     ],
                 )
            ->label(false)
        ?>
    </div>
    <div class='card'>
        <div class='card-header bg-light d-flex justify-content-between'>
            <div>
                Краткое название: <strong>
                    <?= Html::encode($parent->name)
                    ?>
                </strong>
                <hr>
                Полное название
                <strong>
                    <?= Html::encode($parent->title)
                    ?>
                </strong>
            </div>

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
                                'StatusHelper::marketStatusLabel ', TEMPLATES_UPDATE_HTML_LAYOUT, $e,
                            );
                        }
                    endif; ?>
                <button
                        onclick='triggerCopy()'
                        id='copy-button'
                        class='btn btn-sm btn-outline-dark'
                >Скопировать текст в память браузера
                </button>
                <?= ButtonHelper::collapse('Редактировать название и описание')
                ?>
                <br>
                <?= ButtonHelper::submitAction('view', 'Сохранить') ?>
                <?= ButtonHelper::submitAction('update', 'Сохранить и перейти на след.') ?>
                <?= FaviconHelper::index($textType) ?>
                <?= FaviconHelper::create($textType) ?>
                <?= FaviconHelper::view($textType, $parent->id) ?>
                <?= FaviconHelper::update($textType, $parent->id) ?>
                <?= FaviconHelper::updateHtml($textType, $parent->id) ?>
                <?= FaviconHelper::updateUpdate($textType, $parent->id) ?>
                <?= FaviconHelper::updateView($textType, $parent->id) ?>
            </div>

        </div>

        <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>

            <div class="row">

                <div class='col-xl-6'>

                    <div class='card h-100'>

                        <div class='card-body bg-light'>
                            <?= $form->field($model, 'name')
                                     ->textInput(
                                         [
                                             'maxlength' => true,
                                         ],
                                     )
                                ->label()
                            ?>
                            
                            <?= $form->field($model, 'title')
                                     ->textInput(
                                         [
                                             'maxlength' => true,
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
                    </div>

                </div>

                <div class='col-xl-6'>
                    <div class='card h-100'>
                        <div class='p-2 text-end'>
                            <?= ButtonHelper::submit()
                            ?>
                            <?php
                                try {
                                    echo ButtonHelper::update
                                    (
                                        $parent->id,
                                        'Стандартный редактор',
                                    );
                                }
                                catch (Exception $e) {
                                    PrintHelper::exception(
                                        'ButtonHelper::update ', TEMPLATES_UPDATE_HTML_LAYOUT, $e,
                                    );
                                }
                            ?>
                            <?php
                                try {
                                    echo ButtonHelper::view
                                    (
                                        $parent->id,
                                    );
                                }
                                catch (Exception $e) {
                                    PrintHelper::exception(
                                        'ButtonHelper::view ', TEMPLATES_UPDATE_HTML_LAYOUT, $e,
                                    );
                                }
                            ?>
                        </div>
                        <div class='card-header bg-light'>
                            <strong>
                                Краткое описание
                            </strong>
                        </div>

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
                                             'rows' => 9,
                                         ],
                                     )
                                ->label(false)
                            ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
<?php
    ActiveForm::end();
    
    $js                                = <<<EOD
$(document).ready(function() {
   $('#copy-button').click(function() {
        var textToCopy = $('#text-html-area').text();
        var tempTextarea = $('<textarea>');
        $('body').append(tempTextarea);
        tempTextarea.val(textToCopy).select();
        document.execCommand('copy');
        tempTextarea.remove();
        alert('Текст скопирован!');
      });
});
EOD;
    $this->registerJs($js);
