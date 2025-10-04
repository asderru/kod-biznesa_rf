<?php
    
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Shop\Product\Product;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $model Model|Product|Article|Chapter */
    /* @var $uploadForm core\edit\forms\UploadPhotoForm */
    /* @var $editItems array */
    /* @var $modal bool */
    
    const PRODUCT_PART_VIEW_LAYOUT = '#layouts_products_partView';
    echo PrintHelper::layout(PRODUCT_PART_VIEW_LAYOUT);
?>
    <div class='row mb-3'>

        <div class='col-xl-6'>
            
            <?= $this->render(
                '/layouts/products/_info',
                [
                    'model'     => $model,
                    'editItems' => $editItems,
                ],
            )
            ?>

        </div>


        <div class='col-xl-6'>
            
            <?= $this->render(
                '/layouts/images/_images',
                [
                    'model'      => $model,
                    'uploadForm' => $uploadForm,
                ],
            )
            ?>
            <?= $this->render(
                '/layouts/images/_galleries',
                [
                    'model' => $model,
                ],
            )
            ?>
        </div>
    </div>

<?= $this->render(
        '/layouts/templates/_viewWidgets',
        [
            'model' => $model,
        ],
    )
?>

<?= $this->render(
        '/layouts/templates/_textWidget',
        [
            'model' => $model,
        ],
    )
?>

<?= $this->render(
        '/layouts/widgets/seoWidget',
        [
            'model' => $model,
        ],
    );
