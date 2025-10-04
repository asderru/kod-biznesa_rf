<?php
    
    use core\edit\search\Utils\PhotoSearch;
    use core\helpers\ButtonHelper;
    use core\tools\Constant;
    
    /* @var $this yii\web\View */
    /* @var $searchModel PhotoSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $galleries core\edit\entities\Utils\Gallery[] */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_photo_index';
    $label    = 'Изображения в галереях';
    $textType = Constant::GALLERY_TYPE;
    
    $this->title = $label;
    
    $buttons = [];
    
    
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

<div class='card'>
    
    <?= $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
			<span class='small strong'>Упорядочить фото в галереях:
				</span>
        <?php
            foreach ($galleries as $resort) {
                echo ButtonHelper::resort($resort['id'], $resort['name']);
            } ?>

    </div>
        <?= $this->render(
            '@app/views/utils/photo/_partIndex',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ],
        )
        ?>
</div>
