<?php
    
    use backend\helpers\StatusHelper;
    use backend\tools\TinyHelper;
    use backend\widgets\KeywordWidget;
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
    const LAYOUT_ID = '#layouts_templates_keywords';
    echo PrintHelper::layout(LAYOUT_ID);
    
    $status = $parent->status;
    
    $title = match ($textType) {
        Constant::RAZDEL_TYPE   => 'Ключевые слова раздела',
        Constant::PRODUCT_TYPE  => 'Ключевые слова товара/услуги',
        Constant::BRAND_TYPE    => 'Ключевые слова бренда',
        Constant::SECTION_TYPE  => 'Ключевые слова рубрики',
        Constant::ARTICLE_TYPE  => 'Ключевые слова статьи',
        Constant::BOOK_TYPE     => 'Ключевые слова тома',
        Constant::CHAPTER_TYPE  => 'Ключевые слова главы',
        Constant::CATEGORY_TYPE => 'Ключевые слова блога',
        Constant::POST_TYPE     => 'Ключевые слова поста',
        Constant::PAGE_TYPE     => 'Ключевые слова страницы',
        Constant::GROUP_TYPE    => 'Ключевые слова форума',
        Constant::THREAD_TYPE   => 'Ключевые слова треда',
        Constant::NEWS_TYPE     => 'Ключевые слова новости',
        Constant::MATERIAL_TYPE => 'Ключевые слова материала',
        Constant::AUTHOR_TYPE   => 'Ключевые слова автора',
        Constant::ANONS_TYPE    => 'Ключевые слова анонса',
        Constant::GALLERY_TYPE  => 'Ключевые слова галереи',
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
        Constant::THREAD_TYPE
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
    
    $keywords = $parent->getKeywordsArray();
    
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
    <div class='card-header bg-light-subtle d-flex justify-content-between'>
        <div>
            Краткое название: <strong>
                <?= Html::encode($parent->name)
                ?>
            </strong>
            <br>
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
                            $actionId, 'StatusHelper::marketStatusLabel ' . LAYOUT_ID, $e,
                        );
                    }
                endif; ?>
            
            
            <?= ButtonHelper::view($parent->id)
            ?>
            <?= ButtonHelper::update($parent->id)
            ?>
            
            <?= ButtonHelper::collapse()
            ?>
            <br>
            <?= ButtonHelper::submitAction('view', 'Сохранить')
            ?>
            <?= ButtonHelper::submitAction('update', 'Сохранить и перейти на след.')
            ?>

        </div>

    </div>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>

        <div class="row">

            <div class='col-xl-6'>

                <div class='card h-100'>

                    <div class='card-body'>
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
                        
                        <?= ($hasVideo) ?
                            $form->field($model, 'video')
                                 ->textInput(
                                     [
                                         'maxlength' => true,
                                     ],
                                 )
                                 ->label() : null ?>
                        
                        <?= ($hasRating) ?
                            $form->field($model, 'rating')->textInput(
                                [
                                    'type' => 'number',
                                    'min'  => 1,
                                    'max'  => 100,
                                ],
                            ) : null ?>
                        
                        <?= Html::activeHiddenInput(
                            $model, 'site_id',
                            [
                                'value' => $parent->site_id,
                            ],
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

                    <div class='card-body'>
                        
                        
                        <?php
                            try {
                                echo
                                KeywordWidget::widget(
                                    [
                                        'model' => $model,
                                        'title' => 'Ключевые слова',
                                    ],
                                );
                            }
                            catch (Exception|Throwable $e) {
                                PrintHelper::exception(
                                    $actionId, 'KeywordWidget ' . LAYOUT_ID, $e,
                                );
                            } ?>
                    </div>

                </div>

            </div>
        </div>

    </div>
    <div class='card-header bg-light-subtle border-top border-bottom'>
        Ключевое слово (главное): <strong>
            <?= Html::encode($parent->getMainKeyword())
            ?>
        </strong>
        <br>
        Остальные ключевые слова:
        <?= implode(', ', array_values($keywords))
        ?>
    </div>

    <div class='card-body'>
        <div class="row">
            <div class='col-lg-6'>
                <div class='card-header bg-light'>
                    <strong>
                        Правка
                    </strong>
                </div>
                
                <?= $form->field(
                    $model, 'text',
                    [
                        'inputOptions' => [
                            'id' => 'text-edit-area',
                        ],
                    ],
                )
                         ->textarea(
                             [
                                 'rows' => 25,
                             ],
                         )
                         ->label(false)
                ?>
            </div>

            <div class='col-lg-6'>
                <div>
                    <strong>
                        Неиспользуемые ключевые слова
                    </strong>
                    <div id='unused-keywords'>
                    </div>
                </div>
                <div>
                    <strong>
                        Используемые ключевые слова
                    </strong>
                    <div id='used-keywords'>
                    </div>
                </div>
                <?=
                    $this->render(
                        '/layouts/templates/_textWidget',
                        [
                            'model' => $parent,
                        ],
                    )
                ?>
            </div>
        </div>

        <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::submit()
            ?>
        </div>
    </div>
    
    <?php
        ActiveForm::end();
        TinyHelper::getText();
        TinyHelper::getDescription();
    ?>

    <script>
        function highlightKeywords(text, keywords) {
            const notFoundKeywords = [];
            const usedKeywords = [];

            keywords.forEach(function (keyword) {
                const regex = new RegExp(keyword, 'gi');
                const matches = text.match(regex);

                if (matches) {
                    const count = matches.length;
                    usedKeywords.push(`<li>${keyword} - ${count}</li>`);
                    text = text.replace(regex, function (match) {
                        return "<span style='background-color: yellow;'>" + match + '</span>';
                    });
                } else {
                    notFoundKeywords.push(`<li>${keyword}</li>`);
                }
            });

            const highlightedText = text.replace(/<span style='background-color: yellow;'>(.*?)<\/span>/g, function (match, p1) {
                return "<span style='background-color: green; color: white;'>" + p1 + '</span>';
            });

            if (notFoundKeywords.length > 0) {
                const unusedKeywordsHtml = '<ul>' + notFoundKeywords.join('') + '</ul>';
                document.getElementById('unused-keywords').innerHTML = unusedKeywordsHtml;
            }

            if (usedKeywords.length > 0) {
                const usedKeywordsHtml = '<ul>' + usedKeywords.join('') + '</ul>';
                document.getElementById('used-keywords').innerHTML = usedKeywordsHtml;
            }

            return highlightedText;
        }

        const textElement = document.getElementById('card-body-text');
        const encodedText = textElement.innerHTML;
        const decodedText = htmlDecode(encodedText);

        const keywords = <?= json_encode($keywords)
            ?>;

        const highlightedText = highlightKeywords(decodedText, keywords);

        textElement.innerHTML = highlightedText.replace(/<span style='background-color: yellow;'>/g, '<span style="background-color: green; color: white;">').replace(/<\/span>/g, '</span>');

        function htmlDecode(input) {
            var doc = new DOMParser().parseFromString(input, "text/html");
            return doc.documentElement.textContent;
        }
    </script>
