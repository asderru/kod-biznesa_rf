<?php

    use backend\helpers\BreadCrumbHelper;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Content\Page;
    use core\helpers\BulkHelper;
    use core\helpers\ButtonHelper;

    /* @var $this yii\web\View */
    /* @var $searchModel core\edit\search\Content\PageSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $roots Page[] */
    /* @var $sites Information[] */
    /* @var $files array */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */

    const LAYOUT_ID = '#content_page_index';

    $this->title = $label;

    $buttons = [
            ButtonHelper::expressType($textType, null, 'Экспресс-правка'),
            ButtonHelper::structure($textType, null, 'Структура страниц'),
            ButtonHelper::import($textType),
            ButtonHelper::export($textType),
            '<br>',
            ButtonHelper::clearCreatedDates(),
    ];

    $buttons[] = BulkHelper::sessionButtons($textType);

    $this->params['breadcrumbs'][] = BreadCrumbHelper::structure($textType);
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

    <div class='card-body'>

        <?= $this->render(
                '/content/page/_partIndex',
                [
                        'dataProvider' => $dataProvider,
                        'searchModel'  => $searchModel,
                        'textType'     => $textType,
                        'url'          => '/content/page/',
                        'full'         => true,
                ],
        );
        ?>

    </div>

</div>
