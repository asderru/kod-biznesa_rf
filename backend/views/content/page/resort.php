<?php
    
    use backend\helpers\BreadCrumbHelper;
    use core\helpers\ButtonHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $arrayModels array */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $sites array */
    /* @var $query  yii\db\ActiveQuery */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $siteId int */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_page_resort';
    
    $siteId = $site->id;
    
    $this->title = 'Сортировка ' . TypeHelper::getName($textType, 2, true) . '. Сайт ' . $site->name . '. #' .
                   $site->id;
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::structure($textType);
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Сортировка';
    
    echo $this->render(
        '/layouts/tops/_infoHeader',
        [
            'label'    => $label,
            'textType' => $textType,
            'prefix'   => $prefix,
            'actionId' => $actionId,
            'layoutId' => LAYOUT_ID,
        ],
    );
?>

<div class='card'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <?= ButtonHelper::createType($textType, $site->id, 'Добавить', 'success') ?>
            <?= ButtonHelper::clearCache($site->id, $textType)
            ?>
            
            <?php
                echo
                ButtonHelper::refreshSort($site->id); ?>
            <?= ButtonHelper::collapse()
            ?>
        </div>
    </div>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?= ButtonHelper::expressType($textType)
        ?>
        <?=
            ButtonHelper::structure($textType, null,'Структура ' . TypeHelper::getName($textType, 2, true))
        ?>
        <hr>

    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $this->render(
                '/layouts/sort/_sortCat',
                [
                    'query'       => $query,
                    'arrayModels' => $arrayModels,
                ],
            )
            ?>

        </div>
        <div class='col-md-4'>
            <div class="card">
                <div class="card-header">
                    Сортировать <?= TypeHelper::getName($textType, null, true) ?> на сайтах:
                </div>
                <div class="card-body">
                    <?php
                        foreach ($sites as $site):
                            if ($site['id'] !== $siteId) {
                                echo ButtonHelper::resort
                                (
                                    $site['id'], $site['name'],
                                );
                            }
                        endforeach;
                    ?>

                </div>
            </div>
