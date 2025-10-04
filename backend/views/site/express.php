<?php
    
    use backend\widgets\pages\BookWidget;
    use backend\widgets\pages\BrandWidget;
    use backend\widgets\pages\ChapterWidget;
    use backend\widgets\pages\HistoryWidget;
    use backend\widgets\pages\NewsWidget;
    use backend\widgets\pages\PageWidget;
    use backend\widgets\pages\ProductWidget;
    use backend\widgets\pages\RazdelWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /** @var View $this */
    /** @var bool $superadmin */
    /** @var string $actionId */
    /** @var bool $active */
    /** @var bool $isAlone */
    
    const EXPRESS_LAYOUT = '#site_express';
    echo PrintHelper::layout(EXPRESS_LAYOUT);
    
    $this->title = 'Экспресс-правка';
    
    $appType  = Yii::$app->params[('appType')];
    $siteMode = Yii::$app->params[('siteMode')];

?>

<div class="site-index py-4">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Shop'n'SEO Express Edit</h1>
    </div>

    <div class="body-content">

        <!--###### Razdels ##########################################################-->

        <div class='card mb-4'>

            <div
                    class='card-header bg-light-subtle d-flex justify-content-between'
            >

                <div class='h5'>
                    Разделы
                    - <?= Html::encode(Constant::RAZDEL_LABEL)
                    ?>
                </div>

                <div>
                    <?= ButtonHelper::collapse('подробнее', '#razdelButtons')
                    ?>
                </div>
            </div>

            <div class='card-body collapse' id='razdelButtons'>

                <div class="row">

                    <div class='col-lg-4  mb-3'>
                        <div class='card h-100 table-responsive'>
                            <?php
                                try {
                                    echo
                                    RazdelWidget::widget(
                                        [
                                            'url'     => '/shop/razdel/',
                                            'long'    => false,
                                            'isAlone' => $isAlone,
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        $actionId, 'RazdelWidget ' . EXPRESS_LAYOUT, $e,
                                    );
                                } ?>
                        </div>
                    </div>

                    <div class='col-lg-8 mb-3'>
                        <div class='card h-100'>
                            <div class="card-header bg-body-secondary">
                                <h5>Последняя редакция</h5>
                            </div>
                            <div class='card-body table-responsive'>
                                
                                <?php
                                    try {
                                        echo
                                        HistoryWidget::widget(
                                            [
                                                'typeId' => Constant::RAZDEL_TYPE,
                                            ],
                                        );
                                    }
                                    catch (Throwable $e) {
                                        PrintHelper::exception(
                                            
                                            $actionId, 'HistoryWidget Razdel ' . EXPRESS_LAYOUT, $e,
                                        );
                                    } ?>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class='card-body bg-light-subtle'>
                <?= ButtonHelper::createType(Constant::RAZDEL_TYPE, null, 'Добавить раздел') ?>
                <?= ButtonHelper::viewType(Constant::RAZDEL_TYPE) ?>
                <?= ButtonHelper::expressType(Constant::RAZDEL_TYPE) ?>
            </div>
        </div>

        <!--###### Products #################################################-->

        <div class='card mb-4'>

            <div
                    class='card-header bg-light-subtle d-flex justify-content-between'
            >

                <div class='h5'>
                    Товары/услуги
                    - <?= Html::encode(Constant::PRODUCT_LABEL)
                    ?>
                </div>

                <div>
                    <?= ButtonHelper::collapse(
                        'подробнее',
                        '#productButtons',
                    )
                    ?>
                </div>
            </div>

            <div class='card-body collapse' id='productButtons'>

                <div class='row'>
                    <div class='col-lg-4  mb-3'>
                        <div class='card h-100 table-responsive'>
                            <?php
                                try {
                                    echo
                                    ProductWidget::widget(
                                        [
                                            'url'     => '/shop/product/',
                                            'long'    => false,
                                            'isAlone' => $isAlone,
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        $actionId, 'ProductWidget ' . EXPRESS_LAYOUT, $e,
                                    );
                                } ?>
                        </div>
                    </div>
                    <div class='col-lg-8 mb-3'>
                        <div class='card h-100'>
                            <div class="card-header bg-body-secondary">
                                <h5>Последняя редакция</h5>
                            </div>
                            <div class="card-body table-responsive">
                                
                                <?php
                                    try {
                                        echo
                                        HistoryWidget::widget(
                                            [
                                                'typeId' =>
                                                    Constant::PRODUCT_TYPE,
                                            ],
                                        );
                                    }
                                    catch (Throwable $e) {
                                        PrintHelper::exception(
                                            $actionId, 'HistoryWidget Product ' . EXPRESS_LAYOUT, $e,
                                        );
                                    } ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-body bg-light-subtle">
                <?= ButtonHelper::createType(Constant::PRODUCT_TYPE, null, 'Добавить товар/услугу') ?>
                <?= ButtonHelper::viewType(Constant::PRODUCT_TYPE) ?>
                <?= ButtonHelper::expressType(Constant::PRODUCT_TYPE) ?>
            </div>
        </div>

        <!--###### Brands ###################################################-->

        <div class='card mb-4'>

            <div
                    class='card-header bg-light-subtle d-flex justify-content-between'
            >

                <div class='h5'>
                    Бренды - <?= Html::encode(Constant::BRAND_LABEL)
                    ?>
                </div>

                <div>
                    <?= ButtonHelper::collapse(
                        'подробнее',
                        '#brandButtons',
                    )
                    ?>
                </div>
            </div>

            <div class='card-body collapse' id='brandButtons'>

                <div class='row'>
                    <div class='col-lg-4  mb-3'>
                        <div class='card h-100 table-responsive'>
                            <?php
                                try {
                                    echo
                                    BrandWidget::widget(
                                        [
                                            'url'     => '/shop/brand/',
                                            'long'    => false,
                                            'isAlone' => $isAlone,
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        $actionId, 'BrandWidget ' . EXPRESS_LAYOUT, $e,
                                    );
                                } ?>
                        </div>
                    </div>
                    <div class='col-lg-8 mb-3'>
                        <div class='card h-100'>
                            <div class="card-header bg-body-secondary">
                                <h5>Последняя редакция</h5>
                            </div>
                            <div class="card-body table-responsive">
                                
                                <?php
                                    try {
                                        echo
                                        HistoryWidget::widget(
                                            [
                                                'typeId' =>
                                                    Constant::BRAND_TYPE,
                                            ],
                                        );
                                    }
                                    catch (Throwable $e) {
                                        PrintHelper::exception(
                                            $actionId, 'HistoryWidget Brand ' . EXPRESS_LAYOUT, $e,
                                        );
                                    } ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-body bg-light-subtle">
                <?= ButtonHelper::createType(Constant::BRAND_TYPE, null, 'Добавить бренд') ?>
                <?= ButtonHelper::viewType(Constant::BRAND_TYPE) ?>
                <?= ButtonHelper::expressType(Constant::BRAND_TYPE) ?>
            </div>
        </div>

        <!--###### Pages #########################################################-->

        <div class='card mb-4'>

            <div
                    class='card-header bg-light-subtle d-flex justify-content-between'
            >

                <div class='h5'>
                    Страницы - <?= Html::encode(Constant::PAGE_LABEL)
                    ?>
                </div>

                <div>
                    <?= ButtonHelper::collapse(
                        'подробнее',
                        '#pageButtons',
                    )
                    ?>
                </div>
            </div>

            <div class='card-body collapse' id='pageButtons'>

                <div class='row'>
                    <div class='col-lg-4  mb-3'>
                        <div class='card h-100 table-responsive'>
                            <?php
                                try {
                                    echo
                                    PageWidget::widget(
                                        [
                                            'url'     => '/content/page/',
                                            'long'    => false,
                                            'isAlone' => $isAlone,
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        $actionId, 'PageWidget ' . EXPRESS_LAYOUT, $e,
                                    );
                                } ?>
                        </div>
                    </div>
                    <div class='col-lg-8 mb-3'>
                        <div class='card h-100'>
                            <div class="card-header bg-body-secondary">
                                <h5>Последняя редакция</h5>
                            </div>
                            <div class="card-body table-responsive">
                                <?php
                                    try {
                                        echo
                                        HistoryWidget::widget(
                                            [
                                                'typeId' => Constant::PAGE_TYPE,
                                            ],
                                        );
                                    }
                                    catch (Throwable $e) {
                                        PrintHelper::exception(
                                            $actionId, 'HistoryWidget Page ' . EXPRESS_LAYOUT, $e,
                                        );
                                    } ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-body bg-light-subtle">
                <?= ButtonHelper::createType(Constant::PAGE_TYPE, null, 'Добавить страницу') ?>
                <?= ButtonHelper::viewType(Constant::PAGE_TYPE) ?>
                <?= ButtonHelper::expressType(Constant::PAGE_TYPE) ?>
            </div>
        </div>

        <!--###### News #####################################################-->

        <div class='card mb-4'>

            <div
                    class='card-header bg-light-subtle d-flex justify-content-between'
            >

                <div class='h5'>
                    Новости - <?= Html::encode(Constant::NEWS_LABEL)
                    ?>
                </div>

                <div>
                    <?= ButtonHelper::collapse(
                        'подробнее',
                        '#newsButtons',
                    )
                    ?>
                </div>
            </div>

            <div class='card-body collapse' id='newsButtons'>

                <div class="row">
                    <div class="col-lg-4  mb-3">
                        <div class='card h-100'>
                            <div class="card-header bg-body-secondary">
                                <h4><?= Html::encode(Constant::NEWS_LABEL)
                                    ?></h4>
                            </div>
                            <div class="card-body">
                                <p>

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class='col-lg-8 mb-3'>
                        <div class='card h-100 table-responsive'>
                            
                            <?php
                                try {
                                    echo
                                    NewsWidget::widget(
                                        [
                                            'long' => false,
                                            'url'  => '/express/news/',
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        $actionId, 'NewsWidget ' . EXPRESS_LAYOUT, $e,
                                    );
                                } ?>

                        </div>
                    </div>
                </div>

            </div>

            <div class="card-body bg-light-subtle">
                <?= ButtonHelper::createType(Constant::NEWS_TYPE, null, 'Добавить новость') ?>
                <?= ButtonHelper::viewType(Constant::NEWS_TYPE) ?>
                <?= ButtonHelper::expressType(Constant::NEWS_TYPE) ?>
            </div>
        </div>


        <!--###### Books  ###################################################-->

        <div class='card mb-4'>

            <div class='card-header bg-light-subtle d-flex justify-content-between'>

                <div class='h5'>
                    Книги - <?= Html::encode(Constant::BOOK_LABEL)
                    ?>
                </div>

                <div>
                    <?= ButtonHelper::collapse(
                        'подробнее',
                        '#bookButtons',
                    )
                    ?>
                </div>
            </div>

            <div class='card-body collapse' id='bookButtons'>

                <div class="row">
                    <div class="col-lg-4  mb-3">
                        <div class='card h-100 table-responsive'>
                            <?php
                                try {
                                    echo
                                    BookWidget::widget(
                                        [
                                            'url'     => '/library/book/',
                                            'long'    => false,
                                            'isAlone' => $isAlone,
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        $actionId, 'BookWidget ' . EXPRESS_LAYOUT, $e,
                                    );
                                } ?>
                        </div>
                    </div>
                    <div class='col-lg-8 mb-3'>
                        <div class='card h-100'>
                            <div class="card-header bg-body-secondary">
                                <h5>Последняя редакция</h5>
                            </div>
                            <div class='card-body table-responsive'>
                                
                                <?php
                                    try {
                                        echo
                                        HistoryWidget::widget(
                                            [
                                                'typeId' =>
                                                    Constant::BOOK_TYPE,
                                            ],
                                        );
                                    }
                                    catch (Throwable $e) {
                                        PrintHelper::exception(
                                            $actionId, 'HistoryWidget Book ' . EXPRESS_LAYOUT, $e,
                                        );
                                    } ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-body bg-light-subtle">
                <?= ButtonHelper::createType(Constant::BOOK_TYPE, null, 'Добавить книгу') ?>
                <?= ButtonHelper::viewType(Constant::BOOK_TYPE) ?>
                <?= ButtonHelper::expressType(Constant::BOOK_TYPE) ?>
            </div>
        </div>


        <!--###### Chapters #################################################-->

        <div class='card mb-4'>

            <div class='card-header bg-light-subtle d-flex justify-content-between'>

                <div class='h5'>
                    Тексты - <?= Html::encode(Constant::CHAPTER_LABEL)
                    ?>
                </div>

                <div>
                    <?= ButtonHelper::collapse(
                        'подробнее',
                        '#chapterButtons',
                    )
                    ?>
                </div>
            </div>

            <div class='card-body collapse' id='chapterButtons'>

                <div class="row">
                    <div class='col-lg-4  mb-3'>
                        <div class='card h-100 table-responsive'>
                            <?php
                                try {
                                    echo
                                    ChapterWidget::widget(
                                        [
                                            'url'     => '/library/chapter/',
                                            'long'    => false,
                                            'isAlone' => $isAlone,
                                        ],
                                    );
                                }
                                catch (Throwable $e) {
                                    PrintHelper::exception(
                                        $actionId, 'ChapterWidget ' . EXPRESS_LAYOUT, $e,
                                    );
                                } ?>
                        </div>
                    </div>
                    <div class='col-lg-8 mb-3'>
                        <div class='card h-100'>
                            <div class="card-header bg-body-secondary">
                                <h5>Последняя редакция</h5>
                            </div>
                            <div class='card-body table-responsive'>
                                
                                <?php
                                    try {
                                        echo
                                        HistoryWidget::widget(
                                            [
                                                'typeId' =>
                                                    Constant::CHAPTER_TYPE,
                                            ],
                                        );
                                    }
                                    catch (Throwable $e) {
                                        PrintHelper::exception(
                                            $actionId, 'HistoryWidget Chapter ' . EXPRESS_LAYOUT, $e,
                                        );
                                    } ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-body bg-light-subtle">
                <?= ButtonHelper::createType(Constant::CHAPTER_TYPE, null, 'Добавить текст') ?>
                <?= ButtonHelper::viewType(Constant::CHAPTER_TYPE) ?>
                <?= ButtonHelper::expressType(Constant::CHAPTER_TYPE) ?>

            </div>
        </div>

    </div>

</div>
