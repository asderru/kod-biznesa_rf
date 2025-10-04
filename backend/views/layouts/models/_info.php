<?php
    
    use backend\helpers\UrlHelper;
    use core\edit\entities\Content\Note;
    use core\edit\entities\Content\Tag;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Seo\Anons;
    use core\edit\entities\Seo\Material;
    use core\edit\entities\Seo\News;
    use core\edit\entities\Shop\Brand;
    use core\edit\entities\Tools\Draft;
    use core\edit\entities\User\Person;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model Model|Anons|Note|Tag|Author|Material|News|Brand|Draft|Person */
    /* @var $uploadForm UploadPhotoForm */
    
    const MODELS_INFO_LAYOUT = '#layouts_templates_modelPartView';
    
    echo PrintHelper::layout(MODELS_INFO_LAYOUT);
    
    try {
        $modelUrl = $model->getFullUrl();
    }
    catch (Exception $e) {
    
    }

?>


<div class='card h-100'>

    <div class='card-header bg-light'>

        <strong>
            Информация
        </strong>

    </div>

    <div class='table-responsive'>
        <table class='table table-sm table-striped table-bordered'>
            <tbody>
            <tr>
                <th scope='row'>id (сортировка)</th>
                <td><?= $model->id ?> (<?= $model->sort ?>)</td>
            </tr>
            <tr>
                <th scope='row'>Сайт</th>
                <td><?= ParametrHelper::getSiteName($model->site_id)
                    ?></td>
            </tr>
            <tr>
                <th scope='row'>Краткое название</th>
                <td><?= Html::encode($model->name)
                    ?></td>
            </tr>
            <tr>
                <th scope='row'>Полное название</th>
                <td><?= Html::encode($model->title)
                    ?></td>
            </tr>
            <tr>
                <th scope='row'>Идентификатор ссылки (англ.)</th>
                <td><?= Html::encode($model->slug)
                    ?></td>
            </tr>

            </tbody>
        </table>
    </div>

    <div class="card-body">
        <p class='d-inline-flex gap-1'>
            <a class='btn btn-sm btn-outline-dark' data-bs-toggle='collapse'
               href='#collapseTechInfo'
               role='button'
               aria-expanded='false' aria-controls='collapseTechInfo'>
                Техническая информация
            </a>
        </p>
        <div class='collapse p-2' id='collapseTechInfo'>
            <div class='card border-secondary'>
                <div class='card-body'>
                    <div class='border-bottom p-1'>
                        <strong>Время создания</strong>:
                        <?= FormatHelper::asDateTime($model->created_at)
                        ?>
                    </div>
                    <div class='border-bottom p-1'>
                        <strong>Время обновления</strong>:
                        <?= FormatHelper::asDateTime($model->updated_at)
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <?php
            if (ParametrHelper::isLocal()): ?>
                <strong>
                    Локальная ссылка:
                </strong> <?= UrlHelper::local($model)
                ?>
            <?php
            endif; ?>
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
            ><?= $modelUrl ?? null ?></span>
            <?= $this->render(
                '/layouts/scripts/copyUrl',
            )
            ?>
        </div>


    </div>
</div>
