<?php
    
    use backend\helpers\UrlHelper;
    use backend\widgets\PagerWidget;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Library\Author */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_author_view';
    
    $this->title = $model->name;

?>
<div class="card">

    <div class='card-body'>

        <div class='row mb-3'>

            <div class='col-xl-6'>


                <div class='card h-100'>

                    <div class='card-header bg-light'>

                        <strong>
                            Информация
                        </strong>

                    </div>
                    <div class="card-body">
                        <dl class='row'>
                            <dt class='col-sm-3'>id (номер по порядку):</dt>
                            <dd class='col-sm-9'><?= $model->id ?> (<?= $model->sort ?>)</dd>
                            <dt class='col-sm-3'>Псевдоним:</dt>
                            <dd class='col-sm-9'><?= Html::encode($model->name) ?></dd>
                            <dt class='col-sm-3'>ФИО:</dt>
                            <dd class='col-sm-9'><?= Html::encode($model->title) ?></dd>
                            <dt class='col-sm-3'>Контакты:</dt>
                            <dd class='col-sm-9'><?= $model->contact ?></dd>
                            <dt class='col-sm-3'>Сайт:</dt>
                            <dd class='col-sm-9'><?= $model->website ?></dd>
                            <dt class='col-sm-3'>Пользователь:</dt>
                            <dd class='col-sm-9'><?= $model->user->name ?></dd>
                            <dt class='col-sm-3'>Время обновления:</dt>
                            <dd class='col-sm-9'><?= FormatHelper::asDateTime($model->updated_at) ?></dd>
                        </dl>
                    </div>

                    <div class='card-header bg-light'>
                        <strong>
                            Описание (тег Descriptiion)
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?= FormatHelper::asHtml($model->description)
                        ?>
                    </div>
                    <div class='card-footer'>
                        <?= ButtonHelper::update($model->id, 'Редактировать') ?>
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

            </div>

        </div>

    </div>

    <div class='card mb-3'>

        <div class='card-header bg-light'>
            <strong>
                Текст
            </strong>
        </div>

        <div class='card-body' id='card-body-text'>
            <?= FormatHelper::asHtml($model->text)
            ?>
        </div>
        <div class="card-footer">
            <?php
                $text
                    = $model->text !== null ? strip_tags($model->text) : null;
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

</div>
