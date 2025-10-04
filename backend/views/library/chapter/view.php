<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\helpers\LinkHelper;
    use backend\helpers\StatusHelper;
    use backend\widgets\PagerWidget;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\ReadHelper;
    use core\tools\Constant;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;
    use yii\web\JqueryAsset;
    use yii\web\View;
    
    /**
     * @var View            $this
     * @var Chapter         $model
     * @var Book            $book
     * @var UploadPhotoForm $uploadForm
     * @var array           $editItems
     * @var string          $actionId
     * @var string          $label
     * @var string          $prefix
     * @var int             $textType
     */

// Константы и основные переменные
    const LAYOUT_ID                = '#library_chapter_view';
    
    $modelSeoLink = Url::to($model['link'], true);

// Формирование заголовка
    $this->title = $model->isRoot() ? $label : $model->name;
    
    $buttons = [
        ButtonHelper::import($textType),
        ButtonHelper::export($textType),
    ];
// Формирование массива кнопок
    $buttons = [
        ButtonHelper::activation($model),
        ButtonHelper::viewType(Constant::BOOK_TYPE, $model->book_id, 'Том ' . $book->name),
        ButtonHelper::update($model->id),
        ButtonHelper::expressType($textType, $model->id),
        ButtonHelper::updateHtml($model),
        ButtonHelper::keywords($model),
        ButtonHelper::content($model->id),
        ButtonHelper::contentView($model->content_id),
        ButtonHelper::contentUpdate($model->content_id),
        ButtonHelper::create('Написать главу'),
        ButtonHelper::createType($textType, $book->id, 'Добавить в том ' . $book->name),
        ButtonHelper::copy($model->id),
        ButtonHelper::copyToDraft($model->id),
        ButtonHelper::resort(
            $model->book_id,
            "Сортировать главы в {$model->book->name}",
        ),
        LinkHelper::checkUrlLink($model),
        LinkHelper::checkStatus($model),
        LinkHelper::checkModelLink($model),
        ButtonHelper::onSite($model),
        ButtonHelper::bing($model->id),
        ButtonHelper::clearCache($model->site_id, $textType, $model->id),
        ButtonHelper::delete($model),
    ];
    
    $buttons[] = ButtonHelper::relationSet($model::TEXT_TYPE, $model->id);
    $buttons[] = ButtonHelper::editPanelsSet($model);

// Хлебные крошки
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::BOOK_TYPE);
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::CHAPTER_TYPE);
    $this->params['breadcrumbs']   = [
        BreadCrumbHelper::typeView(Constant::BOOK_TYPE, $book->id, $book->name),
    ];
    $this->params['breadcrumbs']   = [
        BreadCrumbHelper::typeView(Constant::CHAPTER_TYPE, $book->id, "Тексты в $book->name"),
        $this->title,
    ];


// Рендеринг компонентов
    echo $this->render(
        '/layouts/tops/_infoHeader', [
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
                'model'  => $model,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget::widget', LAYOUT_ID, $e,
        );
    }
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_viewHeaderModel',
        [
            'model'    => $model,
            'textType' => $textType,
            'buttons' => $buttons,
        ],
    )
