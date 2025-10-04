<?php
    
    use core\edit\search\User\PersonSearch;
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $user core\edit\entities\User\User */
    /* @var $persons core\edit\entities\User\Person[] */
    /* @var $searchModel PersonSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_user_person';
    
    $this->title = 'Профили пользователя: ' . $user->username;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Пользователи',
        'url'   => [
            'index',
        ],
    ];
    
    $this->params['breadcrumbs'][] = [
        'label' => $user->username,
        'url'   => [
            'view',
            'id' => $user->id,
        ],
    ];
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Профили',
        'url'   => [
            '/user/person/index',
        ],
    ];
    
    $this->params['breadcrumbs'][] = 'Правка';
    
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

<div class='card bg-light'>

    <div class='card-header bg-gray d-flex justify-content-between'>
        <div class='h4'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div>
            <?= ButtonHelper::create()
            ?>
            <?php
                echo
                ButtonHelper::refresh(); ?>
            <?= ButtonHelper::collapse()
            ?>
        </div>
    </div>

    <div class='table-responsive'>
        
        <?= $this->render('/user/user/_partIndex', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'url'          => 'user/person/',
        ])
        ?>
    </div>

</div>
