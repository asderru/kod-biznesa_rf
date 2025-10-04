<?php
    
    use backend\helpers\BreadCrumbHelper;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Library\Book;
    use core\edit\search\Library\BookSearch;
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $this yii\web\View */
    /* @var $sites Information[] */
    /* @var $searchModel BookSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $roots Book[] */
    /* @var $files array */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_book_index';
    
    $this->title = $label;
    
    $buttons   = [
        ButtonHelper::expressType($textType, null, 'Экспресс-правка'),
        ButtonHelper::structure($textType, null, 'Структура книг'),
        ButtonHelper::import($textType),
        ButtonHelper::export($textType),
    ];
    $buttons[] = '<hr> Сортировать на сайте: ';
    foreach ($sites as $site) {
        $buttons[] = Html::button($site['name'], [
            'class' => 'btn btn-sm btn-info mb-1 me-1',
            'id'    => 'site-' . $site['id'],
        ]);
    }
    $buttons[] = '<br>';
    
    $buttons[] = 'Смена сайта для книг: ';
    foreach ($sites as $changedSite) {
        $buttons[] = ButtonHelper::changeSite(
            $changedSite['id'],
            $textType,
            $changedSite['name'],
        );
    }
    
    $buttons[] = '<hr> Смотреть на сайте: ';
    
    foreach ($roots as $root) {
        $buttons[] = Html::a(
            $root->name . '. Сайт ' . $root->site->name,
            Url::to('https://' . $root->site->name . '/' . $root->slug . '/', true),
            [
                'class' => 'btn btn-sm btn-outline-info mb-1 me-1',
            ],
        );
    }
    $buttons[] = '<hr> Файлы книг на сервере: ' . ButtonHelper::uploads();
    
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
                '/library/book/_partIndex',
                [
                    'dataProvider' => $dataProvider,
                    'searchModel'  => $searchModel,
                    'url'          => '/library/book/',
                    'full'         => true,
                ],
            );
            ?>

    </div>

</div>
