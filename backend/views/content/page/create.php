<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use backend\widgets\select\SelectSiteWidget;
    use backend\widgets\select\SelectTagWidget;
    use core\edit\entities\Admin\Information;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ImageHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Content\PageForm */
    /* @var $page core\edit\entities\Content\Page */
    /* @var $pages array */
    /* @var $site Information */
    /* @var $form yii\bootstrap5\ActiveForm */
    /* @var $isAlone bool */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_page_create_';
    
    $this->title = 'Добавить страницу';
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::PAGE_TYPE);
    $this->params['breadcrumbs'][] = $this->title;
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    );
    
    $form           = ActiveForm::begin(
        [
            'options'     => [
                'class' => 'active__form',
            ],
            'fieldConfig' => [
                'errorOptions' => [
                    'encode' => false,
                    'class'  => 'help-block',
                ],
            ],
        ],
    );?>
  <div class='card'>
    <?= $this->render(
        '/layouts/tops/_createHeader',
        [
            'title'    => $this->title,
            'textType' => $textType,
        ],
    );
        if ($model->hasErrors()): ?>
            <div class="container alert alert-danger p-4">
                <?= Html::errorSummary($model) ?>
            </div>
        <?php
        endif; ?>
    <div class='card-body'>

            <div class='row mb-3'>

                <div class='col-xl-6'>

                    <div class='card h-100 '>

                        <div class='card-header bg-light '>
                            <strong>Новая страница</strong>
                        </div>

                        <div class="card-body">
                            <?= $this->render(
                                '../../seo/type/_slugGenerator',
                                [
                                    'model' => $model,
                                    'form'  => $form,
                                ],
                            )
                            ?>
                            
                            <?= $form->field($model, 'title')->textarea(['rows' => 3]) ?>

                        </div>
                        
                        <?= $this->render(
                            '/layouts/partials/_brands',
                            [
                                'form'  => $form,
                                'model' => $model->brands,
                            ],
                        )
                        ?>

                        <div class='card-footer d-flex justify-content-between px-4'>
                            <?= $form->field($model, 'rating')
                                     ->textInput([
                                         'type'  => 'number',
                                         'min'   => 1,
                                         'max'   => 100,
                                         'value' => 10,
                                     ])
                                     ->label('Рейтинг SEO от 1 до 100') ?>
                            <?= SelectHelper::status($form, $model) ?>
                        </div>
                    </div>
                </div>
                <div class='col-xl-6'>

                    <div class='card h-100'>
                        
                        <?php
                            if (!$page):
                            echo SelectSiteWidget::widget(
                                [
                                    'form'     => $form,
                                    'model'    => $model,
                                    'textType' => $textType,
                                ],
                            );
                            else:
                        ?>

                        <div class='card-header bg-light'>
                            Родительская страница:

                            <strong>
                                <?= Html::a(
                                    Html::encode($page->name),
                                    [
                                        'content/page/view',
                                        'id' => $page->id,
                                    ],
                                ) ?>
                            </strong>
                        </div>
                        <div class="card-body">

                            <div class='row'>
                                <div class='col-md-4'>
                                    <?php
                                        if ($page->photo):
                                            ?>
                                            <img
                                                    src="<?= ImageHelper::getModelImageSource($page, 3)?>"
                                                    class="img-fluid rounded mb-3"
                                                    alt="<?= Html::encode($page->name) ?>">
                                            <hr>
                                        <?php
                                        endif; ?>
                                    <?= Html::a(
                                        'Открыть в новом окне',
                                        [
                                            '/content/page/view',
                                            'id' => $page->id,
                                        ],
                                        [
                                            'class'  => 'btn btn-sm btn-primary',
                                            'target' => '_blank',
                                        ],
                                    )
                                    ?>
                                    <?= Html::a(
                                        'Редактировать в новом окне',
                                        [
                                            '/content/page/update',
                                            'id' => $page->id,
                                        ],
                                        [
                                            'class'  => 'btn btn-sm btn-success',
                                            'target' => '_blank',
                                        ],
                                    )
                                    ?>
                                </div>
                                <div class="col-md-8">
                                    <dl class="row">
                                        <dt class="col-sm-4">ID страницы:</dt>
                                        <dd class="col-sm-8"><?= $page->id ?></dd>
                                        <dt class='col-sm-4'>Заголовок:</dt>
                                        <dd class='col-sm-8'><?= Html::encode($page->title) ?></dd>
                                        <dt class='col-sm-4'>Описание:</dt>
                                        <dd class='col-sm-8'><?= FormatHelper::asHtml($page->description) ?></dd>
                                    </dl>
                                </div>
                            </div>

                        </div>
                        <?php
                            echo $form->field($model, 'site_id')->hiddenInput(['value' => $page->site_id])->label(false);
                            echo $form->field($model, 'parentId')->hiddenInput(['value' => $page->id])->label(false);
                        ?>

                    </div>
                    
                    
                    <?php
                        endif; ?>
                    
                    <?= $this->render(
                        '/layouts/partials/_description',
                        [
                            'form'  => $form,
                            'model' => $model,
                                            'textType' => $textType,
                        ],
                    ) ?>
                    
                    
                    <?php
                        try {
                            echo SelectTagWidget::widget(
                                [
                                    'form'  => $form,
                                    'model' => $model->tags,
                                    'site'  => $site,
                                ],
                            );
                        }
                        catch (Throwable $e) {
                            PrintHelper::exception(
                                'SelectTagWidget', LAYOUT_ID, $e,
                            );
                            
                        }
                    ?>

                </div>

            </div>

        </div>
        <div class='card-header bg-light'>
            <strong>
                Текст страницы
            </strong>
        </div>
        <div class='card-body'>
            <?= $form->field(
                $model, 'text',
                [
                    'inputOptions' => [
                        'id' => 'text-edit-area',
                    ],
                ],
            )
                     ->textarea()
                     ->label(false) ?>

        </div>

        <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::submit()
            ?>
        </div>

        <div class='card-header bg-light'>
            <strong>
                Карточка контента
            </strong>
        </div>
        <div class='card-body'>
            <?= $this->render(
                '/content/card/_form_card_create',
                [
                    'form'     => $form,
                    'model'    => $model->card,
                    'parentId' => null,
                    'textType' => $textType,
                ],
            )
            ?>
        </div>
    </div>
<?php
    ActiveForm::end();
    TinyHelper::getText();
