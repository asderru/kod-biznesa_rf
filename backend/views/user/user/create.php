<?php
    
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\User\UserForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_user_create';
    
    $mainTitle   = ' Пользователи';
    $this->title = 'Создать пользователя';
    
    $this->params['breadcrumbs'][] = ['label' => $mainTitle, 'url' => ['index']];
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
    
    $form           = ActiveForm::begin(
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
        '/layouts/tops/_createHeader',
        [
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>

    <div class="card-body">
        
        <?= $form
            ->field($model, 'username')
            ->textInput(
                [
                    'maxLength' => true,
                ],
            )
            ->label('Пользователь')
        ?>
        
        <?= $form
            ->field($model, 'email')
            ->textInput(
                [
                    'maxLength' => true,
                    'type'      => 'email',
                ],
            )
            ->label('Е-мейл')
        ?>
        
        <?= $form
            ->field($model, 'password')
            ->passwordInput(
                [
                    'maxLength' => true,
                ],
            )
            ->label('Пароль')
        ?>
        
        <?=
            $form
                ->field($model, 'role')
                ->dropDownList(
                    [
                        'user'  => 'Пользователь',
                        'admin' => 'Администратор',
                    
                    ],
                )
                ->label('Пользовательская роль')
        ?>

        <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::submit()
            ?>

        </div>

    </div>


<?php
    echo '</div>';
    
    ActiveForm::end();
