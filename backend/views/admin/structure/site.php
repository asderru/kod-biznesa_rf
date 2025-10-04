<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\Admin\Information;
    use core\helpers\ButtonHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $site Information */
    /* @var $razdels core\edit\entities\Shop\Razdel[] */
    /* @var $products core\edit\entities\Shop\Product\Product[] */
    /* @var $pages core\edit\entities\Content\Page[] */
    /* @var $faqs core\edit\entities\Seo\Faq[] */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_structure_site_';
    
    $siteName = $site->name;
    
    $this->title = 'Структура сайта ' . $siteName;
    
    $buttons = [
        ButtonHelper::indexPanel(),
        ButtonHelper::adminStructure(),
    ];
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = $siteName;
    
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

<div class='card'>
    <div class='card-header bg-light d-flex justify-content-between p-2'>
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::clearCache($site->id) ?>
            <?= ButtonHelper::collapse() ?>
        </div>
    </div>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?php
            foreach ($buttons as $button) {
                echo $button;
            }
        ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">

                <div class='card'>

                    <div class='card-header bg-primary-subtle d-flex justify-content-between'>
                        <h4><?= TypeHelper::getLabel(Constant::RAZDEL_TYPE) ?></h4>

                        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
                            <?= ButtonHelper::clearCache($site->id, Constant::RAZDEL_TYPE) ?>
                            <?= ButtonHelper::indexType(Constant::RAZDEL_TYPE) ?>
                        </div>
                    </div>

                    <div class='card-body'>
                        <?php
                            $url = TypeHelper::getLongEditUrl(Constant::RAZDEL_TYPE);
                            $color = 'primary';
                            foreach ($razdels as $razdel):
                                if ($razdel['depth'] === 1): ?>
                                    <div class="p-2">
                                        <div class="card shadow-sm p-3 mb-5 bg-body-tertiary rounded">
                                            <?= $this->render(
                                                '_category',
                                                [
                                                    'categories' => $razdels,
                                                    'category'   => $razdel,
                                                    'products'   => $products,
                                                    'faqs'       => $faqs,
                                                    'url'        => $url,
                                                    'color'      => $color,
                                                ],
                                            ); ?>
                                        </div>
                                    </div>
                                <?php
                                endif;
                            endforeach;
                        ?>
                    </div>
                </div>

            </div>
            <div class='col-lg-6'>
                <div class='card'>
                    <div class='card-header bg-success-subtle d-flex justify-content-between'>
                        <h4><?= TypeHelper::getLabel(Constant::PAGE_TYPE) ?></h4>

                        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
                            <?= ButtonHelper::clearCache($site->id, Constant::PAGE_TYPE) ?>
                            <?= ButtonHelper::indexType(Constant::PAGE_TYPE) ?>
                        </div>
                    </div>
                    <div class='card-body'>
                        <?php
                            $url = TypeHelper::getLongEditUrl(Constant::PAGE_TYPE);
                            $color = 'primary';
                            foreach ($pages as $page):
                                if ($page['depth'] === 1): ?>
                                    <div class="p-2">
                                        <div class="card shadow-sm p-3 mb-5 bg-body-tertiary rounded">
                                            <?= $this->render(
                                                '_category',
                                                [
                                                    'categories' => $pages,
                                                    'category'   => $page,
                                                    'products'   => null,
                                                    'faqs'       => $faqs,
                                                    'url'        => $url,
                                                    'color'      => $color,
                                                ],
                                            ); ?>
                                        </div>
                                    </div>
                                <?php
                                endif;
                            endforeach;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
    foreach ($razdels as $razdel): ?>

        <!-- Modal -->
        <div class='modal fade' id="product-modal-<?= Constant::RAZDEL_TYPE ?>-<?= $razdel['id'] ?>" tabindex='-1'
             aria-labelledby="product-modal-label-<?= Constant::RAZDEL_TYPE ?>-<?= $razdel['id'] ?>" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'
                            id="product-modal-label-<?= Constant::RAZDEL_TYPE ?>-<?= $razdel['id'] ?>"><?= Html::encode($razdel['name']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <?= $this->render(
                            '/layouts/modal/_modalCat',
                            [
                                'model'    => $razdel,
                                'textType' => Constant::RAZDEL_TYPE,
                            ],
                        )
                        ?>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    
    <?php
    endforeach;
?>


<?php
    foreach ($pages as $page): ?>

        <!-- Modal -->
        <div class='modal fade' id="product-modal-<?= Constant::PAGE_TYPE ?>-<?= $page['id'] ?>" tabindex='-1'
             aria-labelledby="product-modal-label-<?= Constant::PAGE_TYPE ?>-<?= $page['id'] ?>" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'
                            id="product-modal-label-<?= Constant::PAGE_TYPE ?>-<?= $page['id'] ?>"><?= Html::encode($page['name']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <?= $this->render(
                            '/layouts/modal/_modalCat',
                            [
                                'model'    => $page,
                                'textType' => Constant::PAGE_TYPE,
                            ],
                        )
                        ?>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    
    <?php
    endforeach;
?>


<?php
    foreach ($products as $product): ?>

        <!-- Modal -->
        <div class='modal fade' id="product-modal-<?= Constant::PRODUCT_TYPE ?>-<?= $product['id'] ?>" tabindex='-1'
             aria-labelledby="product-modal-label-<?= Constant::PRODUCT_TYPE ?>-<?= $product['id'] ?>"
             aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'
                            id="product-modal-label-<?= Constant::PRODUCT_TYPE ?>-<?= $product['id'] ?>"><?= Html::encode($product['name']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <?= $this->render(
                            '/layouts/modal/_modalCat',
                            [
                                'model'    => $product,
                                'textType' => Constant::PRODUCT_TYPE,
                            ],
                        )
                        ?>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    
    <?php
    endforeach;
?>


<?php
    foreach ($faqs as $faq): ?>

        <!-- Modal -->
        <div class='modal fade' id="product-modal-<?= Constant::FAQ_TYPE ?>-<?= $faq['id'] ?>" tabindex='-1'
             aria-labelledby="product-modal-label-<?= Constant::FAQ_TYPE ?>-<?= $faq['id'] ?>" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'
                            id="product-modal-label-<?= Constant::FAQ_TYPE ?>-<?= $faq['id'] ?>"><?= Html::encode($faq['name']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <?php
                                    if ($faq['photo']):
                                        ?>
                                        <img src="<?= TypeHelper::getImage($faq['site_id'], $faq['id'], $faq['array_type'], $faq['slug'], 6); ?>"
                                             class="img-fluid rounded mb-3"
                                             alt="<?= Html::encode($faq['name']) ?>">
                                    <?php
                                    endif; ?>
                            </div>
                            <div class="col-md-8">
                                <dl class="row">
                                    <dt class="col-sm-4">ID:</dt>
                                    <dd class="col-sm-8"><?= $faq['id'] ?></dd>

                                    <dt class="col-sm-4">Название:</dt>
                                    <dd class="col-sm-8"><?= Html::encode($faq['name']) ?></dd>

                                    <dt class="col-sm-4">Идентификатор:</dt>
                                    <dd class="col-sm-8"><?= Html::encode($faq['slug']) ?></dd>

                                    <dt class="col-sm-4">Ссылка:</dt>
                                    <dd class="col-sm-8"><?= Html::encode($faq['link']) ?></dd>

                                    <dt class="col-sm-4">Статус:</dt>
                                    <dd class="col-sm-8">
                                <span class="badge bg-<?= $faq['status'] === 3 ? 'success' : 'secondary' ?>">
                                    <?= $faq['status'] === 3 ? 'Активен' : 'Неактивен' ?>
                                </span>
                                    </dd>

                                    <dt class="col-sm-4">Рейтинг:</dt>
                                    <dd class="col-sm-8">
                                        <?php
                                            for ($i = 0; $i < $faq['rating']; $i++): ?>
                                                <i class="bi bi-star-fill text-warning"></i>
                                            <?php
                                            endfor; ?>
                                    </dd>

                                    <dt class="col-sm-4">Глубина:</dt>
                                    <dd class="col-sm-8"><?= $faq['depth'] ?></dd>

                                    <dt class="col-sm-4">Заголовок:</dt>
                                    <dd class="col-sm-8"><?= Html::encode($faq['title']) ?></dd>

                                    <dt class="col-sm-4">Обновлено:</dt>
                                    <dd class="col-sm-8"><?= Yii::$app->formatter->asDatetime($faq['updated_at']) ?></dd>

                                    <dt class="col-sm-4">Описание:</dt>
                                    <dd class="col-sm-8"><?= Html::encode($faq['description']) ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    
    <?php
    endforeach;
?>
