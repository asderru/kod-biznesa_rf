<?php
    
    /* @var $this View */
    /* @var $model core\edit\entities\Admin\Information */
    
    /* @var $scale int */
    
    use yii\web\View;
    
    $mlat = null;
    $mlon = null;
    
    $contact = $model->contact;
    if ($contact) {
        $mlat = $contact->longitude;
        $mlon = $contact->latitude;
    }
    
    const MAP_LAYOUT = '#admin_information_map';

?>
<?php
    if ($mlat && $mlon): ?>
        <iframe
                width="100%" height="100%" frameborder="0" scrolling="no"
                marginheight="0" marginwidth="0"
                src="https://www.openstreetmap.org/export/embed.html?bbox=<?= ($mlon - $scale)
                ?>%2C<?= ($mlat - $scale)
                ?>%2C<?= ($mlon + $scale)
                ?>%2C<?= ($mlat + $scale)
                ?>&amp;layer=mapnik&amp;marker=<?= ($mlat)
                ?>%2C<?= ($mlon)
                ?>"
                style="border: 1px solid black"
        ></iframe>
        <br>
        <small><a href="https://www.openstreetmap.org/?mlat=<?= round($mlat, 4)
            ?>&amp;mlon=<?= round($mlon, 4)
            ?>#map=16/<?= round($mlat, 4)
            ?>/<?= round($mlon, 4)
            ?>">Посмотреть
                на
                карте</a></small>
    <?php
    endif ?>
