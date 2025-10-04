<?php
    
    /* @var $buttons array */

?>


<div class='card-header bg-light d-flex justify-content-end p-2'>
    <?php
        foreach ($buttons as $button):
            
            echo $button . ' ';
        
        endforeach;
    ?>
</div>
