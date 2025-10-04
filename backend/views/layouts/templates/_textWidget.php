<?php
    
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Forum\Group;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model Razdel|Category|Page|Group|Book|Section */
    
    $layoutId = '#layouts_templates_textWidget';

?>

<div class='card mb-3'>

    <div class='card-header bg-light'>
        <strong>
            Текст
        </strong>
    </div>

    <div class='card-body' id="card-body-text">
        <?= FormatHelper::asHtml($model->text)
        ?>
    </div>
    <div class="card-footer">
        <?php
            $text
                = $model->text !== null ? strip_tags($model->text) : null;
        ?>
        <?php
            if ($text) { ?>
                В тексте - <?= str_word_count($text, 0, 'АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя')
                ?>
                слов, <?= mb_strlen($text)
                ?> знаков.
                <?php
            } ?>
    </div>
</div>
