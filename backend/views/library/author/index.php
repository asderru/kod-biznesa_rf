<?php
    
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Library\Author;
    use core\edit\search\Library\AuthorSearch;
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $this yii\web\View */
    /* @var $sites Information[] */
    /* @var $searchModel AuthorSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $roots Author[] */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_author_index';

?>

<div class='card'>

    <div class='card-body'>
            
            <?= $this->render('_partIndex', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'url' => '/library/author/',
            ])
            ?>
    </div>

</div>
