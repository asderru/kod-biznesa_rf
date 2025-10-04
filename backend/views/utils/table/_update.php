<?php
    
    use backend\helpers\SelectHelper;
    use core\edit\forms\Utils\Table\TableForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model TableForm */
    /* @var $form ActiveForm */
    
    const UTILS_TABLE_UPDATE_LAYOUT = '#views_utils_table_update.php';
    echo PrintHelper::layout(UTILS_TABLE_UPDATE_LAYOUT);
?>

<div class='card-body'>
    <div class='row'>
        <div class='col-xl-6'>
            <div class='card'>
                <div class='card-body'>
                    
                    <?php
                        echo
                        $form->field($model, 'name')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        ); ?>
                    
                    <?php
                        echo
                        $form->field(
                            $model, 'description',
                            [
                                'inputOptions' => [
                                    'id' => 'description-edit-area',
                                ],
                            ],
                        )
                             ->textarea()
                        ; ?>
                    
                    <?php
                        try {
                            echo
                            SelectHelper::getSites($form, $model, true);
                        }
                        catch (Exception $e) {
                            PrintHelper::exception(
                                'Выбор сайта ', UTILS_TABLE_UPDATE_LAYOUT, $e,
                            );
                        } ?>

                </div>
                <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                    <?= ButtonHelper::submit()
                    ?>

                </div>
            </div>
        </div>

        <div class='col-xl-6'>
            <div class='card'>
                <div class='card-body'>
                    <?php
                        echo
                        $form->field(
                            $model, 'text',
                            [
                                'inputOptions' => [
                                    'id' => 'text-edit-area',
                                ],
                            ],
                        )
                             ->textarea()
                        ; ?>

                </div>
            </div>
        </div>

    </div>

</div>
