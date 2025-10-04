<?php
    
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $user core\edit\entities\User\User */
    /* @var $model \core\edit\auth\ResetPasswordForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#user_user-password';
    
    $this->title                   = 'Смена пароля для пользователя ' . $user->name;
    $this->params['breadcrumbs'][] = [
        'label' => 'Пользователи',
        'url'   => [
            'index',
        ],
    ];
    $this->params['breadcrumbs'][] = $this->title;
    
    
    $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    );
    
    
    $form           = ActiveForm::begin()
?>
    <div class="container">
<?= $this->render(
    '/layouts/tops/_messages',
)
?>
    <div class="col-md-8 offset-md-2">
        <div class="first-title">
            <h1><?= Html::encode($this->title)
                ?></h1>
        </div>

        <div class="block-padding">

            <p>Пожалуйста, установите новый пароль:</p>

            <div class="col-lg-5">
                <?php
                    $form = ActiveForm::begin(
                        [
                            'id' => 'reset-password-form',
                        ],
                    )
                ?>
                
                <?= $form
                    ->field($model, 'password')
                    ->passwordInput(
                        [
                            'autofocus' => true,
                        ],
                    )
                ?>

                <div class="form-group text-center">
                    <?= Html::submitButton(
                        'Установить',
                        [
                            'class' => 'btn btn-sm btn-primary',
                        ],
                    )
                    ?>
                </div>
                
                <?php
                    ActiveForm::end()
                ?>
            </div>
        </div>
    </div>
<?php
    ActiveForm::end();
