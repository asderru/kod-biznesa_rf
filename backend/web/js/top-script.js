window.addEventListener('DOMContentLoaded', function () {
    // Функция для проверки наличия строки "&sort" или "Search%5" в адресе
    function checkSortParameter() {
        var url = window.location.href;
        if (url.indexOf('&sort') !== -1 || url.indexOf('Search%5') !== -1) {
            // Если в адресе есть "&sort" или "Search%5", перенаправляем на страницу с id='blog_category_view-grid'
            window.location.href = '#point-of-grid-view';
        }
    }

    // Вызываем функцию при загрузке документа
    checkSortParameter();
});
