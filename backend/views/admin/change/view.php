<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\Admin\Information;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $site Information */
    /* @var $models core\edit\entities\Blog\Category[] */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_change_view';
    
    $this->title                   = TypeHelper::getName($textType, null, false, true) . '. Смена сайта';
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = TypeHelper::getName($textType, null, false, true);
    
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
    <div class="card-header bg-light">
        <h4>Перенести <?= TypeHelper::getName($textType, 4) ?> на другой сайт</h4>
    </div>
    <div class="card-body bg-light-subtle">
        <div class="row">
            <div class="col-lg-6">
                <div class='card-header'>
                    Сайт:
                </div>
                <div class="card">
                    <div class="card-body">

                        <div class='table-responsive'>
                            <table class='table table-sm table-striped table-bordered'>
                                <tbody>
                                <tr>
                                    <th scope='row'>id</th>
                                    <td><?= $site->id ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Сайт</th>
                                    <td><?= Html::encode($site->name)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Полное название</th>
                                    <td><?= Html::encode($site->title)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Описание сайта</th>
                                    <td><?= FormatHelper::asHtml($site->description)
                                        ?></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class='col-lg-6'>
                <div class='card'>
                    <div class='card-header'>
                        Переместить <?= TypeHelper::getName($textType, 4) ?>:
                    </div>
                    <div class='card-body'>
                        <div class='accordion' id='sectionsAccordion'>
                            <?php
                                foreach ($models as $index => $model): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?= $index ?>">
                                            <button class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse<?= $index ?>"
                                                    aria-expanded="false"
                                                    aria-controls="collapse<?= $index ?>">
                                                <?= $model->getDepthTitle() ?>
                                            </button>
                                        </h2>
                                        <div id="collapse<?= $index ?>"
                                             class="accordion-collapse collapse"
                                             aria-labelledby="heading<?= $index ?>"
                                             data-bs-parent="#sectionsAccordion">
                                            <div class="accordion-body">
                                                <?= FormatHelper::asHtml($model->description) ?>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <?= ButtonHelper::viewType($textType, $model->id, 'Смотреть') ?>
                                                    <?= ButtonHelper::changeParent(
                                                        $textType, $model->id, 'Сменить
                                                    сайт',
                                                    ) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
