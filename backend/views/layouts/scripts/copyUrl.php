<?php
    
    /* @var $this View */
    
    use yii\web\View;
    
    $js = <<<EOD
$(document).ready(function() {
   $('#copy-button').click(function() {
        var textToCopy = $('#copyUrl').text();
        var tempTextarea = $('<textarea>');
        $('body').append(tempTextarea);
        tempTextarea.val(textToCopy).select();
        document.execCommand('copy');
        tempTextarea.remove();
        alert('Ссылка скопирована!');
      });
});
EOD;
    $this->registerJs($js);
