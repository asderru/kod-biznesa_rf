<?php
    
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\User\Person */
    /* @var $uploadForm UploadPhotoForm */
    
    const USER_PERSON_PART_LAYOUT = '#user_person_partView';
    echo PrintHelper::layout(USER_PERSON_PART_LAYOUT);

?>

<div class='row'>

    <div class='col-xl-6'>
        <div class='card mb-3'>

            <div class='card-header bg-light'>
                <strong>
                    Персона
                </strong>
            </div>
            <div class='card-body'>

                <div class='table-responsive'>
                    
                    <?php
                        try {
                            echo DetailView::widget(
                                [
                                    'model'      => $model,
                                    'attributes' => [
                                        'id',
                                        [
                                            'attribute' => 'user.name',
                                            'label'     => 'Пользователь',
                                        ],
                                        [
                                            'attribute' => 'site.name',
                                            'label'     => 'Сайт',
                                        ],
                                        'first_name',
                                        'last_name',
                                        'name',
                                        [
                                            'attribute' => 'date_of_birth',
                                            'format'    => 'date',
                                        ],
                                        'place',
                                        'country_id',
                                        //'photo',
                                        //'color',
                                        //'status',
                                        'sort',
                                        [
                                            'attribute' => 'created_at',
                                            'format'    => 'dateTime',
                                        ],
                                        [
                                            'attribute' => 'updated_at',
                                            'format'    => 'dateTime',
                                        ],
                                    ],
                                ],
                            );
                        }
                        catch (Throwable $e) {
                            PrintHelper::exception(
                                'DetailView-widget ', USER_PERSON_PART_LAYOUT, $e,
                            );
                        } ?>
                </div>
            </div>
        </div>
    </div>
    <div class='col-xl-6'>
        <!--####### Одна картинка #############-->
        
        
        <?= $this->render(
            '/layouts/images/_images',
            [
                'model'      => $model,
                'uploadForm' => $uploadForm,
            ],
        )
        ?>

        <!--####### Конец картинки ######################-->
        <div class='card mb-3'>
            <div class='card-header bg-light'>
                <strong>
                    Информация
                </strong>
            </div>
            <div class='card-body'>
                : <?= FormatHelper::asHtml($model->position)
                ?>
                <hr>
                Компания: <?= FormatHelper::asHtml($model->company)
                ?>
                <hr>
                О себе:<?= FormatHelper::asHtml($model->description)
                ?>
            </div>
        </div>

    </div>
</div>
