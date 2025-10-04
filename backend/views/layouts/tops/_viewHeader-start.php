<?php
    
    use backend\helpers\StatusHelper;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $buttons array */
    /* @var $id int */
    /* @var $status int */
    /* @var $title string */
    const TOPS_VIEW_HEADER_LAYOUT = '#layout_top_viewHeader_start';

?>
<div class='card-header bg-light d-flex justify-content-between p-2'>
    <div class='h5'>
        <?= Html::encode($title)
        ?>
    </div>

    <div class='btn-group-sm d-grid gap-2 d-sm-block'>
        <?php
            if ($status)
                    echo
                    StatusHelper::statusLabel
                    (
                        $status,
                    ); ?>
        
        <?php
            if ($id && $status)
                try {
                    echo ($status !== Constant::STATUS_ROOT)
                        ?
                        ButtonHelper::update($id, 'Редактировать') : null;
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        'ButtonHelper::update ', TOPS_VIEW_HEADER_LAYOUT, $e,
                    );
                } ?>
        
        <?= ButtonHelper::collapse()
        ?>

    </div>
</div>

<?php
    if (isset($buttons) && is_array($buttons)) : ?>
        <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
            <?php
                foreach ($buttons as $button) {
                    echo $button;
                }
            ?>
        </div>
    
    <?php
    endif; ?>
