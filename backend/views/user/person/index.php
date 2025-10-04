<?php
    
    use core\edit\search\User\PersonSearch;
    
    /* @var $this yii\web\View */
    /* @var $searchModel PersonSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_person_index';
    
    $this->title = 'Профили';
?>

<div class='card'>


    <div class='card-body'>
            <?= $this->render('@app/views/user/person/_partIndex', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'url'          => 'user/person/',
            ])
            ?>
    </div>
</div>
