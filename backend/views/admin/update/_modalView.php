<?php
    
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Shop\Razdel */
    
    
    const MODAL_LAYOUT = '#layouts_partials_modalDialog';

?>

<div class='row mb-3'>

    <div class='col-sm-6'>

        <div class='card mb-3'>
            <div class='card-body'>

                <div class='table-responsive'>

                </div>
            </div>
        </div>

    </div>

    <div class='col-sm-6'>
        <div class='card mb-3'>
            <div class='card-body'>
                <?php
                    if ($model->hasProperty('photo')) {
                        if ($model->photo) { ?>
                            <!--####### Одна картинка #############-->
                            
                            <?php
                            try {
                                echo Html::img(
                                    $model->getImageUrl(6),
                                    [
                                        'class' => 'img-fluid',
                                    ],
                                );
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'model->getImageUrl ', '#layouts_partials_modalView', $e,
                                );
                            }
                            ?>
                            <?php
                        }
                        else {
                            // Столбец `photo` существует, но значение не заполнено
                            echo 'Фото не загружено.';
                        }
                    }
                    else {
                        // Столбец `photo` отсутствует в модели
                        echo 'Данная запись не может иметь фото.';
                    }
                ?>
            </div>
        </div>
        <!--####### Конец картинки ######################-->
    </div>

    <div class='card-body'>
        <p>
            <strong>
                Описание:
            </strong>
        </p>
        
        <?= FormatHelper::asHtml($model->description)
        ?>
    </div>

</div>


<?php
    if (isset($model->text)): ?>
        <?=
        $this->render(
            '/layouts/templates/_textWidget',
            [
                'model' => $model,
            ],
        )
        ?>
    <?php
    endif; ?>
