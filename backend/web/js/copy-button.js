// web/js/copy-button.js
$(document).ready(function() {
    $('#copy-button').click(function() {
        const textToCopy = $('#copyUrl').text();
        navigator.clipboard.writeText(textToCopy)
            .then(() => alert('Ссылка скопирована!'))
            .catch(err => console.error('Ошибка копирования:', err));
    });
});
