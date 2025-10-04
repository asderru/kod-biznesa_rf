<?php
    
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\PrintHelper;
    
    /* @var $this yii\web\View */
    /* @var $model Razdel|Category|Page|Group|Book|Section */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $editItems array */
    /* @var $buttons bool */
    /* @var $modal bool */
    
    const RAZDELS_PART_VIEW_LAYOUT = '#layouts_razdels_partView';
    echo PrintHelper::layout(RAZDELS_PART_VIEW_LAYOUT);

?>

    <div class='row mb-3'>

        <div class='col-xl-6'>
            
            <?= $this->render(
                '/layouts/razdels/_info',
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

<?php
    echo $this->render(
        '/layouts/templates/_viewWidgets',
        [
            'model' => $model,
        ],
    );
    
    echo
    $this->render(
        '/layouts/templates/_textWidget',
        [
            'model' => $model,
        ],
    );
    
    echo
    $this->render(
        '/layouts/widgets/seoWidget',
        [
            'model' => $model,
        ],
    );
