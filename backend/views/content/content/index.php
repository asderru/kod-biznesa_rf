<?php
    
    use core\helpers\ButtonHelper;
    use core\edit\search\Content\ContentSearch;
    use core\tools\Constant;
    
    /* @var $this yii\web\View */
    /* @var $searchModel ContentSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_content_index';
    
    $this->title = $label;
    
    $buttons = [];
    
    $this->params['breadcrumbs'][] = $this->title;
    
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
?>

<div class='card'>
    
    <?= $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        Копировать:
        <?= ButtonHelper::copy(Constant::RAZDEL_TYPE, Constant::RAZDEL_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::PRODUCT_TYPE, Constant::PRODUCT_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::BRAND_TYPE, Constant::BRAND_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::PAGE_TYPE, Constant::PAGE_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::NEWS_TYPE, Constant::NEWS_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::SECTION_TYPE, Constant::SECTION_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::ARTICLE_TYPE, Constant::ARTICLE_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::BOOK_TYPE, Constant::BOOK_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::CHAPTER_TYPE, Constant::CHAPTER_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::CATEGORY_TYPE, Constant::CATEGORY_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::POST_TYPE, Constant::POST_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::AUTHOR_TYPE, Constant::AUTHOR_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::GALLERY_TYPE, Constant::GALLERY_LABEL)
        ?>
        <?= ButtonHelper::copy(Constant::PHOTO_GALLERY_TYPE, Constant::PHOTO_GALLERY_LABEL)
        ?>
        <?= ButtonHelper::copyContent()
        ?>
    </div>

        <?= $this->render(
            '_partIndex',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ],
        )
        ?>

</div>
