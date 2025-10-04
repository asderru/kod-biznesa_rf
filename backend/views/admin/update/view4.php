<?php
    
    use backend\helpers\StatusHelper;
    use backend\helpers\UrlHelper;
    use backend\widgets\PagerEditWidget;
    use brussens\yii2\extensions\trumbowyg\TrumbowygWidget;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Content\Review;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Seo\Footnote;
    use core\edit\entities\Seo\Material;
    use core\edit\entities\Seo\News;
    use core\edit\entities\Shop\Brand;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\edit\entities\Tools\Draft;
    use core\edit\entities\Utils\Gallery;
    use core\edit\forms\ModelEditForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    
    /* @var $this yii\web\View */
    /* @var $parent Article|Author|Book|Brand|Category|Chapter|Draft|Group|Material|News|Page|Post|Product|Razdel|Section| */
    /* @var $model ModelEditForm */
    /* @var $footnotes Footnote[] */
    /* @var $faqs Faq[] */
    /* @var $reviews Review[] */
    /* @var $galleries Gallery[] */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $parentTextType int */
    /* @var $parentId int */
    /* @var $textType int */
    /* @var $editId int */
    
    const LAYOUT_ID = '#admin_update_view4';
    
    $status     = $parent->status;
    $editorName = 'Trumbowyg';
    $editUrl    = $parent->getEditPath();
    $count      = strlen($parent->text);
    
    $this->title = 'ред. ' . $editorName;
    
    
    $parentIndexName = 'Index';
    $parentViewUrl = TypeHelper::getView($parent::TEXT_TYPE, $parent->id);
    $parentIndex = TypeHelper::getView($parent::TEXT_TYPE);
    $parentIndexName = TypeHelper::getLabel($parent::TEXT_TYPE);
    
    $this->params['breadcrumbs'][] = [
        'label' => $parentIndexName,
        'url' => [$parentIndex],
    ];
    
    $this->params['breadcrumbs'][] = [
        'label' => $parent->name,
        'url'   => [
            $parentViewUrl,
        ],
    ];
    $this->params['breadcrumbs'][] = 'ред. ' . $editorName;
    
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
    
    try {
        echo
        PagerEditWidget::widget(
            [
                'model'     => $parent,
                'editId'    => 0,
                'faqs'      => $faqs,
                'footnotes' => $footnotes,
                'reviews'   => $reviews,
                'galleries' => $galleries,
            ],
        );
    }
    catch (Exception|Throwable $e) {
        PrintHelper::exception(
            $actionId, 'PagerEditWidget - ' . LAYOUT_ID, $e,
        );
    }
    echo '<div class="card">';
    
    echo Html::tag(
        'div',
        $this->render('/layouts/tops/_expressBreadcrumbs'),
        ['class' => 'px-2 bg-body-secondary'],
    );
    echo Html::tag(
        'div',
        $this->render('/layouts/tops/_messages'),
        ['class' => 'alert-area'],
    );
    
    $form = ActiveForm::begin(
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
    );
?>

<div class='card-header bg-light d-flex justify-content-between'>
    <div>
        <h4>
            <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($parent->name)
            ?>
        </h4>
        <small>
            <?= StatusHelper::statusBadgeLabel($parent->status) ?>
        </small>
    </div>

    <div class="text-center"> редактор: <strong><?= $editorName ?></strong>
    </div>

    <div class='btn-group-sm d-grid gap-2 d-sm-block'>
        
        <?php
            echo ButtonHelper::submitAction('return', 'Сохранить', 'btn btn-sm btn-primary');
            
            echo ButtonHelper::submitAction('next', 'Сохранить и перейти на след.', 'btn btn-sm btn-success');
            
            echo ButtonHelper::collapse()
        ?>

        <br>
        
        <?php
            echo ButtonHelper::viewType($parentTextType, $parentId);
            
            echo ButtonHelper::updateType($parentTextType, $parentId);
            
            echo ButtonHelper::expressType($parentTextType, $parentId);
            
            echo Html::a(
                '<i class="bi bi-body-text"></i>',
                [
                    'update',
                    'textType' => $parentTextType,
                    'id'       => $parentId,
                    'editId'   => $editId,
                ],
                [
                    'class' => 'btn btn-sm btn-outline-dark',
                ],
            );
        ?>

    </div>
</div>
<div class='card-body mb-2 collapse' id='collapseButtons'>
    <div class="  d-flex justify-content-between">
        <div>
            категория: <strong><?= $parentIndexName ?></strong><br>
            название: <strong><?= ButtonHelper::updateType($parent::TEXT_TYPE, $parent->id); ?></strong><br>
            заголовок: <strong><?= $parent->title ?></strong><br>
        </div>
        <div>
            <?= ButtonHelper::viewType($parent::TEXT_TYPE, null, 'Стандартная панель ' . $parentIndexName); ?>
            <?= ButtonHelper::expressType($parent::TEXT_TYPE, null, 'Экспресс-панель ' . $parentIndexName); ?>
        </div>
    </div>
    <?= ButtonHelper::editPanelsSet($parent, $editId, 2); ?>
