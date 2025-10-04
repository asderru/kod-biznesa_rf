<?php
    
    use core\edit\entities\Tools\Draft;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $drafts Draft[] */
    
    const PARTIALS_DRAFT_LAYOUT = '#layouts_partials_draft';
    echo PrintHelper::layout(PARTIALS_DRAFT_LAYOUT);

?>
<div class='card mb-3 border border-secondary-subtle'>

    <div class="card-header bg-body-secondary">

        Черновики

    </div>
    <div class='card-body mb-2'>
        <?php
            $i = 0;
            foreach ($drafts as $model) {
                $i++;
                ?>
                <?= $i ?>. <?= Html::a(
                    Html::encode($model->name),
                    [
                        'tools/draft/view',
                        'id' => $model->id,
                    ],
                )
                ?>.

                <br>
                
                <?php
                
            }
        ?>

    </div>

</div>
