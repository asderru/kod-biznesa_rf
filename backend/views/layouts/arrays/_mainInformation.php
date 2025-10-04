<?php

    use core\helpers\FormatHelper;
    use core\helpers\SessionHelper;
    use core\helpers\types\TypeUrlHelper;
    use yii\bootstrap5\Html;

    /* @var $model array */
    /* @var $tags array */
    /* @var $keywords array */

    $modelUrl = $modelUrl ?? null;
    $fullUrl  = $modelUrl ?? TypeUrlHelper::getModelLinkUrl($model);

?>
<div class='table-responsive'>
    <table class='table table-sm table-striped table-bordered'>
        <tbody>
        <tr>
            <th scope='row'>id <small>(статус)</small></th>
            <td><?= $model['id'] ?> <small>(<?= $model['status'] ?>)</small></td>
        </tr>
        <tr>
            <th scope='row'>Краткое название</th>
            <td><?= Html::encode($model['name'])
                ?></td>
        </tr>
        <tr>
            <th scope='row'>Полное название</th>
            <td>
                <?= Html::encode($model['title']) ?>
            </td>
        </tr>
        <tr>
            <th scope='row'>Идентификатор ссылки (англ.)</th>
            <td><?= ($model['slug'])
                ?></td>
        </tr>
        <tr>
            <th scope='row'>Сайт</th>
            <td><?= SessionHelper::getSiteName($model['site_id']) ?></td>
        </tr>
        <tr>
            <th scope='row'>Смотреть на сайте</th>
            <td><?= Html::a(
                        $fullUrl,
                        $fullUrl,
                        ['target' => '_blank', 'class' => 'text-break flex-grow-1'],
                ) ?></td>
        </tr>
        <tr>
            <th scope='row'>Метки</th>
            <td><?php
                    if ($tags): ?>
                        <?= FormatHelper::formatTagsArray($tags) ?>
                    <?php
                    else: ?>
                        не определены
                    <?php
                    endif; ?>
            </td>
        </tr>

        <tr>
            <th scope='row'>Ключевое слово (главное)</th>
            <td>
                <?php
                    if ($keywords) {
                        echo Html::encode($keywords['name']);
                    }
                    else {
                        ?>
                        отсутствует
                        <?php
                    } ?>
            </td>
        </tr>

        <tr>
            <th scope='row'>Время обновления</th>
            <td><?= FormatHelper::asDateTime($model['updated_at'])
                ?></td>
        </tr>
        </tbody>
    </table>
</div>
