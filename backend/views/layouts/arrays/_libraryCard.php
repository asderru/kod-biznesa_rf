<?php

    use core\helpers\FormatHelper;
    use yii\bootstrap5\Html;

    /* @var $libraryCard array */
    /* @var $previousTime int */

?>

<?php
    if ($libraryCard):
        ?>
        <div class='card-header'>
            <h5 class='card-title mb-0'>
                <i class='fas fa-clock me-2'></i>
                Управление временем
            </h5>
        </div>
        <div class='card-body'>

            <?php

                if ($previousTime): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <div>Время предыдущей карточки</div>
                        <strong id='prev-card-date-value'><?= FormatHelper::getDateTime($previousTime, true) ?></strong>
                    </div>
                <?php
                endif; ?>


            <?php
                if ($libraryCard['date']): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <div>Дата действия</div>
                        <strong id='prev-card-date-value'><?= FormatHelper::getDate($libraryCard['date'], true) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div>День недели</div>
                        <strong id='prev-card-date-value'><?= FormatHelper::getWeekday($libraryCard['date'], true) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div>Время</div>
                        <strong id='prev-card-date-value'><?= FormatHelper::getTime(
                                    $libraryCard['date'],
                                    true,
                            ) ?></strong>
                    </div>

                    <div class='d-flex justify-content-between mb-2'>
                        <div>Дата и время</div>
                        <strong id='prev-card-date-value'>
                            <?= date('d.m.Y H:i', $libraryCard['date']) ?>
                        </strong>
                    </div>

                <?php
                else: ?>
                    <strong>Время действия не указано</strong><br>

                <?php
                endif; ?>

            <?= Html::a(
                    '<i class="fas fa-edit me-2"></i>Изменить дату и время',
                    ['/library/time/update', 'id' => $libraryCard['id']],
                    [
                            'class'          => 'btn btn-primary w-100 mb-2',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#datetime-modal-' . $libraryCard['id'],
                    ],
            ) ?>

            <?= Html::button(
                    '<i class="fas fa-clock me-2"></i>Установить текущее время',
                    [
                            'class'   => 'btn btn-outline-success w-100',
                            'onclick' => 'setCurrentTime(' . $libraryCard['id'] . ')',
                    ],
            ) ?>
        </div>

    <?php
    endif; ?>
