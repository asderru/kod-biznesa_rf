<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model core\edit\entities\User\User */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_user_view';
    
    $this->title = 'Пользователь ' . $model->username;
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->username;
    
    $buttons = [
         ButtonHelper::activation($model),
         ButtonHelper::simpleDelete($model),
            ];
    
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
    
    try {
        echo
        PagerWidget::widget(
            [
                'model'  => $model,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }

echo '<div class="card">';
 
echo $this->render(
    '/layouts/tops/_viewHeaderModel',
        [
            'model' => $model,
            'textType' => $textType,
            'buttons'  => $buttons, // передаем массив кнопок
        ],
)
?>

    <div class='card-body'>
        
        <?= $this->render(
            '/user/user/_partView',
            [
                'model' => $model,
            ],
        )
        ?>

    </div>

<?php echo '</div>';
