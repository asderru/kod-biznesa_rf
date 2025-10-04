<?php

    use core\edit\entities\Content\ContentCard;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\helpers\StatusHelper;
    use core\helpers\types\TypeUrlHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;

    /* @var $this yii\web\View */
    /* @var $model array */
    /* @var $contentCard ContentCard */
    /* @var $cardFields ContentCard */
    /* @var $libraryCard array */
    /* @var $previousTime int */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $parent array */
    /* @var $children array */
    /* @var $tags array */
    /* @var $faqs array */
    /* @var $footnotes array */
    /* @var $keywords array */
    /* @var $galleries array */
    /* @var $assignedGalleries array */
    /* @var $editItems array */
    /* @var $notes array */
    /* @var $drafts array */
    /* @var $buttons bool */
    /* @var $modal bool */
    /* @var $textType int */
    /* @var $textType int */

    const PRODUCTS_PART_VIEW_LAYOUT = '#layouts_products_arrayPartView';
    echo PrintHelper::layout(PRODUCTS_PART_VIEW_LAYOUT);

    $showGalleries = match ($textType) {
        Constant::PRODUCT_TYPE, Constant::CHAPTER_TYPE => true,
        default                                        => false,
    };

    $showViewWidget = match ($textType) {
        Constant::PRODUCT_TYPE, Constant::CHAPTER_TYPE => true,
        default                                        => false,
    };

    $parent            = $parent ?? [];
    $contentCard       = $contentCard ?? [];
    $contentCardFields = $contentCardFields ?? [];
    $libraryCard       = $libraryCard ?? [];
    $previousTime      = $previousTime ?? [];
    $editItems         = $editItems ?? [];
    $faqs              = $faqs ?? [];
    $notes             = $notes ?? [];
    $drafts            = $drafts ?? [];
    $footnotes         = $footnotes ?? [];
    $keywords          = $keywords ?? [];
    $galleries         = $galleries ?? [];
    $assignedGalleries = $assignedGalleries ?? [];
    $tags              = $tags ?? [];
    $uploadForm        = $uploadForm ?? [];

    $modelUrl = TypeUrlHelper::getModelLinkUrl($model);

?>

