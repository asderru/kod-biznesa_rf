<?php
    
    use core\edit\entities\Admin\Edit;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Forum\Thread;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $model Model|Post|Thread */
    /* @var $faq core\edit\entities\Seo\Faq */
    /* @var $uploadForm core\edit\forms\UploadPhotoForm */
    /* @var $editItems Edit[] */
    /* @var $modal bool */
    
    const MODELS_POST_PART_LAYOUT = '#layouts_models_partView';
    echo PrintHelper::layout(MODELS_POST_PART_LAYOUT);

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
        <div class="card">
            <div class="card-body">
                <?=
                    $this->render(
                        '/layouts/images/_uploadPostsPhotos',
                        [
                            'model'      => $model,
                            'uploadForm' => $uploadForm,
                        ],
                    )
                ?>
                <?php
                    if ($model->gallery) { ?>
                        <div class='card-footer'>
                            <strong>Галерея:</strong>
                            <br>
                            <?= ButtonHelper::gallery($model)
                            ?>
                        </div>
                        <?php
                    } ?>


            </div>
        </div>
    </div>
</div>

<?=
    $this->render(
        '/layouts/templates/_viewWidgets',
        [
            'model' => $model,
        ],
    )
?>

<?=
    $this->render(
        '/layouts/templates/_textWidget',
        [
            'model' => $model,
        ],
    )
?>
