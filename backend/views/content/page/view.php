<?php

    use backend\helpers\BreadCrumbHelper;
    use backend\widgets\PagerWidget;
    use core\edit\entities\Admin\Edit;
    use core\edit\entities\Content\ContentCard;
    use core\edit\entities\Content\Note;
    use core\edit\entities\Content\Review;
    use core\edit\entities\Content\Tag;
    use core\edit\entities\Library\LibraryCard;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Seo\Footnote;
    use core\edit\entities\Tools\Draft;
    use core\edit\entities\Tools\Keyword;
    use core\edit\entities\Utils\Gallery;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\BulkHelper;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\Html;

    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Content\Page */
    /* @var $card Edit */
    /* @var $cardFields ContentCard */
    /* @var $editItems Edit */
    /* @var $parents core\edit\entities\Content\Page[] */
    /* @var $children core\edit\entities\Content\Page[] */
    /* @var $prevModel core\edit\entities\Content\Page[] */
    /* @var $nextModel core\edit\entities\Content\Page[] */
    /* @var $faqs Faq[] */
    /* @var $footnotes Footnote[] */
    /* @var $reviews Review[] */
    /* @var $editItems Edit[] */
    /* @var $drafts Draft[] */
    /* @var $notes Note[] */
    /* @var $tags Tag[] */
    /* @var $galleries Gallery[] */
    /* @var $assignedGalleries Gallery[] */
    /* @var $keywords Keyword */
    /* @var $contentCard ContentCard */
    /* @var $contentCardFields array */
    /* @var $contentCard LibraryCard */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */

    const LAYOUT_ID = '#content_page_view';
    $this->title = $model['id'] . '. ' . $model['name'];

?>
<div class='card'>


    <div class='card-body'>


        <!--###### Контент ######################################################-->
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
            <div class="card-footer">
                <?= ButtonHelper::update($model['id'], 'Редактировать') ?>
            </div>
        </div>
    </div>
</div>

        <!--###### // Контент ######################################################-->
    </div>

</div>;
