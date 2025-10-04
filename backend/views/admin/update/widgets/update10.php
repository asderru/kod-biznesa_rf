<?php
    
    use backend\tools\TinyHelper;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Note;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Content\Review;
    use core\edit\entities\Content\Tag;
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
    use core\edit\entities\Utils\Gallery;
    use core\edit\forms\ModelEditForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $this yii\web\View */
    /* @var $parent Article|Author|Book|Brand|Category|Chapter|Group|Material|News|Page|Post|Product|Razdel|Section| */
    /* @var $prevModel Article|Author|Book|Brand|Category|Chapter|Group|Material|News|Page|Post|Product|Razdel|Section| */
    /* @var $siblings array */
    /* @var $model ModelEditForm */
    /* @var $footnotes Footnote[] */
    /* @var $faqs Faq[] */
    /* @var $reviews Review[] */
    /* @var $galleries Gallery[] */
    /* @var $notes Note[] */
    /* @var $tags Tag[] */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $parentTextType int */
    /* @var $parentId int */
    /* @var $textType int */
    /* @var $editId int */
    
    const LAYOUT_ID = '#admin_update_update0';
    
    $status     = $parent->status;
    $editorName = 'TinyMCE';
    $editUrl    = $parent->getEditPath();
    $count      = strlen($parent->text);
    
    $this->title = 'ред. ' . $editorName;
    
    $parentIndexName = 'Index';
    $parentViewUrl = TypeHelper::getView($parent::TEXT_TYPE, $parent->id);
    $parentIndex = TypeHelper::getView($parent::TEXT_TYPE);
    $parentIndexName = TypeHelper::getLabel($parent::TEXT_TYPE);
    
    $this->params['breadcrumbs'][] = [
        'label' => $parentIndexName,
        'url'   => [$parentIndex],
    ];
    
    $this->params['breadcrumbs'][] = [
        'label' => $parent->name,
        'url'   => [
            $parentViewUrl,
        ],
    ];
    $this->params['breadcrumbs'][] = 'ред. ' . $editorName;
    
    echo $this->render(
        '/layouts/tops/_breadcrumbs',
    );
    
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


?>
<div class="row">
    <div class="col-lg-9">

        <div class='card'>
            <div class='card-header bg-light d-flex justify-content-between'>
                <div>
                    категория: <strong><?= $parentIndexName ?></strong><br>
                    название: <strong><?= ButtonHelper::updateType($parent::TEXT_TYPE, $parent->id); ?></strong><br>
                    заголовок: <strong><?= $parent->title ?></strong><br>
                </div>
                <div class="text-center"> редактор: <strong><?= $editorName ?></strong><br>
                    <button class='btn btn-sm btn-outline-info' type='button' data-bs-toggle='offcanvas'
                            data-bs-target='#offcanvas1'
                            aria-controls='offcanvasWithBothOptions'>
                        <i class='bi bi-arrow-left-square'></i> <?= $prevModel->name ?>
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

            <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
                категория: <strong><?= $parentIndexName ?></strong><br>
                название: <strong><?= ButtonHelper::updateType($parent::TEXT_TYPE, $parent->id); ?></strong><br>
                заголовок: <strong><?= $parent->title ?></strong><br>
                
                <?= ButtonHelper::editPanelsSet($parent, $editId); ?>
            </div>
            
            <?php
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

            <div class="card-body">
                <?=
                    $form->field(
                        $model, 'text',
                        [
                            'inputOptions' => [
                                'id' => 'text-edit-area',
                            ],
                        ],
                    )
                         ->textarea()
                         ->label(false)
                    ; ?>
            </div>

            <div class="card-footer">
                
                <?= ButtonHelper::submit()
                ?>

            </div>

        </div>
        
        <?php
            ActiveForm::end();
            echo TinyHelper::getText();
            echo TinyHelper::getDescription();
        ?>
    </div>
    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header text-center">
                <h4 class="card-title"><?php
                        try {
                            echo
                            TypeHelper::getName($parentTextType);
                        }
                        catch (Exception $e) {
                            
                        } ?>
                </h4>
            </div>
            <div class='card-body' style='max-height: 800px; overflow-y: auto;'>
                <?php
                    foreach ($siblings as $sibling) { ?>
                        <strong>#<?= $sibling->id . '. ' . Html::encode($sibling->title) ?></strong>
                        <?= FormatHelper::asHtml($model->description) ?>
                        <?php
                        if ($sibling->faqs) {
                            echo '<p class="small">Примечания: <br>';
                            foreach ($sibling->faqs as $faq) { ?>
                                <a href="#" class="faq-link"
                                   data-bs-toggle="modal"
                                   data-bs-target="#faqModal"
                                   data-faq-id="<?= $faq->id ?>">
                                    <strong><?= $faq->title ?></strong>
                                </a> <br>
                                <?php
                            }
                            echo '</p>';
                        }
                        ?>
                        <hr>
                        <?php
                    } ?>
            </div>

        </div>
    </div>
</div>


<div class='offcanvas offcanvas-start' data-bs-scroll='true'
     tabindex='-1'
     id='offcanvas1'
     aria-labelledby='offcanvasWithBothOptionsLabel'>
    <div class='offcanvas-header'>
        <h5 class='offcanvas-title' id='offcanvas1Label'><?= $prevModel->name ?></h5>
        <button type='button' class='btn-close' data-bs-dismiss='offcanvas' aria-label='Close'></button>
    </div>
    <div class='offcanvas-body'>
        <div class="card">
            <div class="card-header">
                <?= $prevModel->title ?>
            </div>
            <div class="card-body">
                <?= FormatHelper::asHtml($prevModel->description)
                ?>
            </div>
            <?php
                echo
                $this->render(
                    '/layouts/templates/_textWidget',
                    [
                        'model' => $prevModel,
                    ],
                );
            ?>
        </div>
    </div>
</div>


<!-- FAQ Modal -->
<div class='modal fade' id='faqModal' tabindex='-1' aria-labelledby='faqModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='faqModalLabel'></h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <!-- FAQ text will be inserted here -->
            </div>
        </div>
    </div>
</div>
<?php
    $getFaqUrl = Url::to(['/seo/faq/get-model']);
    
    $js = <<<JS
$(document).ready(function() {
    // Обработчик для всех ссылок с классом faq-link
    $('.faq-link').on('click', function(e) {
        e.preventDefault();
        
        // Получаем ID из data атрибута
        const faqId = $(this).data('faq-id');
        
        // Передаем ID в заголовок модального окна
        $('#faqModalLabel').text('ID: ' + faqId);
        
        // Можно также передать ID в тело модального окна, если нужно
        $('.modal-body p').text('Вы выбрали FAQ с ID: ' + faqId);
    });


});
JS;
    
    $this->registerJs($js, \yii\web\View::POS_READY);
?>