</div>

<div class="card-body">
    <?php
        try {
            echo
            $form->field($model, 'text')->widget(TrumbowygWidget::className());
        }
        catch (Exception $e) {
            PrintHelper::exception(
                $actionId, 'TrumbowygWidget' . LAYOUT_ID, $e,
            );
        }
    ?>
</div>
<div class='card-header bg-light d-flex justify-content-between'>
    <div>
        <?= ButtonHelper::submitAction('return', 'Сохранить')
        ?>
        <?= ButtonHelper::submitAction('next', 'Сохранить и перейти на след.')
        ?>
    </div>

    <div class='btn-group-sm d-grid gap-2 d-sm-block'>
        <?php
            if ($status < Constant::STATUS_NEW): ?>
                <?= StatusHelper::statusLabel($status) ?>
            <?php
            endif; ?>
        
        <?php
            if ($status >= Constant::STATUS_NEW): ?>
                <?= StatusHelper::marketStatusLabel($status) ?>
            <?php
            endif; ?>
        <button
                onclick='triggerCopy()'
                id='copy-button'
                class='btn btn-sm btn-outline-dark'
        >Скопировать текст в память браузера
        </button>
    </div>

</div>

<div class='card-body mb-2 btn-group-sm gap-2'>


    <div class="row">

        <div class='col-xl-6'>

            <div class='card h-100'>

                <div class='card-body bg-light'>
                    
                    <?= $form->field($model, 'name')
                             ->textInput(
                                 [
                                     'maxlength' => true,
                                 ],
                             )
                             ->label()
                    ?>
                    
                    <?= $form->field($model, 'title')
                             ->textarea(
                                 [
                                     'rows' => 3,
                                 ],
                             )
                             ->label()
                    ?>
                    
                    <?= Html::activeHiddenInput(
                        $model, 'site_id',
                        [
                            'value' => $parent->site_id,
                        ],
                    )
                    ?>

                </div>

                <div class="card-footer">
                    <small> Модель: </small> <?= Html::tag(
                        'strong',
                        Html::encode($parent->name),
                    )
                    ?>.

                    <br>

                    <small> Сайт: </small> <?= Html::tag(
                        'strong',
                        Html::encode($parent->site->name),
                    )
                    ?>.

                    <hr>

                    <small>
                        ID #<strong><?= Html::encode($parent->id)
                            ?></strong>
                        / Контент - <strong><?= Html::encode($parent->content_id)
                            ?>
                        </strong>.
                    </small>
                    <br>
                    Ссылка - <?= Html::a(
                        $parent->link,
                        $parent->link,
                        [
                            'target' => '_blank',
                        ],
                    )
                    ?>.
                    <br>
                    <?=
                        UrlHelper::checkUrl($parent->status, $parent->link)
                    ?>
                </div>

            </div>

        </div>

        <div class='col-xl-6'>
            <div class='card h-100'>
                <div class='card-header bg-light'>
                    <strong>
                        Краткое описание
                    </strong>
                </div>

                <div class='card-body bg-light'>
                    <?php
                        try {
                            echo
                            $form->field($model, 'description')->widget(TrumbowygWidget::className(), [
                                'clientOptions' => [
                                    'btns' => [
                                        ['viewHTML'],
                                        ['undo', 'redo'],
                                        ['formatting'],
                                        ['strong', 'em', 'del'],
                                        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                                        ['unorderedList', 'orderedList'],
                                        ['removeformat'],
                                        ['fullscreen'],
                                    ],
                                ],
                            ]);
                        }
                        catch (Exception $e) {
                            PrintHelper::exception(
                                $actionId, 'TrumbowygWidget' . LAYOUT_ID, $e,
                            );
                        }
                    ?>
                </div>

            </div>

        </div>

    </div>

</div>

<div class='card-footer'>
    <?= ButtonHelper::editPanelsSet($parent); ?>
</div>

<div class='card-header bg-light'>
    <strong>
        Метки
    </strong>
</div>
<div class='card-body'>
    
    <?= $form->field($model->tags, 'textNew')
             ->label('Добавить новые метки, через запятую:')
    ?>
    <hr>
    
    <?= $form->field($model->tags, 'existing')
             ->inline()
             ->checkboxList($model->tags::tagsList($parent->site_id))
             ->label('Отметить:') ?>
</div>

<?php
    ActiveForm::end();
    
    echo '<div class="card">';
?>
<!--###### Modal ############################################################-->
<div
        class='modal fade' id='currentModal' tabindex='-1'
        aria-labelledby='currentModalLabel' aria-hidden='true'
>
    <?=
        $this->render(
            '../../../views/layouts/partials/_modalDialog',
            [
                'model' => $parent,
            ],
        )
    ?>
</div>
