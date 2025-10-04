<?php
    
    use core\helpers\PrintHelper;
    use core\read\widgets\nestable\Nestable;
    
    /* @var $arrayModels array */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $query  yii\db\ActiveQuery */

?>
<div class='card-body menu-index'>

    <div class='table-responsive'>
        <?php
            try {
                echo
                Nestable::widget(
                    [
                        'type'          => Nestable::TYPE_WITH_HANDLE,
                        'query'         => $query,
                        'modelOptions'  => [
                            'name' => 'name', //поле из БД с названием элемента (отображается в дереве)
                        ],
                        'pluginEvents'  => [
                            'change' => 'function(e) {}', //js событие при выборе элемента
                        ],
                        'pluginOptions' => [
                            'maxDepth' => 10, //максимальное кол-во уровней вложенности
                        ],
                    ],
                );
            }
            catch (Exception|Throwable $e) {
                PrintHelper::exception(
                    'Nestable::widget', LAYOUT_ID, $e,
                );
            }
        ?>

    </div>

</div>

<script>
    // Добавляем обработчик после полной загрузки DOM
    document.addEventListener('DOMContentLoaded', function () {
        // Находим контейнер nestable по классу dd
        const nestableContainer = document.querySelector('.dd');

        if (nestableContainer) {
            // Добавляем делегированный обработчик на контейнер
            nestableContainer.addEventListener('click', function (e) {
                // Проверяем, что клик был по контенту, а не по handle
                if (e.target.closest('.dd3-content')) {
                    // Находим ближайший родительский элемент с классом dd-item
                    const item = e.target.closest('.dd-item');
                    if (item) {
                        const id = item.getAttribute('data-id');

                        // Находим модальное окно по id
                        const modal = document.getElementById(`razdel-modal-${id}`);

                        if (modal) {
                            // Создаем экземпляр модального окна Bootstrap
                            const bsModal = new bootstrap.Modal(modal);
                            // Показываем модальное окно
                            bsModal.show();
                        }
                    }
                }
            });
        }
    });

    // Регистрируем обработчик для всех модальных окон
    document.addEventListener('show.bs.modal', function (event) {
        const modal = event.target;
        const id = modal.id.replace('razdel-modal-', '');

        // Обновляем содержимое модального окна при каждом показе
        // Это нужно делать только если данные могут измениться
        if (typeof updateModalContent === 'function') {
            updateModalContent(id);
        }
    });

    // Функция обновления содержимого модального окна
    function updateModalContent(id) {
        // Здесь можно добавить AJAX-запрос для получения актуальных данных,
        // если это необходимо

        // Пример AJAX-запроса с использованием Yii2 CSRF-токена
        /*
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/your-controller/get-model?id=' + id, {
            headers: {
                'X-CSRF-Token': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            // Обновление данных в модальном окне
            updateModalFields(data);
        })
        .catch(error => console.error('Error:', error));
        */
    }

    // Вспомогательная функция для обновления полей модального окна
    function updateModalFields(data) {
        // Пример обновления полей, настройте под свои нужды
        const modal = document.getElementById(`razdel-modal-${data.id}`);
        if (modal) {
            // Обновление заголовка
            const title = modal.querySelector('.modal-title');
            if (title) title.textContent = data.name;

            // Обновление изображения
            const img = modal.querySelector('img');
            if (img && data.photo) img.src = data.photo;

            // Обновление остальных полей
            const fields = {
                'id': data.id,
                'name': data.name,
                'slug': data.slug,
                'link': data.link,
                // Добавьте остальные поля
            };

            for (const [key, value] of Object.entries(fields)) {
                const element = modal.querySelector(`[data-field="${key}"]`);
                if (element) element.textContent = value;
            }
        }
    }
</script>
