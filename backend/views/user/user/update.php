<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $model core\edit\forms\User\UserEditForm */
    /* @var $user core\edit\entities\User\User */
    /* @var $form yii\bootstrap5\ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_user_update';
    
    $this->title = 'Пользователь - ' . $user->username;
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = $user->username;
    
    $id     = $user->id;
    $siteId = $user->site_id;
    
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
                'model'  => $user,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }
    
    $form = ActiveForm::begin(
        [
            'options'     => [
                'class' => 'active__form',
            ],
            'fieldConfig' => [
                'errorOptions' => [
                    'encode' => false,
                    'class'  => 'help-block',
                ],
            ],
        ],
    );
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_updateHeader',
        [
            'model'    => $user,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>
    <div class='card-body'>
        <?= $form
            ->field($model, 'email')
            ->textInput(
                [
                    'maxLength' => true,
                    'type'      => 'email',
                ],
            )
        ?>
        
        <?= $form
            ->field($model, 'role')
            ->dropDownList(
                [
                    'user'  => 'Пользователь',
                    'admin' => 'Администратор',
                
                ],
            )
            ->label(
                'Пользовательская роль',
            )
        ?>

    </div>
    <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
        <?= ButtonHelper::submit()
        ?>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
