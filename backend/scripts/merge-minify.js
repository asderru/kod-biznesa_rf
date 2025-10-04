import fs from 'fs/promises';
import CleanCSS from 'clean-css';

async function mergeAndMinify() {
    try {
        // Чтение содержимого файлов
        const criticalCss = await fs.readFile('/var/www/server_1/asder_es/frontend/web/css/critical.css', 'utf8');
        const criticalMinCss = await fs.readFile('/var/www/server_1/asder_es/frontend/web/css/critical.min.css', 'utf8');
        const mainCss = await fs.readFile('/var/www/server_1/asder_es/frontend/web/css/main.css', 'utf8');

        // Объединение содержимого critical файлов
        const combinedCss = criticalCss + '\n' + criticalMinCss;

        // Минификация critical файлов
        const minifiedCritical = new CleanCSS().minify(combinedCss);

        // Запись минифицированного результата для critical.min.css
        await fs.writeFile('/var/www/server_1/asder_es/frontend/web/css/critical.min.css', minifiedCritical.styles);
        console.log('Файл critical.min.css успешно обновлен');

        // Минификация main.css
        const minifiedMain = new CleanCSS().minify(mainCss);

        // Запись минифицированного результата для main.min.css
        await fs.writeFile('/var/www/server_1/asder_es/frontend/web/css/main.min.css', minifiedMain.styles);
        console.log('Файл main.min.css успешно создан');
    } catch (error) {
        console.error('Произошла ошибка:', error);
    }
}


mergeAndMinify();
