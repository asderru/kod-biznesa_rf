<?php
    
    use backend\helpers\BreadCrumbHelper;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\search\Library\ChapterSearch;
    use core\helpers\ButtonHelper;
    use core\tools\Constant;
    use yii\data\ActiveDataProvider;
    
    /* @var $this yii\web\View */
    /* @var $searchModel ChapterSearch */
    /* @var $dataProvider ActiveDataProvider */
    /* @var $chapters Chapter[] */
    /* @var $books Book[] */
    /* @var $book Book */
    /* @var $roots Chapter[] */
    /* @var $sites Information[] */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_chapter_index';
    
    $this->title = $label;
    
    $buttons = [
        ButtonHelper::expressType($textType, null, 'Экспресс-правка'),
        ButtonHelper::structure($textType, null, 'Структура текстов'),
        ButtonHelper::import($textType),
        ButtonHelper::export($textType),
        ($book) ?
            ButtonHelper::createType($textType, $book->id, 'Добавить в том ' . $book->name)
            : null,
    ];
    
    $buttons[] = '<hr> Сортировать главы: ';
    
    foreach ($books as $resort) {
        $buttons[] = ButtonHelper::resort(
            $resort['id'],
            $resort['name'] . ' (' . $resort['site_id'] . ')',
        );
    }
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::BOOK_TYPE);
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
            '/library/chapter/_partIndex.php',
            [
                'dataProvider' => $dataProvider,
                'searchModel'  => $searchModel,
                'books' => $books,
                'url' => '/library/',
                'full'         => true,
            ],
        );
        ?>


    </div>

</div>
