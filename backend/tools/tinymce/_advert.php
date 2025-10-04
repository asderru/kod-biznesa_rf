<?php
    
    use yii\web\View;
    
    /* @var $height int */
    
    $this->render('_tinyRef.php');
    
    $this->registerJs(
        "tinymce.init({
		selector: '#advert-edit-area',
		content_style: 'body {font-family: Ubuntu; font-size: 18px;  line-height: 32px; color: #444}',
		language: 'ru',
		menubar: false,
        plugins: 'codesample lists media visualblocks wordcount code',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic | align lineheight | removeformat code',
        toolbar_sticky: true,
        min_height: $height,
    })",
        View::POS_READY,
    );
