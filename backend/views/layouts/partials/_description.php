<?php

    use core\helpers\ButtonHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;

    /* @var $model Model */
    /* @var $form ActiveForm */
    /* @var $value string */
    /* @var $textType int */
    
    $isProduct = match ($textType) {
        Constant::PRODUCT_TYPE, Constant::CHAPTER_TYPE, Constant::POST_TYPE,
        Constant::NEWS_TYPE, Constant::MATERIAL_TYPE => true,
        default                                      => false
    };
    $buttons   = $isProduct ?
        ButtonHelper::iconSubmitAction('return', 'btn btn-sm btn-success') . ButtonHelper::iconSubmitAction('next', 'btn btn-sm btn-outline-success')
        : ButtonHelper::iconSubmit();
?>

<div class="card-header bg-light d-flex justify-content-between">
    <div>
        <strong><?= $model->getAttributeLabel('description'); ?></strong>
    </div>
    <div>
        <?= ButtonHelper::iconSubmitAction('return', 'btn btn-sm btn-success')
        ?>
        <?= ButtonHelper::iconSubmitAction('next', 'btn btn-sm btn-outline-success')
        ?>
    </div>
</div>
<div class="card-body py-1 mb-0">
<?= $form->field($model, 'description', [
    'options' => ['class' => 'mb-0'],
])->textarea([
    'rows'      => 6,
    'maxlength' => $maxLetters ?? 511,
    'value'     => $value ?? null,
])->label(false) ?>

    <p class='p-1 mb-0'>
        <small>Как правило описание должно иметь около 150 знаков. Оно используется для
            заполнения мета-тега Description веб-страницы.</small>
    </p>
</div>
