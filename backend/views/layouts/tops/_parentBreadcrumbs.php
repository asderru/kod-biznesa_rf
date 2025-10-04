<?php
    
    use core\edit\entities\Shop\Razdel;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\base\Model;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model Model|Razdel */
    /* @var $parent Model|Razdel */
    
    $actionId = '#views_layouts_tops__parentBreadcrumbs';
    
    $parentIndexName = 'Index';
    $parentViewUrl = TypeHelper::getView($model->text_type, $model->parent_id);
    $parentIndex = TypeHelper::getView($parent::TEXT_TYPE);
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
