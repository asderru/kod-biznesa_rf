document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.querySelector("#faqform-name"); // ID поля name
    const slugInput = document.querySelector("#faqform-slug"); // ID поля slug
    if (nameInput && slugInput) {
        nameInput.addEventListener("input", function () {
            const transliterated = transliterate(nameInput.value);
            slugInput.value = makeSlug(transliterated);
        });
    }

    // Функция для транслитерации текста с русского на латиницу
    function transliterate(text) {
        const charMap = {
            "а": "a", "б": "b", "в": "v", "г": "g", "д": "d",
            "е": "e", "ё": "e", "ж": "zh", "з": "z", "и": "i",
            "й": "y", "к": "k", "л": "l", "м": "m", "н": "n",
            "о": "o", "п": "p", "р": "r", "с": "s", "т": "t",
            "у": "u", "ф": "f", "х": "kh", "ц": "ts", "ч": "ch",
            "ш": "sh", "щ": "shch", "ъ": "", "ы": "y", "ь": "",
            "э": "e", "ю": "yu", "я": "ya",
            "А": "A", "Б": "B", "В": "V", "Г": "G", "Д": "D",
            "Е": "E", "Ё": "E", "Ж": "Zh", "З": "Z", "И": "I",
            "Й": "Y", "К": "K", "Л": "L", "М": "M", "Н": "N",
            "О": "O", "П": "P", "Р": "R", "С": "S", "Т": "T",
            "У": "U", "Ф": "F", "Х": "Kh", "Ц": "Ts", "Ч": "Ch",
            "Ш": "Sh", "Щ": "Shch", "Ъ": "", "Ы": "Y", "Ь": "",
            "Э": "E", "Ю": "Yu", "Я": "Ya"
        };
        return text
            .split("")
            .map(char => charMap[char] || char) // Транслитерация символов
            .join("");
    }

    // Функция для создания слага: замена пробелов на дефисы, удаление лишних символов
    function makeSlug(text) {
        return text
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, "") // Удалить всё, кроме латинских букв, цифр, пробелов и дефисов
            .replace(/\s+/g, "-") // Заменить пробелы на дефисы
            .replace(/-+/g, "-") // Удалить лишние дефисы
            .replace(/^-|-$/g, ""); // Удалить дефисы в начале и конце
    }
});
