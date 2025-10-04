<?php
    
    use core\edit\entities\Tools\Keyword;
    use core\helpers\ButtonHelper;
    use core\helpers\types\TypeHelper;
    use yii\base\Model;
    
    /* @var $this yii\web\View */
    /* @var $model Keyword */
    /* @var $parent Model */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_keyword_view';
    
    $this->title     = $model->name;
    $parentIndexName = 'Index';
    $parentViewUrl   = TypeHelper::getView($model->text_type, $model->parent_id);
    $parentIndex     = TypeHelper::getView($parent::TEXT_TYPE);
    $parentIndexName = TypeHelper::getLabel($model->text_type);
    
    $this->params['breadcrumbs'][] = [
        'label' => $parentIndexName,
        'url'   => [$parentIndex],
    ];
    
    if ($parent) {
        $this->params['breadcrumbs'][] = [
            'label' => $parent->name,
            'url'   => [
                $parentViewUrl,
            ],
        ];
    }
    $this->params['breadcrumbs'][] = [
        'label' => 'Ключевые слова',
        'url'   => ['index'],
    ];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        ButtonHelper::activation($model),
        ButtonHelper::update($model->id, 'Редактировать'),
        ButtonHelper::create(),
        ButtonHelper::clearCache($model->site_id, $textType, $model->id),
        ButtonHelper::delete($model),
    ];
    
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
    
    
    echo '<div class="card">';

?>
    <div class='card-body'>
        
        <?= $this->render(
            '/tools/keyword/_partView',
            [
                'model' => $model,
            ],
        )
        ?>

    </div>

<?php
    echo '</div>';
