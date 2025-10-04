<?php
    
    use core\helpers\FaviconHelper;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Seo\Material;
    use core\edit\entities\Seo\News;
    use core\edit\entities\Shop\Brand;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\edit\entities\Tools\Draft;
    use core\edit\forms\ModelEditForm;
    use core\helpers\ButtonHelper;
    use core\helpers\ModelHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model Article|Author|Book|Brand|Category|Chapter|Draft|Group|Material|News|Page|Post|Product|Razdel|Section| */
    /* @var $model ModelEditForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $editId int */
    
    const LAYOUT_ID = '#admin_view_view';
    
    $status    = $model->status;
    $faqs      = $model->faqs;
    $footnotes = $model->footnotes;
    $reviews   = $model->reviews;
    $editUrl   = $model->getEditPath();
    $count     = strlen($model->text);
    
    
    $nextModel = $model->getNextModelById();
    $nextUrl   = Html::a(
        Html::encode($nextModel->name),
        [
            'view',
            'textType' => $nextModel->textType,
            'id'       => $nextModel->id,
        ],
    );
    $prevModel = $model->getPrevModelById();
    $prevUrl   = Html::a(
        Html::encode($prevModel->name),
        [
            'view',
            'textType' => $prevModel->textType,
            'id'       => $prevModel->id,
        ],
    );
    
    $this->title                   = $model->name . '. ' . TypeHelper::getName($model::TEXT_TYPE);
    $this->params['breadcrumbs'][] = $model->name . '. ' . TypeHelper::getName($model::TEXT_TYPE);
    
    $buttons = [];
    
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

<div class='bg-primary-subtle d-flex justify-content-between border-bottom'>
    <!-- Previous navigation -->
    <div class='p-1 text-secondary'>
        <i class='bi bi-caret-left'></i>
        <?= $prevUrl ?>
    </div>
    <!-- Next navigation -->
    <div class="p-1 text-secondary">
        <?= $nextUrl ?>
        <i class='bi bi-caret-right'></i>
    </div>
</div>


<div class='card'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div>
            <h4>
                <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($this->title)
                ?>
            </h4>
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
            
            
            <?php
                try {
                    echo
                    ($status > Constant::STATUS_ROOT) ? ButtonHelper::viewType($model::TEXT_TYPE, $model->id) : null;
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        'ButtonHelper_viewType ', LAYOUT_ID, $e,
                    );
                } ?>
            <?php
                try {
                    echo
                    ($status > Constant::STATUS_ROOT) ? ButtonHelper::updateType($model::TEXT_TYPE, $model->id) : null;
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        'ButtonHelper_updateModel ', LAYOUT_ID, $e,
                    );
                } ?>
            <?php
                try {
                    echo
                    ($status > Constant::STATUS_ROOT) ? ButtonHelper::expressType($model::TEXT_TYPE, $model->id) : null;
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        'ButtonHelper_expressModel ', LAYOUT_ID, $e,
                    );
                } ?>
            
            <?= ButtonHelper::collapse()
            ?>
        </div>
    </div>

    <div class="card-body">
        <?= $this->render(
                '/layouts/partials/_modalView',
                [
                    'model' => $model,
                ],
            )
        ?>
    </div>
</div>
