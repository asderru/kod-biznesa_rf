<?php
    
    use backend\helpers\StatusHelper;
    use core\edit\entities\Shop\Product\Product;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\db\ActiveRecord;
    
    /* @var $this yii\web\View */
    /* @var $model Product|ActiveRecord */
    
    const SEO_WIDGET_LAYOUT = '#layouts_widgets_seoWidget';
    echo PrintHelper::layout(SEO_WIDGET_LAYOUT);

?>
<!--###### Обзор ##########################-->

<div class='card mb-3'>
    <div class='card-header bg-light'>
        <strong>
            Комментарии
        </strong>
    </div>
    <?php
        foreach ($model->faqs as $faq): ?>
            <hr>
            <div class='card-body' id='card-body-text'>
                <div class='row'>
                    <div class='col-md-3'>
                        <div class='card'>
                            <div class='card-header bg-light'>
                                <strong>
                                    <?= FormatHelper::asHtml($faq->name)
                                    ?>
                                </strong>
                                <br>
                                <?= StatusHelper::statusLabel($faq->status) ?>
                            </div>
                            <div class='card-body'>
                                <?php
                                    try {
                                        echo
                                        Html::img(
                                            $faq->getImageUrl(3),
                                            [
                                                'class' => 'img-fluid',
                                            ],
                                        );
                                    }
                                    catch (Throwable $e) {
                                        PrintHelper::exception(
                                            ' faq->getImageUrl', SEO_WIDGET_LAYOUT, $e,
                                        );
                                        
                                    }
                                ?>
                            </div>
                            <div class='card-footer'>
                                <?php
                                    try {
                                        echo ButtonHelper::viewType($faq::TEXT_TYPE, $faq->id);
                                    }
                                    catch (Exception $e) {
                                        PrintHelper::exception(
                                            ' ButtonHelper_viewType', SEO_WIDGET_LAYOUT, $e,
                                        );
                                    }
                                ?>
                                <?php
                                    try {
                                        echo ButtonHelper::updateType($faq::TEXT_TYPE, $faq->id);
                                    }
                                    catch (Exception $e) {
                                        PrintHelper::exception(
                                            ' ButtonHelper_updateModel', SEO_WIDGET_LAYOUT, $e,
                                        );
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class='col-md-9'>
                        <div class="card">
                            <div class='card-header bg-light'>
                                <strong>
                                    <?= FormatHelper::asHtml($faq->title)
                                    ?>
                                </strong>
                            </div>

                            <div class='card-body'>
                                <?= FormatHelper::asHtml($faq->text)
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach; ?>
    <div class='card-footer text-end' id='card-footer-text'>
        <?= ButtonHelper::faqCreate(
            $model::TEXT_TYPE,
            $model->id,
            'Добавить комментарий',
        )
        ?>
    </div>
</div>

<div class='card mb-3'>
    <div class='card-header bg-light'>
        <strong>
            Примечания
        </strong>
    </div>
    <?php
        foreach ($model->footnotes as $footnote): ?>
            <hr>
            <div class='card-body' id='card-body-text'>
                <div class='row'>
                    <div class='col-md-3'>
                        <div class='card'>
                            <div class='card-header bg-light'>
                                <strong>
                                    <?= FormatHelper::asHtml($footnote->name)
                                    ?>
                                </strong>
                                <br>
                                <?php
                                    try {
                                        echo StatusHelper::statusLabel($footnote->status);
                                    }
                                    catch (Exception $e) {
                                        PrintHelper::exception(
                                            ' StatusHelper::statusLabel', SEO_WIDGET_LAYOUT, $e,
                                        );
                                        
                                    }
                                    if ($footnote->status < Constant::STATUS_ACTIVE) {
                                        echo ButtonHelper::activateModel($footnote);
                                    }
                                ?>
                            </div>
                            <div class='card-body'>
                                <?php
                                    try {
                                        echo
                                        Html::img(
                                            $footnote->getImageUrl(3),
                                            [
                                                'class' => 'img-fluid',
                                            ],
                                        );
                                    }
                                    catch (Throwable $e) {
                                        PrintHelper::exception(
                                            ' footnote->getImageUrl', SEO_WIDGET_LAYOUT, $e,
                                        );
                                    }
                                ?>
                            </div>
                            <div class='card-footer'>
                                <?= ButtonHelper::updateType(Constant::FOOTNOTE_TYPE, $footnote->id)
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class='col-md-9'>
                        <div class="card">
                            <div class='card-header bg-light'>
                                <strong>
                                    <?= FormatHelper::asHtml($footnote->title)
                                    ?>
                                </strong>
                            </div>

                            <div class='card-body'>
                                <?= FormatHelper::asHtml($footnote->text)
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach; ?>
    <div class='card-footer text-end' id='card-footer-text'>
        <?= ButtonHelper::footnoteCreate(
            $model::TEXT_TYPE,
            $model->id,
            'Добавить примечание',
        )
        ?>
    </div>
</div>

<div class='card mb-3'>
    <div class='card-header bg-light'>
        <strong>
            Обзоры
        </strong>
    </div>

    <div class='card-body'>
        <?php
            if ($model->reviews): ?>
                <ol>
                    <?php
                        foreach ($model->getReviews()->all() as $review) {
                            $person = $review->person;
                            if ($review) {
                                ?>
                                <li>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <strong>
                                                <?= Html::a(
                                                    $review->name,
                                                    [
                                                        '/content/review/view',
                                                        'id' => $review->id,
                                                    ],
                                                )
                                                ?>
                                            </strong>
                                            <br>
                                            <?= FormatHelper::asDescription($review, 20)
                                            ?>
                                            <br>
                                            <?= Html::a(
                                                '<i class="bi bi-pencil-fill"></i>',
                                                [
                                                    '/content/review/view',
                                                    'id' => $review->id,
                                                ],
                                                [
                                                    'class' => 'badge bg-success',
                                                ],
                                            )
                                            ?></div>
                                        <div class="col-md-4">
                                            <?= Html::img(
                                                $person->getImageUrl(3),
                                                [
                                                    'class' => 'img-fluid rounded-circle',
                                                ],
                                            ) . ' ' .
                                                Html::a(
                                                    $person->name,
                                                    [
                                                        '/user/person/view',
                                                        'id' => $review->person_id,
                                                    ],
                                                ); ?>

                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                        } ?>
                </ol>
            <?php
            endif; ?>

    </div>
    <div class="card-footer text-end">
        
        <?= ButtonHelper::reviewCreate(
            $model::TEXT_TYPE,
            $model->id,
        )
        ?>
    </div>
</div>
