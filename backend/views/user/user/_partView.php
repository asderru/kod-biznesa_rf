<?php
    
    use backend\helpers\StatusHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\helpers\ArrayHelper;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\User\User */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    const USER_PART_LAYOUT = '#user_user_partView';
    echo PrintHelper::layout(USER_PART_LAYOUT);
    
    $label = 'Пользователи';
    
    $this->title = 'Пользователь ' . $model->username;

?>


<div class="row">
    <div class='col-md-8'>

        <div class='card bg-light mb-3'>
            <div class='card-header bg-light'>

                <h4><?= Html::encode($this->title)
                    ?></h4>
                
                <?= StatusHelper::statusLabel($model->status) ?>

            </div>
            <div class="card-body">

                <div class='table-responsive'>
                    <?php
                        try {
                            echo
                            DetailView::widget(
                                [
                                    'model'      => $model,
                                    'attributes' => [
                                        'id',
                                        [
                                            'attribute' => 'username',
                                            'label'     => 'пользователь',
                                        ],
                                        'email:email',
                                        [
                                            'label'  => 'роль',
                                            'value'  => implode(
                                                ', ',
                                                ArrayHelper::getColumn(
                                                    Yii::$app->authManager
                                                        ->getRolesByUser($model->id),
                                                    'description',
                                                ),
                                            ),
                                            'format' => 'raw',
                                        ],
                                        [
                                            'attribute' => 'created_at',
                                            'format'    => 'datetime',
                                            'label'     => 'создан',
                                        ],
                                        [
                                            'attribute' => 'updated_at',
                                            'format'    => 'dateTime',
                                            'label'     => 'обновлен',
                                        ],
                                    ],
                                ],
                            );
                        }
                        catch (Exception|Throwable $e) {
                            PrintHelper::exception(
                                'DetailWidget', $e,
                            );
                        } ?>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <p>
                    <?= Html::a(
                        'Редактировать данные', [
                        'update',
                        'id' => $model->id,
                    ], [
                        'class' => 'btn btn-success m-1',
                    ],
                    )
                    ?>
                    <?=
                        ($model->hasPerson())
                            ?
                            Html::a(
                                'Редактировать профили', [
                                'person',
                                'id' => $model->id,
                            ], [
                                'class' => 'btn btn-outline-success m-1',
                            ],
                            )
                            :
                            Html::a(
                                'Создать профиль', [
                                'user/person/create',
                                'id' => $model->id,
                            ], [
                                'class' => 'btn btn-outline-primary m-1',
                            ],
                            )
                    ?>
                    
                    <?= Html::a(
                        'Сменить пароль', [
                        'password',
                        'id' => $model->id,
                    ], [
                        'class' => 'btn btn-info m-1',
                    ],
                    )
                    ?>
                    
                    <?= Html::a(
                        'Удалить', [
                        'delete',
                        'id' => $model->id,
                    ],
                        [
                            'class' => 'btn btn-danger m-1',
                            'data'  => [
                                'confirm' => 'Подтвердить удаление',
                                'method'  => 'POST',
                            ],
                        ],
                    )
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>
