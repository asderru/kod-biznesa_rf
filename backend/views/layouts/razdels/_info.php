<?php
    
    use backend\helpers\StatusHelper;
    use backend\helpers\UrlHelper;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\ReadHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model Model|Razdel|Section|Book|Category|Page|Information|Group */
    /* @var $editItems array */
    
    const RAZDELS_INFO_LAYOUT = '#layouts_razdels_info';
    
    echo PrintHelper::layout(RAZDELS_INFO_LAYOUT);
    
    $parentTitle = match ($model::TEXT_TYPE) {
        Constant::RAZDEL_TYPE   => 'Родительский раздел',
        Constant::SECTION_TYPE  => 'Родительская рубрика',
        Constant::BOOK_TYPE     => 'Родительский том',
        Constant::CATEGORY_TYPE => 'Родительский блог',
        Constant::PAGE_TYPE     => 'Родительская страница',
        Constant::SITE_TYPE     => 'Родительский сайт',
        Constant::GROUP_TYPE    => 'Родительский форум',
        
    };
    
    $childrenTitle = match ($model::TEXT_TYPE) {
        Constant::RAZDEL_TYPE   => 'Вложенные разделы',
        Constant::SECTION_TYPE  => 'Вложенные рубрики',
        Constant::BOOK_TYPE     => 'Вложенные тома',
        Constant::CATEGORY_TYPE => 'Вложенные блоги',
        Constant::PAGE_TYPE     => 'Вложенные страницы',
        Constant::SITE_TYPE     => 'Вложенные сайты',
        Constant::GROUP_TYPE    => 'Вложенные форумы',
    };

?>


