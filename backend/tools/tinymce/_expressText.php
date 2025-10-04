<?php
    
    use yii\web\View;
    
    /* @var $height int */
    
    $this->render('_tinyRef.php');
    
    $this->registerJs(
        "tinymce.init({
		selector: '#express-edit-area',
		content_style: 'body {font-family: Ubuntu; font-size: 18px;  line-height: 32px; color: #444}',
		language: 'ru',
		menubar: false,
        plugins: 'codesample lists image visualblocks wordcount link code quickbars pagebreak',
        toolbar: 'undo redo | blocks | bold italic link image | align numlist bullist indent outdent code removeformat | fontfamily fontsize ',
                  link_rel_list: [
                    {title: 'None', value: ''},
                    {title: 'Внешняя, доверяю', value: 'noopener noreferrer'},
                    {title: 'Внешняя, не доверяю', value: 'noopener nofollow'},
                    {title: 'Реклама', value: 'noindex sponsored nofollow'},
                    {title: 'Комментарий', value: 'noindex ugc nofollow'}
                  ],
                  link_class_list: [
                    {title: 'None', value: ''},
                    {title: 'External Link', value: 'ext_link'},
                    {title: 'Internal Support Link', value: 'int_sup_link'},
                    {title: 'Internal Marketing Link', value: 'int_mark_link'},
                    {title: 'Other Internal Link', value: 'int_other_link'}
                  ],
        toolbar_sticky: true,
        min_height: $height,
    })",
        View::POS_READY,
    );
