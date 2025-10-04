<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $models Model|Razdel|Product[] */
    /* @var $typeName string */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_update_check';
    
    $this->title = 'Проверка изображений для ' . $typeName;
    
    $this->params['breadcrumbs'][] = $this->title;
    
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

<div class='card rounded-0 mb-3'>
    <div class='card-body'>
        <div class="table-responsive">
            <table class='table'>
                <thead>
                <tr>
                    <th scope='col'>#</th>
                    <th scope='col'>Название</th>
                    <th scope='col'>Фото</th>
                </tr>
                </thead>
                <tbody>
        </div>
        <?php
            foreach ($models as $model) :
                ?>
                <tr>
                    <td><?= $model->id ?></td>
                    <td><?= $model->name ?>
                        <?php
                            try {
                                echo
                                $model->getImageUrl(3);
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    $actionId, 'model->getImageUrl' . LAYOUT_ID, $e,
                                );
                            } ?></td>
                    <td style="width: 330px">
                        <img src="
                <?php
                            try {
                                echo
                                $model->getImageUrl(3);
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    $actionId, 'model->getImageUrl' . LAYOUT_ID, $e,
                                );
                            }
                        ?>" class='img-fluid lazy-load'
                             alt="<?= htmlspecialchars($model->name)
                             ?>">
                    </td>

                </tr>
            <?php
            endforeach; ?>
    </div>
</div>
