<?php
    
    use core\helpers\IconHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\helpers\StringHelper;
    
    /**
     * @var array $category
     * @var array $categories
     * @var array $products
     * @var string $url
     * @var string $color
     * @var int    $level
     */
    
    
    $renderModel = function (
        array $category, array $categories, mixed $products, string $url, string $color, int $level = 0,
    ) use (&$renderModel): void {
        $imgUrl       = TypeHelper::getImage($category['site_id'], $category['id'], $category['array_type'], $category['slug'], 3);
        $productsName = match ($category['array_type']) {
            Constant::RAZDEL_TYPE   => TypeHelper::getName(Constant::PRODUCT_TYPE, null, true, true),
            Constant::BOOK_TYPE     => TypeHelper::getName(Constant::CHAPTER_TYPE, null, true, true),
            Constant::SECTION_TYPE  => TypeHelper::getName(Constant::ARTICLE_TYPE, null, true, true),
            Constant::CATEGORY_TYPE => TypeHelper::getName(Constant::POST_TYPE, null, true, true),
            default                 => null,
        };
        $parentId     = match ($category['array_type']) {
            Constant::RAZDEL_TYPE   => 'razdel_id',
            Constant::BOOK_TYPE     => 'book_id',
            Constant::SECTION_TYPE  => 'section_id',
            Constant::CATEGORY_TYPE => 'category_id',
            default                 => null,
        };
        ?>
        <div class="row">
            <div class="col-lg-7 col-md-12">
                <div class='card border mb-2'>
                    <img src='<?= $imgUrl ?>' class='card-img-top' alt='...'>
                    <div class='card-body'>
                        <h<?= min(5 + $level, 6) ?> class='card-title'>
                            <?= $category['name'] ?>
                        </h<?= min(5 + $level, 6) ?>>

                        <p class='card-text <?= $level > 0 ? 'small' : '' ?>'>
                            <?= StringHelper::truncateWords($category['description'], 25); ?>
                        </p>
                        <?= Html::a(
                            IconHelper::biEye('открыть окно'), // Assuming you want to display the
                                                               // product name
                            '#',                               // Placeholder href
                            [
                                'data-bs-toggle' => 'modal',
                                'data-bs-target' => "#product-modal-{$category['array_type']}-{$category['id']}",
                                'class'          => 'btn btn-sm btn-primary',
                            ],
                        ) ?>
                        
                        <?= Html::a('смотреть', [
                            $url . 'view',
                            'id' => $category['id'],
                        ],
                            [
                                'class'  => 'btn btn-sm btn-outline-primary',
                                'target' => '_blank',
                            ],
                        ) ?>
                        <?= Html::a('править', [
                            $url . 'update',
                            'id' => $category['id'],
                        ],
                            [
                                'class'  => 'btn btn-sm btn-outline-success',
                                'target' => '_blank',
                            ],
                        ) ?>
                    </div>
                </div>
            </div>
            <div class='col-lg-5'>
                <div class="card ">
                    <div class="card-body border">
                        <strong><?= $productsName ?></strong>
                        <ul>
                            <?php
                                if (!empty($products) && is_array($products)): ?>
                                    <?php
                                    foreach ($products as $product): ?>
                                        <?php
                                        if ($product[$parentId] == $category['id']): ?>
                                            <li>
                                                <?= Html::a(
                                                    $product['name'], // Assuming you want to display the product name
                                                    '#',              // Placeholder href
                                                    [
                                                        'data-bs-toggle' => 'modal',
                                                        'data-bs-target' => "#product-modal-{$product['array_type']}-{$product['id']}",
                                                    ],
                                                ) ?>
                                            </li>
                                        <?php
                                        endif; ?>
                                    <?php
                                    endforeach; ?>
                                <?php
                                endif; ?>
                        </ul>
                        <strong>Комментарии</strong>
                        <ul>
                            <?php
                                if (!empty($faqs) && is_array($faqs)): ?>
                                    <?php
                                    foreach ($faqs as $faq): ?>
                                        <?php
                                        if ($faq['text_type'] === $category['array_type'] && $faq['parent_id'] === $category['id']): ?>
                                            <li>
                                                <?= Html::a(
                                                    $faq['name'],     // Assuming you want to display the faq name
                                                    '#',              // Placeholder href
                                                    [
                                                        'data-toggle' => 'modal',
                                                        'data-target' => "#product-modal-{$faq['array_type']}-{$faq['id']}",
                                                    ],
                                                ) ?>
                                            </li>
                                        <?php
                                        endif; ?>
                                    <?php
                                    endforeach; ?>
                                <?php
                                endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
        foreach ($categories as $childModel) {
            if (
                $childModel['lft'] > $category['lft']
                && $childModel['rgt'] < $category['rgt']
                && $childModel['depth'] === $category['depth'] + 1
            ) {
                ?>
                <div class="border-start border-<?= $color ?> ps-3">
                    <?php
                        $renderModel($childModel, $categories, $products, $url, $color, $level + 1); ?>
                </div>
                <?php
            }
        }
        ?>
        <?php
    };
?>

<?php
// Начинаем рендеринг с переданной модели
    $renderModel($category, $categories, $products, $url, $color, 0);
