<?php
    
    use backend\helpers\StatusHelper;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use yii\base\InvalidConfigException;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $model Model */
    /* @var $texType int */
    
    $siteId      = $model['site_id'];
    $modelId     = $model['id'];
    $name        = $model['name'];
    $slug        = $model['slug'];
    $link        = $model['link'];
    $title       = $model['title'];
    $description = $model['description'];
    $updated     = 0;
    try {
        $updated = Yii::$app->formatter->asDatetime($model['updated_at']);
    }
    catch (InvalidConfigException $e) {
    }
    $status     = StatusHelper::statusBadgeLabel($model['status']);
    $isProduct = TypeHelper::isProduct($model['array_type']);
    $isCategory = TypeHelper::isCategory($model['array_type']);

?>

<div class='row'>
    <div class='col-md-4'>
        <?php
            if ($model['photo']):
                ?>
                <img
                        src="<?= TypeHelper::getImage($siteId, $modelId, $model['array_type'], $slug, 6); ?>"
                        class="img-fluid rounded mb-3"
                        alt="<?= Html::encode($name) ?>">
                <hr>
            <?php
            endif; ?>
        <?= ButtonHelper::viewType($model['array_type'], $modelId, 'Открыть в новом окне', true) ?>"
        <?= ButtonHelper::updateType(
            $model['array_type'], $modelId, 'Редактировать в новом окне',
            true,
        ) ?>
    </div>
    <div class="col-md-8">
        <dl class="row">
            <dt class="col-sm-4">ID:</dt>
            <dd class="col-sm-8"><?= $modelId ?></dd>

            <dt class="col-sm-4">Название:</dt>
            <dd class="col-sm-8"><?= Html::encode($name) ?></dd>

            <dt class='col-sm-4'>Заголовок:</dt>
            <dd class='col-sm-8'><?= Html::encode($title) ?></dd>

            <dt class="col-sm-4">Идентификатор:</dt>
            <dd class="col-sm-8"><?= Html::encode($slug) ?></dd>

            <dt class="col-sm-4">Ссылка:</dt>
            <dd class="col-sm-8"><?= Html::encode($link) ?></dd>

            <dt class="col-sm-4">Статус:</dt>
            <dd class="col-sm-8"><?= $status ?></dd>
            
            <?php
                if (($isProduct && $model['array_type'] !== Constant::CHAPTER_TYPE) || $isCategory): ?>
                    <dt class="col-sm-4">Рейтинг:</dt>
                    <dd class="col-sm-8"><?= $model['rating'] ?></dd>
                <?php
                endif; ?>
            
            <?php
                if ($isCategory): ?>
                    <dt class="col-sm-4">Поле LFT:</dt>
                    <dd class="col-sm-8"><?= $model['lft'] ?></dd>
                    <dt class='col-sm-4'>Поле RGT:</dt>
                    <dd class="col-sm-8"><?= $model['rgt'] ?></dd>
                    <dt class="col-sm-4">Глубина:</dt>
                    <dd class="col-sm-8"><?= $model['depth'] ?></dd>
                <?php
                endif; ?>

            <dt class='col-sm-4'>Описание:</dt>
            <dd class='col-sm-8'><?= FormatHelper::truncateWords($description, 20) ?></dd>
            <dt class="col-sm-4">Обновлено:</dt>
            <dd class="col-sm-8"><?= $updated ?></dd>
        </dl>
    </div>
</div>
