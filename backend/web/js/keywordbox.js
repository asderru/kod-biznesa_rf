document.addEventListener('DOMContentLoaded', function () {
    // Находим кнопку для открытия блока
    var openButton = document.querySelector('.keyword-box-button');
    // Находим блок по классу
    var keywordBox = document.querySelector('.card.keyword-box');
    // Находим сайдбар по id
    var sidebar = document.getElementById('sidebar');
    // Находим кнопку для закрытия блока
    var closeButton = document.querySelector('.close-button');

    // Обработчик события нажатия на кнопку для открытия блока
    openButton.addEventListener('click', function () {
        // Проверяем, содержит ли сайдбар класс collapsed
        if (sidebar.classList.contains('collapsed')) {
            // Убираем класс collapsed
            sidebar.classList.remove('collapsed');
        }

        // Проверяем, видим ли блок
        if (keywordBox.style.display === 'none') {
            // Если блок скрыт, делаем его видимым
            keywordBox.style.display = 'block';
        } else {
            // Если блок видим, скрываем его
            keywordBox.style.display = 'none';
        }
    });

    // Обработчик события нажатия на кнопку для закрытия блока
    closeButton.addEventListener('click', function () {
        // Скрываем блок
        keywordBox.style.display = 'none';
    });
});
