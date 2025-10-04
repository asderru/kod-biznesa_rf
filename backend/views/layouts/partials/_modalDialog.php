<?php
    
    use core\edit\entities\Shop\Razdel;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\web\View;
    use yii\widgets\DetailView;
    
    /* @var $this View */
    /* @var $model Model|Razdel */
    
    $url = TypeHelper::getView($model::TEXT_TYPE, $model->id);
?>

<div class='modal-dialog modal-xl'>
    <div class='modal-content'>
        <div class='modal-header'>
            <h3 class='modal-title fs-5'>
                <?= Html::encode($model->name) ?></h3>
            <button type='button' class='btn-sm btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body'>

            <div class='card'>

                <div class='row mb-3'>

                    <div class='col-sm-6'>

                        <div class='card mb-3'>
                            <div class='card-body'>

                                <div class='table-responsive'>
                                    
                                    <?php
                                        try {
                                            echo DetailView::widget(
                                                [
                                                    'model'      => $model,
                                                    'attributes' => [
                                                        [
                                                            'attribute' => 'site.name',
                                                            'label'     => 'Сайт',
                                                            'visible'   =>
                                                                ParametrHelper::isServer(),
                                                        ],
                                                        'name',
                                                        'slug',
                                                        'title',
                                                        'status',
                                                    ],
                                                ],
                                            );
                                        }
                                        catch (Throwable $e) {
                                            PrintHelper::exception(
                                                'DetailView-widget ', '#layouts_partials_modalDialog', $e,
                                            );
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class='col-sm-6'>
                        <div class='card mb-3'>
                            <div class='card-body'>
                                <?php
                                    if ($model->hasAttribute('photo')) {
                                        if ($model->photo) { ?>
                                            
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
                                                    'model->getImageUrl ', '#layouts_partials_modalDialog', $e,
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


            </div>
        </div>
        <div class='modal-footer'>
            <?= Html::a('Открыть в новом окне', $url, [
                'class' => 'btn btn-outline-secondary', 'target' =>
                    '_blank',
            ]) ?>
        </div>
    </div>
</div>
