<?php
    
    use core\edit\entities\Shop\Razdel;
    use core\tools\Constant;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\web\JqueryAsset;
    use yii\web\View;
    
    $this->registerJsFile('@web/js/keywordbox.js', ['depends' => [JqueryAsset::class]]);
    
    /* @var $this View */
    /* @var $model Model|Razdel */

?>


<?php
    $keyword     = null;
    
    if ($model->hasKeywords() === Constant::KEYWORD_ASSIGNED):
        $keywords = $model->getKeywords()->one();
        $keyword = Html::encode($model->getMainKeyword());
        $status  = $keywords->status;
        $class   = ($status > Constant::STATUS_ARCHIVE) ? "btn btn-sm btn-outline-success" : "btn btn-sm btn-secondary";
        ?>

        <div class='card-header bg-light d-flex justify-content-between'>

            <div>
                Ключевое слово:
                <a href='#' class='<?= $class ?>'
                   data-bs-toggle='popover'
                   data-bs-trigger='hover'
                   data-bs-placement='bottom'
                   title='Ключевые слова'
                   data-bs-content='<?php
                       echo $model->getKeywordsString(); ?>'>
                    <?= $keyword ?>
                </a>
            </div>
            <div>
                <?= Html::button('<i class="fa-solid fa-arrow-up-right-from-square"></i>', [
                    'class' => 'keyword-box-button btn btn-sm btn-outline-primary',
                ],
                )
                ?>
            </div>

        </div>
    <?php
    elseif ($model->hasKeywords() === Constant::KEYWORD_EXISTS):?>
        Ключевое слово отсутствует
    
    
    <?php
    else:?>
    
    <?php
    endif; ?>
<?php
    if ($model->hasKeywords() === Constant::KEYWORD_ASSIGNED): ?>
        <div class='card keyword-box' style='display: none;'>
            <div class="card-header bg-light">
                <?= Html::button('<i class="fa-regular fa-circle-xmark"></i>', [
                    'class' => 'close-button btn btn-sm btn-outline-primary',
                ],
                )
                ?>
                Ключевое слово: <br>
                <strong> <?= $keyword ?></strong>

            </div>
            <div class="card-body">
                <?php
                    echo
                    $model->getKeywordsList(); ?>
            </div>
            <div class="card-footer text-end">

            </div>
        </div>
    
    <?php
    endif; ?>
<?php
    
    
    // Ваш код контроллера или представления
    
    $this->registerJs(
        '
    let popoverTriggerList = [].slice.call(
        document.querySelectorAll(\'[data-bs-toggle="popover"]\'));
      
    let popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
', View::POS_READY,
    );
?>
