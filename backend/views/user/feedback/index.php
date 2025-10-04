<?php
    
    use core\edit\search\User\FeedbackSearch;
    
    /* @var $this yii\web\View */
    /* @var $searchModel FeedbackSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_feedback_index';
    
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
                '@app/views/user/feedback/_partIndex',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'url'          => 'user/feedback/',
                ],
            )
            ?>

    </div>

</div>
