<?php
    
    /* @var $this yii\web\View */
    /* @var $searchModel core\edit\search\User\UserSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_user_index';
    
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
    )
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
                '@app/views/user/user/_partIndex',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'url'          => 'user/user/',
                ],
            )
            ?>
    </div>
</div>
