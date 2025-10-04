<?php
    
    use yii\web\View;
    
    /* @var $height int */
    
    $this->render('_tinyRef.php');
    
    $this->registerJs(
        "tinymce.init({
        selector: '#description-edit-area',
        language: 'ru',
        menubar: false,
        plugins: 'codesample lists visualblocks wordcount code charactercount',
        toolbar: 'undo redo | code removeformat | bold italic | align lineheight | blocks fontfamily fontsize ',
        toolbar_sticky: true,
        min_height: 200,
    });",
    );
    
    $this->registerJs(
        "
    function characterCount() {
        const wordCount = tinymce.activeEditor.plugins.wordcount;
        alert(wordCount.body.getCharacterCountWithoutSpaces());
    }
    var buttonCount = document.getElementById('CharButton');
    buttonCount.addEventListener('click', characterCount, false);
    ",
        View::POS_READY,
    );
