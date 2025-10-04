<?php
    
    use core\helpers\types\TypeHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $model Model */

?>


<!-- Modal -->
<div class='modal fade' id="product-modal-<?= $model['id'] ?>-<?= $model['razdel_id'] ?>" tabindex='-1'
     aria-labelledby="product-modal-label-<?= $model['id'] ?>-<?= $model['razdel_id'] ?>" aria-hidden='true'>
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title'
                    id="product-modal-label-<?= $model['id'] ?>-<?= $model['razdel_id'] ?>"><?= Html::encode($model['name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <?php
                            if ($model['photo']):
                                ?>
                                <img src="<?= TypeHelper::getImage($model['site_id'], $model['id'], $model['array_type'], $model['slug'], 6); ?>"
                                     class="img-fluid rounded mb-3"
                                     alt="<?= Html::encode($model['name']) ?>">
                            <?php
                            endif; ?>
                    </div>
                    <div class="col-md-8">
                        <dl class="row">
                            <dt class="col-sm-4">ID:</dt>
                            <dd class="col-sm-8"><?= $model['id'] ?></dd>

                            <dt class="col-sm-4">Название:</dt>
                            <dd class="col-sm-8"><?= Html::encode($model['name']) ?></dd>

                            <dt class="col-sm-4">Идентификатор:</dt>
                            <dd class="col-sm-8"><?= Html::encode($model['slug']) ?></dd>

                            <dt class="col-sm-4">Ссылка:</dt>
                            <dd class="col-sm-8"><?= Html::encode($model['link']) ?></dd>

                            <dt class="col-sm-4">Статус:</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-<?= $model['status'] === 3 ? 'success' : 'secondary' ?>">
                                    <?= $model['status'] === 3 ? 'Активен' : 'Неактивен' ?>
                                </span>
                            </dd>

                            <dt class="col-sm-4">Рейтинг:</dt>
                            <dd class="col-sm-8">
                                <?php
                                    for ($i = 0; $i < $model['rating']; $i++): ?>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    <?php
                                    endfor; ?>
                            </dd>

                            <dt class="col-sm-4">Глубина:</dt>
                            <dd class="col-sm-8"><?= $model['depth'] ?></dd>

                            <dt class="col-sm-4">Заголовок:</dt>
                            <dd class="col-sm-8"><?= Html::encode($model['title']) ?></dd>

                            <dt class="col-sm-4">Обновлено:</dt>
                            <dd class="col-sm-8"><?= Yii::$app->formatter->asDatetime($model['updated_at']) ?></dd>

                            <dt class="col-sm-4">Описание:</dt>
                            <dd class="col-sm-8"><?= Html::encode($model['description']) ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
