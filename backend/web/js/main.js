$(document).ready(function () {
    //При нажатии левая панель исчезает/появляется
    $('.js-sidebar-toggle').on('click', function () {
        $('#sidebar').toggleClass('collapsed');
    });

//Making screen full
    $('.js-fullscreen').on('click', function (e) {
        e.preventDefault(); // Предотвращаем переход по ссылке

        var elem = document.documentElement;

        if (!document.fullscreenElement &&
            !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    });

// choose the color csheme
    const colorLightBtn = document.getElementById('color-light');
    const colorSemiBtn = document.getElementById('color-semi');
    const colorDarkBtn = document.getElementById('color-dark');
    const colorSchemeLink = document.getElementById('color-scheme');
    const body = document.querySelector('body');

    // light scheme
    colorLightBtn.addEventListener('click', function () {
        colorSchemeLink.href = "/css/light.css?v=1712620514";
        body.setAttribute('data-theme', 'light');
    });

    // semi scheme
    colorSemiBtn.addEventListener('click', function () {
        colorSchemeLink.href = "/css/light.css?v=1712620514";
        body.setAttribute('data-theme', 'default');
    });

    // dark scheme
    colorDarkBtn.addEventListener('click', function () {
        colorSchemeLink.href = "/css/dark.css?v=1712620514";
        body.setAttribute('data-theme', 'dark');
    });

// Получаем все кнопки с атрибутом data-action="seek-words"
    const keywordButtons = document.querySelectorAll('[data-action="seek-words"]');
    const textArea = document.getElementById('text-edit-area');

// Добавляем обработчик событий для каждой кнопки
    keywordButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Получаем текст из текстовой области
            const text = textArea.value;

            // Получаем ключевое слово из кнопки
            const keyword = button.textContent.trim();

            // Создаем регулярное выражение для поиска ключевого слова
            const regex = new RegExp(keyword, 'gi');

            // Находим все совпадения
            const matches = text.match(regex) || [];

            // Выводим всплывающее окно с количеством найденных слов
            alert(`Найдено ${matches.length} вхождений "${keyword}"`);
        });
    });


});
