<?php
    
    use backend\assets\AppAsset;
    use core\edit\auth\LoginForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /** @var View $this */
    /** @var string $image */
    /** @var LoginForm $model */
    
    $this->title = 'Вход в панель управления ';
    
    AppAsset::register($this);

?>
<div class='container d-flex flex-column'>
    <div class='row align-items-center justify-content-center g-0 min-vh-100'>
        <div class='col-12 col-md-8 col-lg-6 col-xxl-4 py-8 py-xl-0'>
            <!-- Card -->
            <div class='card smooth-shadow-md'>
                <!-- Card body -->
                <div class='card-body p-6'>
                    
                    <?= Html::a(
                        Html::tag(
                            'h6',
                            Yii::$app->name,
                            [
                                'class' => 'mb-2 text-end',
                            ],
                        ),
                        '@web',
                    )
                    ?>
                    
                    <?php
                        try {
                            echo
                            Html::img(
                                $image,
                                [
                                    'class' => 'img-fluid mb-4',
                                ],
                            );
                        }
                        catch (Exception|Throwable $e) {
                            PrintHelper::exception(
                                'Главная картинка', $e,
                            );
                        } ?>
                    
                    <?php
                        $form = ActiveForm::begin(
                            [
                                'id' => 'login-form',
                            ],
                        )
                    ?>
                    
                    <?= $form
                        ->field(
                            $model, 'username',
                            [
                                'options'        => [
                                    'class' => 'form-group has-feedback',
                                ],
                                'inputTemplate'  => '{input}',
                                'template'       => '{beginWrapper}{input}{error}{endWrapper}',
                                'wrapperOptions' => [
                                    'class' => 'input-group mb-3',
                                ],
                            ],
                        )
                        ->label(
                            false,
                        )
                        ->textInput(
                            [
                                'placeholder' => $model->getAttributeLabel('логин'),
                            ],
                        )
                    ?>
                    
                    <?= $form
                        ->field(
                            $model, 'password',
                            [
                                'options'        => [
                                    'class' => 'form-group has-feedback',
                                ],
                                'inputTemplate'  => '{input}',
                                'template'       => '{beginWrapper}{input}{error}{endWrapper}',
                                'wrapperOptions' => [
                                    'class' => 'input-group mb-3',
                                ],
                            ],
                        )
                        ->label(
                            false,
                        )
                        ->passwordInput(
                            [
                                'placeholder' => $model->getAttributeLabel('пароль'),
                            ],
                        )
                    ?>


                    <!-- Checkbox -->
                    <div class='d-lg-flex justify-content-between align-items-center mb-4'>
                        <?= $form
                            ->field($model, 'rememberMe')
                            ->checkbox()
                            ->label(
                                'Запомнить меня на 30 дней',
                            )
                        ?>
                    </div>

                    <div>
                        <!-- Button -->
                        <div class='d-grid'>
                            <?= Html::submitButton(
                                'Войти',
                                [
                                    'class' => 'btn btn-sm btn-primary btn-block',
                                    'name'  => 'login-button',
                                ],
                            )
                            ?>
                        </div>

                        <div class='d-md-flex justify-content-between mt-4'>
                            <div>
                                <?= date('H:i  d-m-Y')
                                ?>
                            </div>
                            <div>
                                <a
                                        href="<?=
                                            Yii::getAlias('@web')
                                        ?>\auth\reset\request"
                                        class='text-inherit fs-5'
                                >*
                                    Забыл
                                    пароль</a>
                            </div>

                        </div>
                    </div>
                    <?php
                        ActiveForm::end()
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
