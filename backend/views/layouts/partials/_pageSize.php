<?php
    
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /* @var $this View */
    $pageSizeOptions = [20 => '20', 50 => '50', 100 => '100', 250 => '250 строк'];
    
    // Получение текущего значения pageSize из запроса или использование значения по умолчанию
    $pageSize = Yii::$app->request->get('pageSize', 50);  // 50 - значение по умолчанию
?>
<div class='page-size-buttons__area px-4'>
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-3 align-items-center'])
    ?>
    
    <?php
        foreach ($pageSizeOptions as $value => $label): ?>
            <div class="col-auto">
                <div class="form-check form-check-inline">
                    <?= Html::radio('pageSize', $pageSize == $value, [
                        'class' => 'form-check-input',
                        'id'    => 'pageSize' . $value,
                        'value' => $value,
                    ]); ?>
                    <?= Html::label($label, 'pageSize' . $value, ['class' => 'form-check-label'])
                    ?>
                </div>
            </div>
        <?php
        endforeach;
    ?>

    <div class="col-auto">
        <?= Html::submitButton('Применить', ['class' => 'btn btn-sm btn-outline-dark'])
        ?>
    </div>
    
    <?= Html::endForm()
    ?> <!-- Закрываем форму -->
</div>
