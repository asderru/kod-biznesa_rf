<?php
    
    use core\helpers\ButtonHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $roots array */
    /* @var $files array */
    /* @var $path string */
    
    const EXPORT_LAYOUT = '#layouts_partials_export';
    echo PrintHelper::layout(EXPORT_LAYOUT);
?>

<div class='row'>

    <div class='col-lg-6'>
        Выгрузить в XLSX:

        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?php
                try {
                    foreach (ParametrHelper::getSites() as $site): ?>
                        <?= ButtonHelper::export
                        (
                            $site->id, $site->name, true,
                        )
                        ?>
                        <?= ButtonHelper::export
                        (
                            $site->id, $site->name,
                        )
                        ?>
                        <br>
                    <?php
                    endforeach;
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        'ParametrHelper::getSites', EXPORT_LAYOUT, $e,
                    );
                }
            ?>
        </div>
    </div>

    <div class='col-lg-6'>
        Скачать файлы / удалить:

        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?php
                foreach ($files as $file): ?>
                    <?= ButtonHelper::downloadExport
                    (
                        $file, $path,
                    )
                    ?> /
                    <?= ButtonHelper::deleteExport
                    (
                        $file, $path,
                    )
                    ?>
                <?php
                endforeach;
            ?>
        </div>
    </div>
</div>
