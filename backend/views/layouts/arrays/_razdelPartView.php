<?php

    use core\edit\entities\Content\ContentCard;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\helpers\StatusHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;

    /* @var $this yii\web\View */
    /* @var $model array */
    /* @var $parent array */
    /* @var $parents array */
    /* @var $contentCard ContentCard */
    /* @var $cardFields ContentCard */
    /* @var $uploadForm UploadPhotoForm */
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

    const RAZDELS_PART_VIEW_LAYOUT = '#layouts_razdels_arrayPartView';
    echo PrintHelper::layout(RAZDELS_PART_VIEW_LAYOUT);

    $showGalleries = match ($textType) {
        Constant::RAZDEL_TYPE, Constant::PAGE_TYPE, Constant::BOOK_TYPE => true,
        default                                                         => false,
    };

    $showParentModel = match ($textType) {
        Constant::FAQ_TYPE, Constant::MATERIAL_TYPE, Constant::BRAND_TYPE => true,
        default                                                           => false,
    };

    $showViewWidget    = match ($textType) {
        Constant::RAZDEL_TYPE, Constant::PAGE_TYPE, Constant::BOOK_TYPE => true,
        default                                                         => false,
    };
    $parent            = $parent ?? [];
    $parents           = $parents ?? [];
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
?>

<div class='row mb-3'>

    <div class='col-xl-6'>

        <div class='card h-100'>

            <div class='card-header bg-light d-flex justify-content-between'>
                <h5>
                    Информация
                </h5>

            </div>

            <div class='card-body'>
                <div class='table-responsive'>
                    <table class='table table-sm table-striped table-bordered'>
                        <tbody>
                        <tr>
                            <th scope='row'>id <small>(статус)</small></th>
                            <td><?= $model['id'] ?> <small>(<?= $model['status'] ?>)</small></td>
                        </tr>
                        <tr>
                            <th scope='row'>Краткое название</th>
                            <td><?= Html::encode($model['name'])
                                ?></td>
                        </tr>
                        <tr>
                            <th scope='row'>Полное название</th>
                            <td>
                                <?= Html::encode($model['title']) ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

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
        </div>
    </div>

    <div class='col-xl-6'>

        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Текст
                </strong>
            </div>

            <div class='card-body' id='card-body-text'>
                <?= FormatHelper::asHtml($model['text']); ?>

            </div>
        </div>
    </div>
</div>
