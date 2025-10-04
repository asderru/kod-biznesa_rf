<?php
    
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\db\ActiveRecord;
    
    /**
     * @var ActiveRecord $model
     */
    
    return match ($model->hasKeywords()) {
        Constant::KEYWORD_EXISTS   => 'Ключевое слово не определено.',
        Constant::KEYWORD_ASSIGNED =>
            '<small>Ключевое слово: </small><strong>«' . Html::encode($model->getMainKeyword()) . '»</strong>',
        default                    => null,
    };
