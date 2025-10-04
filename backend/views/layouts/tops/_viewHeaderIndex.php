<?php
    
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $title string */
    /* @var $textType int */


?>
<style>
    .filter-btn {
        margin-right: 5px;
        margin-bottom: 10px;
    }
</style>

<div class='card-header bg-light d-flex justify-content-between'>
    <div>
        <h4>
            <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($title)
            ?>
        </h4>
    </div>

    <div class='btn-group-sm d-grid gap-2 d-sm-block'>
        <?= ButtonHelper::create() ?>
        <?= ButtonHelper::refresh(); ?>
        <?= ButtonHelper::collapse() ?>
    </div>
</div>
<?php
    if (isset($buttons) && is_array($buttons)) : ?>
        <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
            <?php
                foreach ($buttons as $button) {
                    echo $button;
                }
            ?>
        </div>
    <?php
    endif; ?>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        // Получаем все кнопки фильтрации сайтов и товаров
        const siteBtns = document.querySelectorAll('[id^="site-"]');
        const productBtns = document.querySelectorAll('.btn-sm.resort-part');

        // Изначально скрываем все кнопки товаров
        productBtns.forEach(productBtn => {
            productBtn.style.display = 'none';
        });

        // Обработчик клика по кнопке сайта
        siteBtns.forEach(siteBtn => {
            siteBtn.addEventListener('click', function () {
                const siteId = this.id; // Получаем id выбранного сайта

                // Деактивируем все кнопки сайтов
                siteBtns.forEach(btn => {
                    btn.classList.remove('active');
                });

                // Активируем текущую кнопку
                this.classList.add('active');

                // Скрываем все кнопки товаров
                productBtns.forEach(productBtn => {
                    productBtn.style.display = 'none';
                });

                // Показываем кнопки товаров для выбранного сайта
                const siteProductBtns = document.querySelectorAll(`.${siteId}`);
                siteProductBtns.forEach(productBtn => {
                    productBtn.style.display = 'inline-block';
                });
            });
        });

        // Добавляем кнопку "Показать все"
        const filterContainer = document.querySelector('.filter-container'); // Замените на реальный селектор контейнера
        const showAllBtn = document.createElement('button');
        showAllBtn.textContent = 'Показать все';
        showAllBtn.classList.add('btn', 'btn-outline-secondary', 'ms-2');

        showAllBtn.addEventListener('click', function () {
            // Деактивируем все кнопки сайтов
            siteBtns.forEach(btn => {
                btn.classList.remove('active');
            });

            // Скрываем все кнопки товаров
            productBtns.forEach(productBtn => {
                productBtn.style.display = 'none';
            });
        });

        filterContainer.appendChild(showAllBtn);
    });

</script>
