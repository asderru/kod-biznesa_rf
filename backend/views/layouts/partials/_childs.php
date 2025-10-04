<?php
    
    use core\helpers\ButtonHelper;
    use core\helpers\ModelHelper;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $childs Model[] */
    /* @var $label string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const PARTIALS_CHILD_LAYOUT = '#layouts_partials_childs';
    echo PrintHelper::layout(PARTIALS_CHILD_LAYOUT);

?>

<div class="card-header bg-body-secondary">
    
    <?= ButtonHelper::collapse($label ?? 'Вложенные тексты', '#collapsePartialButtons')
    ?>

</div>
<div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapsePartialButtons'>
    <?php
        $i = 0;
        foreach ($childs as $model) {
            $i++;
            ?>
            <?= $i ?>. <?= Html::a(
                Html::encode($model->name),
                ModelHelper::getView($model),
            )
            ?>. <?= Html::a(
                ' <i class="bi bi-pencil-fill"></i> ',
                ModelHelper::getView($model),
                [
                    'class' => 'badge bg-success',
                ],
            )
            ?>

            <br>
            
            <?php
        }
    ?>

</div>
