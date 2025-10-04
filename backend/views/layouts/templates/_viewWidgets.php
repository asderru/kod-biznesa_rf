<?php
    
    use backend\widgets\KeywordWidget;
    use backend\widgets\NoteWidget;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\PrintHelper;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model Razdel|Category|Page|Group|Book|Section */
    
    const VIEW_WIDGET_LAYOUT = '#layouts_templates_viewWidgets';
    echo PrintHelper::layout(VIEW_WIDGET_LAYOUT);
    
    try {
        $modelUrl = $model->getFullUrl();
    }
    catch (Exception $e) {
        PrintHelper::exception(
            '$model->getFullUrl',
            VIEW_WIDGET_LAYOUT
            , $e,
        );
        
    }
?>


<div class='card mb-3'>

    <div class='card-header bg-secondary-subtle d-flex justify-content-between'>
        <div>
            <a class='btn btn-outline-dark' data-bs-toggle='collapse'
               href='#collapseKeywordWidget'
               role='button'
               aria-expanded='false' aria-controls='collapseKeywordWidget'>
                Ключевые слова и заметки
            </a>
        </div>
    </div>

    <div class='card-body collapse p-2' id='collapseKeywordWidget'>
        <div class="row">
            <div class="col-lg-6">
                
                <?php
                    try {
                        echo
                        KeywordWidget::widget(
                            [
                                'parent' => $model,
                                'title'  => 'Ключевые слова',
                            ],
                        );
                    }
                    catch (Exception|Throwable $e) {
                        PrintHelper::exception(
                            ' KeywordWidget_widget ', VIEW_WIDGET_LAYOUT, $e,
                        );
                    } ?>
            </div>
            <div class="col-lg-6">
                <?php
                    try {
                        echo
                        NoteWidget::widget(
                            [
                                'parent' => $model,
                                'title'  => 'Заметки к тексту',
                            ],
                        );
                    }
                    catch (Exception|Throwable $e) {
                        PrintHelper::exception(
                            ' NoteWidget::widget ', VIEW_WIDGET_LAYOUT, $e,
                        );
                    } ?>
                
                <?=
                    ($model->hasDrafts()) ?
                        $this->render(
                            '/layouts/partials/_draft',
                            [
                                'drafts' => $model->drafts,
                            ],
                        ) : null
                ?>
            </div>
        </div>

    </div>
</div>
