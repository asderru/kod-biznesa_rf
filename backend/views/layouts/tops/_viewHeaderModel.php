<?php
    
    use backend\helpers\StatusHelper;
    use backend\helpers\UrlHelper;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\IconHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model Model|Razdel */
    /* @var $buttons array */
    /* @var $textType int */
    
    const HEADER_MODEL_LAYOUT = '#views_layouts_partials_viewHeaderModel';
    
    $keyword      = null;
    $title        = $model->name;
    $modelSeoLink = Url::to($model['link'], true);
    $status       = $model->status;
    
    
    $isPromoted = ParentHelper::isPromoted($textType);

    
    if (property_exists($model, 'title')) {
        $title = $model->title;
    }

?>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div>
            <h4>
                <?= FaviconHelper::getTypeFavSized($textType, 2) . ' ' . Html::encode($title)
                ?>
            </h4>
            <small>
                <?= StatusHelper::statusBadgeLabel($model->status) ?>
            </small>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?php
                try {
                    echo ($status > Constant::STATUS_ROOT) ? ButtonHelper::update($model->id, 'Редактировать') : '<span class="btn btn-sm bg-danger text-white">Корневая модель</span>';
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        'ButtonHelper_update', HEADER_MODEL_LAYOUT, $e,
                    );
                } ?>
            <?= ($isPromoted) ? Html::a(
                IconHelper::biEye('Смотреть'),
                [
                    'admin/view/view',
                    'textType' => $textType,
                    'id'       => $model->id,
                ],
                [
                    'class'             => 'btn btn-sm btn-primary mb-1 mr-1',
                    'data-bs-toggle'    => 'tooltip',
                    'data-bs-placement' => 'bottom',
                    'title'             => 'Смотреть',
                    'target'            => '_blank',
                ],
            ) : null;
            
            ?>
            
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

<?php
    
    $js                       = <<<EOD
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