<div class='card h-100'>

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
                    <th scope='row'>id</th>
                    <td><?= $model->id ?></td>
                </tr>
                <tr>
                    <th scope='row'>Сайт</th>
                    <td><?= ParametrHelper::getSiteName($model->site_id) ?></td>
                </tr>
                <?php
                    if ($model->depth > Constant::THIS_FIRST_NODE) { ?>
                        <tr>
                            <th scope='row'><?= $parentTitle ?></th>
                            <td><?= Html::encode($model->parent?->name)
                                ?></td>
                        </tr>
                        <?php
                    } ?>
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
                    <th scope='row'>Полная ссылка</th>
                    <td><?= Html::encode($model->link)
                        ?></td>
                </tr>
                <tr>
                    <th scope='row'>Ключевое слово (главное)</th>
                    <td><?= Html::encode($model->getMainKeyword())
                        ?></td>
                </tr>
                <?php
                    if (isset($model->rating)) { ?>
                        <tr>
                            <th scope='row'>Рейтинг SEO</th>
                            <td><?= $model->rating ?></td>
                        </tr>
                        <?php
                    } ?>
                <?php
                    if (isset($model->tags)) { ?>
                        <tr>
                            <th scope='row'>Метки</th>
                            <td><?= implode(', ', ArrayHelper::getColumn($model->tags, 'name'))
                                ?></td>
                        </tr>
                        <?php
                    } ?>
                <tr>
                    <th scope='row'>Время обновления</th>
                    <td><?= FormatHelper::asDateTime($model->updated_at)
                        ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class='d-flex justify-content-between'>
            <div><small>Время чтения:</small> <strong><?= ReadHelper::getReadingTime($model->text)
                    ?></strong>мин.
            </div>
            <div><small>Слов:</small> <strong><?= ReadHelper::getWordCount($model->text)
                    ?></strong></div>
            <div><small>Знаков:</small> <strong><?= ReadHelper::getCharactersCount($model->text)
                    ?></strong></div>
        </div>
        <hr>
        <div class='p-2'>
            <a class='btn btn-sm btn-outline-dark' data-bs-toggle='collapse'
               href='#collapseTechInfo'
               role='button'
               aria-expanded='false' aria-controls='collapseTechInfo'>
                Техническая информация
            </a>
            <?php
                if ($model->getEditItems()->exists()): ?>
                    <a class='btn btn-sm btn-outline-primary' data-bs-toggle='collapse'
                       href='#collapseEditInfo'
                       role='button'
                       aria-expanded='false' aria-controls='collapseEditInfo'>
                        Редакторская информация
                    </a>
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
                        <strong><?= $childrenTitle ?></strong>:
                        <?php
                            foreach ($model->children()->all() as $child) { ?>
                                <?= Html::a(
                                    $child->name,
                                    [
                                        'view',
                                        'id' => $child->id,
                                    ],
                                )
                                ?>,
                                <?php
                            } ?>
                    </div>
                    <div class='border-bottom p-1'>
                        <strong>Ключевые слова</strong>:
                        <?= implode(', ', ArrayHelper::getColumn($model->getKeywords()->all(), 'name'))
                        ?>
                    </div>
                    <div class='border-bottom p-1'>
                        <strong>Метки</strong>:
                        <?= implode(', ', ArrayHelper::getColumn($model->tags, 'name'))
                        ?>
                    </div>
                    <div class='border-bottom p-1'>
                        <strong>Структура</strong>:
                        Структура - <?= $model->tree ?>.
                        Поле LFT - <?= $model->lft ?>.
                        Поле RGT - <?= $model->rgt ?>.
                        Глубина - <?= $model->depth ?>.
                    </div>
                    <div class='border-bottom p-1'>
                        <strong>Контент</strong>:
                        <?= $model->content_id ?>.
                    </div>
                    <div class='border-bottom p-1'>
                        <strong>Цветовое решение</strong>:
                        <?= $model->color ?>.
                    </div>
                    <div class='border-bottom p-1'>
                        <strong>Панель</strong>:
                        <?= $model->panel_id ?>.
                    </div>
                    <div class='border-bottom p-1'>
                        <strong>Статус</strong>:
                        <?= $model->status ?> ->
                        <?= StatusHelper::getStatusName($model->status) ?>.
                    </div>
                </div>
                <div class="card-footer">
                    
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
            </div>

        </div>
        <hr>
        <?php
            if (ParametrHelper::isLocal()): ?>
                <small>
                    Локальная ссылка:
                </small> <?= UrlHelper::local($model)
                ?>
            <?php
            endif; ?>
        <hr>
        <small>
            Ссылка:
        </small>
        <?= Html::a(
            Url::to($model['link'], true),
            Url::to($model['link'], true),
            [
                'target' => '_blank',
            ],
        )
        ?>.
        <span>
                <?=
                    UrlHelper::checkUrl($model->status, $model->link)
                ?>
            </span>
        <div class='py-2'>
            <button
                    onclick='triggerCopy()'
                    id='copy-button'
                    class='btn btn-sm btn-outline-dark'
            >Скопировать
            </button>
            <br>
            <?= ButtonHelper::editSlug($model->id); ?>
            <?= ButtonHelper::editSeolink($model->id); ?>
            <span
                    id='copyUrl' class='invisible'
            ><?= $model->link ?></span>
            <?= $this->render(
                '/layouts/scripts/copyUrl',
            )
            ?>
        </div>
        
        <?php
            if (!$model->link): ?>
                
                <?php
                if ($model->site_id === Parametr::siteId()): ?>
                    <hr>
                    <div>
                        <small>Ссылка SEO:</small>
                        <strong>
                            <?= $model->link ?>
                        </strong>
                    </div>
                <?php
                endif; ?>
                
                <?php
                if ($model->site_id !== Parametr::siteId()): ?>
                    <strong>
                        SEO-ссылку можно создать только из панели сайта <?= $model->site->name ?>
                    </strong>
                <?php
                endif;
            endif; ?>

    </div>

    <div class='card-header bg-dark-subtle'>
        <strong>
            Описание (тег Descriptiion)
        </strong>
    </div>
    <div class='card-body'>
        <?= FormatHelper::asHtml($model->description)
        ?>
    </div>
</div>
