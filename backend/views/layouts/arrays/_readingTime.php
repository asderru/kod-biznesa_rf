<?php
    
    use core\helpers\ReadHelper;
    
    /* @var $text string */

?>
<div class='d-flex justify-content-between'>
    <div><small>Время чтения:</small> <strong><?= ReadHelper::getReadingTime($text)
            ?></strong>мин.
    </div>
    <div><small>Слов:</small> <strong><?= ReadHelper::getWordCount($text)
            ?></strong></div>
    <div><small>Знаков:</small> <strong><?= ReadHelper::getCharactersCount($text)
            ?></strong></div>
</div>
