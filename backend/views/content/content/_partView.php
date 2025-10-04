<?php
    
    use backend\helpers\UrlHelper;
    use core\edit\entities\Content\Content;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model Content */
    
    const CONTENT_PART_LAYOUT = '#content_content_partView';
    echo PrintHelper::layout(CONTENT_PART_LAYOUT);

?>
<div class='row'>

    <div class='col-lg-6'>

        <div class='card h-100'>

            <div class='card-header bg-light'>
                <strong>
                    Информация
                </strong>
            </div>
            <div class='card-body table-responsive mb-3'>
                
                <?= $this->render(
                    '@app/views/content/content/_partDetailView.php',
                    [
                        'model' => $model,
                    ],
                )
                ?>

            </div>

            <hr>
            <strong>
                Ссылка:
            </strong>
            <?= Html::a(
                $model->link,
                $model->link,
                [
                    'target' => '_blank',
                ],
            )
            ?>.

            <span>
						<?=
                            UrlHelper::checkUrl($model->status, $model->link)
                        ?>
					</span>
            <div class='py-2'>
                <button
                        onclick='triggerCopy()'
                        id='copy-button'
                        class='btn btn-sm btn-outline-dark'
                >Скопировать
                </button>
                <span
                        id='copyUrl' class='invisible'
                ><?= $model->link ?></span>
                <?= $this->render(
                    '/layouts/scripts/copyUrl',
                )
                ?>
            </div>

        </div>
    </div>
    <div class='col-lg-6'>

        <div class='card h-100'>
            
            <?php
                if ($model->picture_url) : ?>
                    <div class="card-header bg-body-secondary">
                        <strong>
                            Изображение
                        </strong>
                    </div>

                    <div class='card-body'>
                        <?=
                            Html::img(
                                $model->picture_url,
                                [
                                    'class' => 'img-fluid',
                                ],
                            )
                        ?>
                    </div>
                <?php
                endif ?>

            <div class='card-header bg-light'>

                <strong>
                    Текст
                </strong>

            </div>

            <div class='card-body'>
                
                <?= FormatHelper::asHtml(
                    $model->description,
                )
                ?>

            </div>
        </div>

    </div>
</div>
