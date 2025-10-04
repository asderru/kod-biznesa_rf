<?php
    
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    use yii\widgets\DetailView;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Library\Author */
    /* @var $uploadForm UploadPhotoForm */
    
    const LIBRARY_AUTHOR_PART_LAYOUT = '#library_author_partView';
    echo PrintHelper::layout(LIBRARY_AUTHOR_PART_LAYOUT);

?>

<div class='row mb-3'>

    <div class='col-xl-6'>
        <div class='card'>

            <div class='card-header bg-light'>
                <strong>
                    Информация
                </strong>
            </div>
            <div class='card-body'>

                <div class='table-responsive'>
                    <div class='table'>
                        
                        <?php
                            try {
                                echo DetailView::widget(
                                    [
                                        'model'      => $model,
                                        'attributes' => [
                                            'id',
                                            [
                                                'attribute' => 'site.name',
                                                'label'     => 'Основной сайт',
                                            ],
                                            //'site_id',
                                            [
                                                'attribute' => 'user.name',
                                                'label'     => 'Создал автора',
                                            ],
                                            [
                                                'attribute' => 'authorType.name',
                                                'label'     => 'Тип литературы',
                                            ],
                                            'type_id',
                                            'name',
                                            'slug',
                                            'contact',
                                            'website',
                                            //'photo',
                                            'sort',
                                            [
                                                'attribute' => 'updated_at',
                                                'format'    => 'dateTime',
                                            ],
                                        ],
                                    ],
                                );
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'DetailView-widget ', LIBRARY_AUTHOR_PART_LAYOUT, $e,
                                );
                            } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class='col-xl-6'>
        <!--####### Одна картинка #############-->
        
        <?= $this->render(
            '/layouts/images/_images',
            [
                'model'      => $model,
                'uploadForm' => $uploadForm,
            ],
        )
        ?>
        <!--####### Конец картинки ######################-->
        <!--Конец загрузки-->
        <div class="card">
            <div class="card-header bg-light">
                <?= Html::encode($model->title)
                ?>
            </div>
            <div class="card-body">
                <?= FormatHelper::asHtml($model->description)
                ?>
            </div>
        </div>
    </div>

</div>

<div class='card'>
    <div class='card-header text-white bg-secondary'>
        <strong>
            Бытописание жизненного пути автора
        </strong>
    </div>
    <div class='card-body'>
        <?= FormatHelper::asHtml($model->text)
        ?>
    </div>
    <div class="card-footer">
        <?php
            $text = $model->text !== null ? strip_tags($model->text) : null;
        ?>
        <?php
            if ($text) { ?>
                В тексте - <?= str_word_count($text, 0, 'АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя')
                ?>
                слов, <?= mb_strlen($text)
                ?> знаков.
                <?php
            } ?>
    </div>
</div>
