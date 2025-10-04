<?php
    
    use core\helpers\FaviconHelper;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\FormatHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $parent Razdel|Model */

?>

<div class="card-header bg-body-secondary">Родительская модель:
    <strong>
        <?= Html::encode($parent->name)
        ?>
    </strong>. Полное название:
    <strong>
        <?= Html::encode($parent->title)
        ?>
    </strong>.
    <?= FaviconHelper::modelView($parent, true)
    ?>
    <?= FaviconHelper::express($parent::TEXT_TYPE, $parent->id)
    ?>
    <button class='btn btn-sm btn-outline-secondary' type='button' data-bs-toggle='offcanvas'
            data-bs-target='#offcanvasRight'
            aria-controls='offcanvasRight'>Читать
    </button>
</div>
<div class='offcanvas offcanvas-end' tabindex='-1' id='offcanvasRight' aria-labelledby='offcanvasRightLabel'>
    <div class='offcanvas-header'>
        <h5 class='offcanvas-title' id='offcanvasTopLabel'><?= Html::encode($parent->title)
            ?></h5>
        <button type='button' class='btn-close' data-bs-dismiss='offcanvas' aria-label='Close'></button>
    </div>
    <div class='offcanvas-body'>
        <?= FormatHelper::asHtml($parent->text)
        ?>
    </div>
</div>