?>

    <div class="card-body">

        <div class='row mb-3'>

            <div class='col-xl-6'>

                <div class='card'>

                    <div class='card-header bg-light'>
                        <strong>
                            Информация
                        </strong>
                    </div>

                    <div class='card-body'>
                        <div class='table-responsive'>
                            <table class='table table-sm table-striped table-bordered'>
                                <tbody>
                                <tr>
                                    <th scope='row'>id (сортировка)</th>
                                    <td><?= $model->id ?> (<?= $model->sort ?>)</td>
                                </tr>
                                <tr>
                                    <th scope='row'>Сайт</th>
                                    <td><?= ParametrHelper::getSiteName($model->site_id)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Том</th>
                                    <td><?= Html::a(
                                            Html::encode($book->name),
                                            [
                                                'library/book/view',
                                                'id' => $book->id,
                                            ],
                                        )
                                        ?>. <?= StatusHelper::statusBadgeLabel($book->status) ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Краткое название</th>
                                    <td><?= Html::encode($model->name)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Полное название</th>
                                    <td><?= Html::encode($model->title)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Идентификатор ссылки (англ.)</th>
                                    <td><?= Html::encode($model->slug)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'><strong>Метки</strong>:</th>
                                    <td>
                                        
                                        <?= implode(', ', ArrayHelper::getColumn($model->tags, 'name'))
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope='row'>Ключевое слово (главное)</th>
                                    <td>
                                        <?= Html::encode($model->mainKeyword) ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class='d-flex justify-content-between'>
                            <div><small>Время чтения (мин.):</small>
                                <strong><?= ReadHelper::getReadingTime($model->text)
                                    ?></strong>
                            </div>
                            <div><small>Слов:</small> <strong><?= ReadHelper::getWordCount($model->text)
                                    ?></strong></div>
                            <div><small>Знаков:</small> <strong><?= ReadHelper::getCharactersCount($model->text)
                                    ?></strong></div>
                        </div>
                    </div>


                    <div class='card-footer d-flex justify-content-between'>
        <span>
            <a class='btn btn-sm btn-outline-dark' data-bs-toggle='collapse'
               href='#collapseTechInfo'
               role='button'
               aria-expanded='false' aria-controls='collapseTechInfo'>
                Техническая информация
            </a>
        </span>
                        
                        
                        <?php
                            if ($model->getEditItems()->exists()): ?>
                                <span> <a class='btn btn-sm btn-outline-primary' data-bs-toggle='collapse'
                                          href='#collapseEditInfo'
                                          role='button'
                                          aria-expanded='false' aria-controls='collapseEditInfo'>
                        Редакторская информация
                    </a></span>
                            <?php
                            else: ?>
                                <span class="text-primary strong">Правка модели не осуществлялась</span>
                            <?php
                            endif ?>
                    </div>

                    <div class='collapse p-2' id='collapseTechInfo'>
                        <div class='card border-secondary'>
                            <div class='card-body'>
                                <div class='border-bottom p-1'>
                                    <strong>Другие ключевые слова</strong>:
                                    <?= Html::encode($model->otherKeywords) ?>
                                </div>
                                <div class='border-bottom p-1'>
                                    <strong>Метки</strong>:
                                    <?= implode(', ', ArrayHelper::getColumn($model->tags, 'name'))
                                    ?>
                                </div>
                                <?php
                                    if (isset($model->rating)) { ?>
                                        <div class='border-bottom p-1'>
                                            <strong>Рейтинг</strong>:
                                            <?= $model->rating ?>
                                        </div>
                                        <?php
                                    } ?>
                                <?php
                                    if (isset($model->photo)) { ?>
                                        <div class='border-bottom p-1'>
                                            <strong>Главное фото</strong>:
                                            #<?= $model->photo ?>
                                        </div>
                                        <?php
                                    } ?>
                                <div class='border-bottom p-1'>
                                    <strong>Время создания</strong>:
                                    <?= FormatHelper::asDateTime($model->created_at)
                                    ?>
                                </div>
                                <div class='border-bottom p-1'>
                                    <strong>Время обновления</strong>:
                                    <?= FormatHelper::asDateTime($model->updated_at)
                                    ?>
                                </div>
                                <div class='border-bottom p-1'>
                                    <strong>Контент</strong>:
                                    <?= $model->content_id ?>
                                </div>
                                <div class='border-bottom p-1'>
                                    <strong>Панель</strong>:
                                    <?= $model->panel_id ?>
                                </div>
                            </div>

                            <div class='card-footer'>
                                <?php
                                    echo
                                    LinkHelper::checkUrlLink(
                                        $model,
                                    );
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        if ($model->getEditItems()->exists()): ?>
                            <div class='collapse p-2' id='collapseEditInfo'>
                                <div class='card border-secondary'>
                                    <div class="card-header bg-body-secondary">Недавние правки
                                        <small>(всего: <?= $model->getEditCount()
                                            ?>)</small>
                                    </div>
                                    <div class='card-body table-responsive'>
                                        <table class='table table-striped'>
                                            <thead class='table-light'>
                                            <td>Дата</td>
                                            <td>Время</td>
                                            <td>Слов</td>
                                            <td>+ / -</td>
                                            </thead>
                                            <tbody>
                                            <?php
                                                foreach ($editItems as $editItem): ?>
                                                    <tr>
                                                        <td><?= $editItem->createdTime ?></td>
                                                        <td><?= $editItem->editTime ?></td>
                                                        <td><?= $editItem->words ?></td>
                                                        <?php
                                                            $editWords  = $editItem->getEditWords($editItem->id);
                                                            $colorClass = $editWords >= 0 ? 'text-success' : 'text-danger';
                                                        ?>
                                                        <td class="<?= $colorClass ?>">
                                                            <?= $editWords ?>
                                                            <?php
                                                                if ($editWords > 0): ?>
                                                                    <i class="bi bi-arrow-up-short"></i>
                                                                <?php
                                                                elseif ($editWords < 0): ?>
                                                                    <i class="bi bi-arrow-down-short"></i>
                                                                <?php
                                                                endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class='card-footer'>
                                        <?= ButtonHelper::modelEdits()
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endif ?>
                </div>

                <div class='card border border-warning'>
                    <?php
                        if (!$model->hasProperUrl()): ?>

                            <div class='card-header bg-danger text-white'>
                                <strong>
                                    Cсылка не совпадает с идентфикатором!
                                </strong>
                            </div>
                        
                        <?php
                        else: ?>

                            <div class='card-header bg-body-secondary'>
                                <strong>
                                    Работа со ссылкой
                                </strong>
                            </div>
                        <?php
                        endif; ?>

                    <div class='card-body'>
                        Полная ссылка главы: <?= Html::a(
                            $model->getFullLink(), $model->getFullLink(),
                            [
                                'target' => '_blank',
                            ],
                        )
                        ?>
                        <hr>
                        <?= ButtonHelper::editSlug($model->id)
                        ?> - <strong><?= $model->slug ?></strong>
                        <hr>
                        <?= ButtonHelper::editSeolink($model->id)
                        ?> - <strong><?= $model->link ?></strong>
                    </div>
                    <div class='card-footer bg-warning-subtle'>
                        Для опытных пользователей! При любом изменении ссылки страница выпадает из поиска!
                    </div>
                </div>

                <div class="card">
                    <div class='card-header bg-light'>
                        <strong>
                            Описание (тег Descriptiion)
                        </strong>
                    </div>

                    <div class='card-body'>
                        <?= FormatHelper::asHtml($model->description)
                        ?>
                    </div>
                </div>

            </div>


            <div class='col-xl-6'>
                
                <?= $this->render(
                    '/layouts/images/_images',
                    [
                        'model'      => $model,
                        'uploadForm' => $uploadForm,
                    ],
                )
                ?>
                <?= $this->render(
                    '/layouts/images/_galleries',
                    [
                        'model' => $model,
                    ],
                )
                ?>
            </div>
        </div>
        
        <?= $this->render(
            '/layouts/templates/_viewWidgets',
            [
                'model' => $model,
            ],
        )
        ?>
        
        <?= $this->render(
            '/layouts/templates/_textWidget',
            [
                'model' => $model,
            ],
        )
        ?>
        
        <?= $this->render(
            '/layouts/widgets/seoWidget',
            [
                'model' => $model,
            ],
        );
        
        ?>
    </div>

<?php
    echo '</div>';
// Регистрация JS кода в отдельном файле
    try {
        $this->registerJsFile('@web/js/copy-button.js', ['depends' => [JqueryAsset::class]]);
    }
    catch (InvalidConfigException $e) {
        PrintHelper::exception(
            'this_registerJsFile', LAYOUT_ID, $e,
        );
    }