<div class='row mb-3'>

    <div class='col-xl-6'>

        <div class='card h-100'>

            <div class='card-header bg-light d-flex justify-content-between'>
                <h5>
                    Информация
                </h5>
                <div class='px-1 text-end'>
                    <?php
                        if ($contentCard): ?>
                            <button class='btn btn-sm btn-outline-dark' type='button' data-bs-toggle='offcanvas'
                                    data-bs-target='#contentContentCard'
                                    aria-controls='contentContentCard'>Посмотреть карточку контента
                            </button>
                        <?php
                        else: ?>
                            <?= ButtonHelper::contentCardCreate($textType, $model['id']) ?>
                        <?php
                        endif; ?>

                </div>

            </div>

            <div class='card-body'>
                <!-- Блок информации -->
                <?= $this->render(
                        '/layouts/arrays/_mainInformation',
                        [
                                'model'    => $model,
                                'modelUrl' => $modelUrl,
                                'tags'     => $tags,
                                'keywords' => $keywords,
                        ],
                ) ?>

                <!-- Блок управления датой и временем  -->
                <?= $this->render(
                        '/layouts/arrays/_libraryCard',
                        [
                                'libraryCard'  => $libraryCard,
                                'previousTime' => $previousTime,
                        ],
                ) ?>

                <?= $this->render(
                        '/layouts/arrays/_parentModel',
                        [
                                'parent' => $parent,
                                'textType' => $textType,
                        ],
                ) ?>

                <?= $this->render(
                        '/layouts/arrays/_readingTime',
                        [
                                'text' => $model['text'],
                        ],
                ) ?>
            </div>

            <div class='card-header bg-dark-subtle'>
                <strong>
                    Описание (тег Descriptiion)
                </strong>
            </div>
            <div class='card-body'>
                <?= FormatHelper::asHtml($model['description'])
                ?>
            </div>

            <?= $this->render(
                    '/layouts/arrays/_metaData',
                    [
                            'faqs'      => $faqs,
                            'footnotes' => $footnotes,
                    ],
            ) ?>
            <div class='card-footer'>
                <?= $this->render(
                        '/layouts/modals/_addContent',
                        [
                                'model' => $model,
                        ],
                )
                ?>
            </div>
            <div class='p-2'>
                <a class='btn btn-sm btn-outline-dark' data-bs-toggle='collapse'
                   href='#collapseTechInfo'
                   role='button'
                   aria-expanded='false' aria-controls='collapseTechInfo'>
                    Техническая информация
                </a>
                <?php
                    if ($editItems): ?>
                        <a class='btn btn-sm btn-outline-primary' data-bs-toggle='collapse'
                           href='#collapseEditInfo'
                           role='button'
                           aria-expanded='false' aria-controls='collapseEditInfo'>
                            Редакторская информация
                        </a>
                    <?php
                    else: ?>
                        <span class="text-muted">Правка модели не осуществлялась</span>
                    <?php
                    endif ?>
            </div>
            <div class='collapse p-2' id='collapseTechInfo'>
                <div class='card border-secondary'>
                    <div class='card-body'>
                        <div class='border-bottom p-1'>
                            <strong>Ключевые слова</strong>:
                            <?php
                                if (!empty($keywordsArray)): ?>
                                    <div class="keywords-container">
                                        <?php
                                            foreach ($keywordsArray as $keyword): ?>
                                                <span class="badge bg-primary"><?= Html::encode($keyword) ?></span>
                                            <?php
                                            endforeach; ?>
                                    </div>
                                <?php
                                else: ?>
                                    <p>не определены</p>
                                <?php
                                endif; ?>
                        </div>
                        <div class='border-bottom p-1'>
                            <strong>Контент</strong>:
                            <?= $model['content_id'] ?>.
                        </div>
                        <div class='border-bottom p-1'>
                            <strong>Панель</strong>:
                        </div>
                        <div class='border-bottom p-1'>
                            <strong>Статус</strong>:
                            <?= $model['status'] ?> -
                            <?= StatusHelper::getStatusName($model['status'], $textType) ?>.
                        </div>
                    </div>
                </div>

            </div>
            <?php
                if ($editItems): ?>
                    <div class='collapse p-2' id='collapseEditInfo'>
                        <div class='card border-secondary'>
                            <div class="card-header bg-body-secondary">Недавние правки
                                <small>(всего: <?= count($editItems)
                                    ?>)</small>
                            </div>
                            <div class='card-body table-responsive'>
                                <table class='table table-striped'>
                                    <thead class='table-light'>
                                    <td>Дата</td>
                                    <td>Время</td>
                                    <td>Слов</td>
                                    </thead>
                                    <tbody>
                                    <?php
                                        foreach ($editItems as $editItem): ?>
                                            <tr>
                                                <td><?= $editItem['created_at'] ?></td>
                                                <td><?= FormatHelper::getTimeDifference(
                                                            $editItem['created_at'],
                                                            $editItem['updated_at'],
                                                    ) ?></td>
                                                <td><?= $editItem['words'] ?></td>
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

    <div class='col-xl-6'>
        <?php
            echo $this->render(
                    '/layouts/images/_images',
                    [
                            'model'      => $model,
                            'uploadForm' => $uploadForm,
                    ],
            );
            if ($showGalleries) {
                echo $this->render(
                        '/layouts/images/_galleries',
                        [
                                'galleries'         => $galleries,
                                'assignedGalleries' => $assignedGalleries,
                                'textType'          => $textType,
                                'modelId'           => $model['id'],
                        ],
                );
            }
            echo $this->render(
                    '/layouts/widgets/linkWidget',
                    [
                            'model' => $model,
                            'modelUrl' => $modelUrl,
                    ],
            );
        ?>

    </div>

</div>

<?php
    if ($showViewWidget) {
        echo $this->render(
                '/layouts/templates/_viewWidgets',
                [
                        'model'    => $model,
                        'keywords' => $keywords,
                        'notes'    => $notes,
                        'drafts'   => $drafts,
                ],
        );
    }
    echo
    $this->render(
            '/layouts/templates/_textWidget',
            [
                    'text' => $model['text'],
            ],
    );

?>
