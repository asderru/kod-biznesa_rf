<?php
    
    use yii\web\View;
    
    /* @var $height int */
    
    $src = Yii::$app->params['backendHostInfo'] . '/upload.php';
    
    $this->render('_tinyRef.php');
    
    $this->registerJs(
        "function image_upload_handler_callback (blobInfo, success, failure, progress) {
		  var xhr, formData;
		
		  xhr = new XMLHttpRequest();
		  xhr.withCredentials = false;
		  xhr.open('POST', '/upload.php');
		
		  xhr.upload.onprogress = function (e) {
		    progress(e.loaded / e.total * 100);
		  };
		
		  xhr.onload = function() {
		    var json;
		
		    if (xhr.status === 403) {
		      failure('HTTP Error: ' + xhr.status, { remove: true });
		      return;
		    }
		
		    if (xhr.status < 200 || xhr.status >= 300) {
		      failure('HTTP Error: ' + xhr.status);
		      return;
		    }
		
		    json = JSON.parse(xhr.responseText);
		
		    if (!json || typeof json.location != 'string') {
		      failure('Invalid JSON: ' + xhr.responseText);
		      return;
		    }
		
		    success(json.location);
		  };
		
		  xhr.onerror = function () {
		    failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
		  };
		
		  formData = new FormData();
		  formData.append('file', blobInfo.blob(), blobInfo.filename());
		
		  xhr.send(formData);
		};
		
		tinymce.init({
				selector: '#text-edit-area',
				content_style: 'body {font-family: Ubuntu; font-size: 18px;  line-height: 32px; color: #444}',
				language: 'ru',
				menubar: 'edit insert view format table',
				plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code quickbars pagebreak',
                toolbar: 'code searchreplace | blocks | bold italic blockquote codesample removeformat | link image media | align  numlist bullist indent outdent | charmap  fontfamily fontsize ',
                toolbar_sticky: false,
				image_class_list: [
			         {title: 'Адаптивный - img-fluid', value: 'img-fluid'}
				  ],
	            min_height: $height,
		        images_upload_url: '/upload.php',
		        images_upload_handler: 'image_upload_handler_callback',
		        extended_valid_elements : 'img[class|src|alt|title|width|height|style|loading=lazy]',
		        image_caption: true,
				image_title: true,
                images_file_types: 'jpg, jpeg, webp',
                object_resizing: 'img',
                resize_img_proportional: true,
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
                  ]
                    })",
        View::POS_READY,
    );
