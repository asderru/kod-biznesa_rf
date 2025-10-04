<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\helpers\StatusHelper;
    use core\helpers\ButtonHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\types\TypeHelper;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\helpers\ArrayHelper;
    
    /* @var $this yii\web\View */
    /* @var $parent Model */
    /* @var $models array */
    /* @var $limit int */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_change_copy';
    
    $this->title = 'Копирование ' . $parent->name;
    
    $parentUrl = TypeHelper::getLongEditUrl($textType);
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex($textType);
    $this->params['breadcrumbs'][] = [
        'label' => $parent->name,
        'url'   => [$parentUrl . 'view', 'id' => $parent->id],
    ];
    $this->params['breadcrumbs'][] = 'Копирование';
    
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
    
    $form = ActiveForm::begin(
        [
            'options'     => [
                'class' => 'active__form',
            ],
            'fieldConfig' => [
                'errorOptions' => [
                    'encode' => false,
                    'class'  => 'help-block',
                ],
            ],
        ],
    );

?>

<div class='card'>
    
    <?= $this->render(
        '/layouts/tops/_updateHeader',
        [
            'model'    => $parent,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    ) ?>

    <div class='card-body bg-body-tertiary'>
        <div class='row row-cols-1 row-cols-md-3 g-4'>
            <div class="col">
                <div class="card border border-primary h-100 shadow-sm">
                    <div class='card-header '><h4><?= $parent->name
                            ?></h4>
                        <?= $limit ?> экземпляров копий <?= TypeHelper::getName($textType, 1) ?></div>
                    <div class='card-body'>
                        <div class='table-responsive'>
                            <table class='table table-sm table-striped table-bordered'>
                                <tbody>
                                <tr>
                                    <th scope='row'>id (сортировка)</th>
                                    <td><?= $parent->id ?> (<?= $parent->sort ?>)</td>
                                </tr>
                                <tr>
                                    <th scope='row'>Сайт</th>
                                    <td><?= ParametrHelper::getSiteName($parent->site_id)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Раздел</th>
                                    <td><?= Html::a(
                                            Html::encode($parent->razdel->name),
                                            [
                                                'shop/razdel/view',
                                                'id' => $parent->razdel->id,
                                            ],
                                        )
                                        ?>. <?= StatusHelper::statusBadgeLabel($parent->razdel->status) ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Краткое название</th>
                                    <td><?= Html::encode($parent->name)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Полное название</th>
                                    <td><?= Html::encode($parent->title)
                                        ?></td>
                                </tr>
                                <?php
                                    if ($parent->brand) { ?>
                                        <tr>
                                            <th scope='row'>Бренд</th>
                                            <td><?= Html::encode($parent->brand->name)
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    } ?>
                                <tr>
                                    <th scope='row'>Код</th>
                                    <td><?= Html::encode($parent->code)
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope='row'>Цена</th>
                                    <td><?= Html::encode($parent->price)
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope='row'><strong>Метки</strong>:</th>
                                    <td>
                                        
                                        <?= implode(', ', ArrayHelper::getColumn($parent->tags, 'name'))
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        
                        <?= ButtonHelper::viewType($textType, $parent->id, 'Смотреть') ?>
                    </div>
                </div>
            </div>
            
            
            <?php
                foreach ($models as $model): ?>
                    <div class="col">
                        <div class="card border border-primary-subtle h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= $model['name'] ?></h5>
                                <p class="card-text">
                                    <strong>ID:</strong> <?= $model['id'] ?><br>
                                    <strong>Краткое название:</strong> <?= $model['name'] ?><br>
                                    <strong>Полное название:</strong> <?= $model['title'] ?><br>
                                    <strong>Идентификатор:</strong> <?= $model['slug'] ?><br>
                                    <strong>Ссылка:</strong> <?= $model['link'] ?><br>
                                    <strong>Код:</strong> <?= $model['code'] ?><br>
                                    <strong>Цена:</strong> <?= number_format($model['price'], 0, '', ' ') ?> ₽<br>
                                    <strong>Рейтинг:</strong> <?= $model['rating'] ?><br>
                                    <strong>Скопировано:</strong> <?= date('d.m.Y H:i:s', $model['updated_at']) ?>
                                </p>
                            </div>
                            <div class="card-footer text-muted d-flex justify-content-between">
                                <?= ButtonHelper::changeParent($textType, $model['id'], 'Сменить сайт') ?>
                                <?= ButtonHelper::updateType($textType, $model['id'], 'Редактировать') ?>

                            </div>
                        </div>
                    </div>
                <?php
                endforeach; ?>
        </div>
    </div>

</div>
