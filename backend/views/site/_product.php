<?php
    
    
    use core\edit\entities\User\User;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\FormatHelper;
    use core\helpers\IconHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    
    /** @var View $this */
    /* @var $superadmin User */
    /* @var $models array */
    /* @var $firstModel array */
    /* @var $textType int */


?>


<div class='card border'>

    <div class='card-header px-2 pt-1'>

        <div class='row'>
            <div class='col mt-0'>
                <h5 class='card-title'>
                    <?= Html::a(
                        TypeHelper::getName($textType, null, true, true),
                        TypeHelper::getLongEditUrl($textType),
                    )
                    ?>
                </h5>
            </div>

            <div class='col-auto'>
                <?= FaviconHelper::importSmall(
                    $textType,
                )
                ?>
                <?= FaviconHelper::exportSmall(
                    $textType,
                )
                ?>
                <?= FaviconHelper::createSmall(
                    $textType,
                )
                ?>
            </div>

        </div>

        <div class='py-1'>
            <strong>
                <?= Html::encode($firstModel['name'])
                ?>
            </strong>
        </div>

        <div class='py-1'>
                <span class='badge badge-primary-light'>
                    <?= FormatHelper::asDateTime($firstModel['updated_at'])
                    ?>
                </span>
        </div>

    </div>

    <div class='card-body px-2 py-1'>
        
        <?php
            try {
                echo FaviconHelper::status($firstModel['status']);
            }
            catch (Exception $e) {
            } ?>
        <?= FaviconHelper::view(
            $textType,
            $firstModel['parent_id'],
        )
        ?>
        <?= FaviconHelper::update(
            $textType,
            $firstModel['parent_id'],
        )
        ?>
        <?= FaviconHelper::express(
            $textType, $firstModel['parent_id'],
        )
        ?>
        <?= FaviconHelper::updateHtml(
            $textType,
            $firstModel['parent_id'],
        )
        ?>
        <?= FaviconHelper::updateUpdate(
            $textType,
            $firstModel['parent_id'],
        )
        ?>
        <?= ButtonHelper::collapse(
            IconHelper::biArrowDownCircle('Развернуть'),
            '#collapseButton-' . $textType,
        )
        ?>
    </div>

    <div class='card-footer bg-body-secondary collapse' id='collapseButton-<?= $textType ?>'>
        <?php
            $i = 0;
            foreach ($models as $model):
                $i++;
                ?>

                <div class="w-100">
                    <?= $i ?>.
                    &thinsp;
                    <?php
                        try {
                            echo FaviconHelper::statusSmall($model['status']);
                        }
                        catch (Exception $e) {
                            PrintHelper::exception(
                                'shortModels', 'FaviconHelper::statusSmall ' . LAYOUT_ID, $e,
                            );
                            
                        }
                    
                    ?>
                    &thinsp;
                    <strong>
                        <?= Html::encode($model['name'])
                        ?>.
                    </strong>
                    <small class="muted">
                        <?= FormatHelper::asDateTime
                        (
                            $model['updated_at'],
                        )
                        ?>.
                    </small>
                </div>
                <div class='w-100 mb-2 p-2 border-bottom '>
                    <div class="d-flex justify-content-between">
                        <small class='muted'>
                            <?= ParametrHelper::getSiteName($model['site_id']) ?>
                        </small>
                        <div class="text-end">
                            <?= FaviconHelper::viewSmall($textType, $model['parent_id'])
                            ?>
                            <?= FaviconHelper::updateSmall($textType, $model['parent_id'])
                            ?>
                            <?= FaviconHelper::expressSmall($textType, $model['parent_id'])
                            ?>
                            <?= FaviconHelper::updateHtmlSmall($textType, $model['parent_id'])
                            ?>
                            <?= FaviconHelper::updateUpdateSmall($textType, $model['parent_id'])
                            ?>
                            <?= FaviconHelper::panelUpdateSmall($textType, $model['parent_id'])
                            ?>
                        </div>
                    </div>
                </div>
            
            
            <?php
            endforeach; ?>
    </div>

</div>
