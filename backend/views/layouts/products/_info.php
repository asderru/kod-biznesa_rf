<?php
    
    use backend\helpers\LinkHelper;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Forum\Thread;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Shop\Product\Product;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\ReadHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\helpers\ArrayHelper;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model Model|Product|Article|Chapter|Post|Thread */
    /* @var $editItems array */
    
    const PRODUCT_INFO_LAYOUT = '#layouts_products_info';
    echo PrintHelper::layout(PRODUCT_INFO_LAYOUT);
    
    try {
        $modelUrl = $model->getFullUrl();
    }
    catch (Exception $e) {
        PrintHelper::exception(
            'model->getFullUrl', PRODUCT_INFO_LAYOUT, $e,
        );
    }
    
    $parent = match ($model::TEXT_TYPE) {
        Constant::ARTICLE_TYPE => Html::a($model->section?->name, [
            'magazin/section/view', 'id' => $model->section_id,
        ]),
        Constant::POST_TYPE    => Html::a($model->category?->name, [
            'blog/category/view', 'id' => $model->category_id,
        ]),
        Constant::THREAD_TYPE  => Html::a($model->group?->name, [
            'forum/group/view', 'id' => $model->group_id,
        ]),
        
    };
    
    $parentTitle = match ($model::TEXT_TYPE) {
        Constant::ARTICLE_TYPE => 'Основная рубрика',
        Constant::POST_TYPE    => 'Блог',
        Constant::THREAD_TYPE  => 'Форум',
        
    };
    
    $otherParentTitle = match ($model::TEXT_TYPE) {
        Constant::ARTICLE_TYPE => 'Другие рубрики',
        default                => null,
    };
    
    $otherParentModels = match ($model::TEXT_TYPE) {
        Constant::ARTICLE_TYPE => implode(', ', ArrayHelper::getColumn($model->sections, 'name')),
        default                => null,
    };

?>


<div class="card">

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
                    <th scope='row'>Автор</th>
                    <td>" . Html::encode($model->author->name) . '</td>
                </tr>

                <tr>
                    <th scope='row'><?= $parentTitle ?></th>
                    <td><?= $parent ?></td>
                </tr>
                <?php
                    if ($otherParentTitle) { ?>
                        <tr>
                            <th scope='row'><?= $otherParentTitle ?></th>
                            <td><?= $otherParentModels ?>
                            </td>
                        </tr>
                        <?php
                    } ?>
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
            <div><small>Время чтения (мин.):</small> <strong><?= ReadHelper::getReadingTime($model->text)
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
                    <div class="card-header bg-body-secondary">Недавние правки <small>(всего: <?= $model->getEditCount()
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
        Полная ссылка модели: <?= Html::a(
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
