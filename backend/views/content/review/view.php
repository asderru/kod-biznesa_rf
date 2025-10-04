<?php

    use backend\widgets\PagerWidget;
    use core\edit\entities\Content\Review;
    use core\edit\entities\Shop\Product\Product;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\helpers\types\TypeUrlHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;

    /** @var yii\web\View $this */
    /** @var Review $model */
    /** @var Product $parent */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_review_view';
    
    $this->title = $model->name;
?>
    <div class="card">
    <div class='card-body'>
        <div class='row mb-3'>

            <div class='col-xl-6'>

                <div class='card mb-3 h-100'>

                    <div class='card-header bg-light'>
                        <strong>
                            Информация
                        </strong>
                    </div>

                    <div class='card-body'>

                        <div class='table-responsive'>
                            <?php
                                    echo
                                    DetailView::widget([
                                        'model'      => $model,
                                        'attributes' => [
                                            'id',
                                            'name',
                                            'vote',
                                            [
                                                'attribute' => 'person.name',
                                                'label'     => 'Профиль',
                                            ],
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
                                    ]);
                                    ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class='col-xl-6'>


                <div class='card mb-3 h-100'>

                    <div class='card-header bg-light'>
                        <strong>
                            Текст
                        </strong>
                    </div>

                    <div class='card-body'>
                        <?= FormatHelper::asHtml($model->text)
                        ?>
                    </div>

                    <div class="card-footer">
                        <?= ButtonHelper::update($model->id, 'Редактировать') ?>
                    </div>
                </div>
            </div>


        </div>
    </div>

    </div>
