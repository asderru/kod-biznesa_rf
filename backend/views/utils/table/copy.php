<?php
    
    use backend\tools\TinyHelper;
    use core\edit\entities\Utils\Table;
    use core\edit\forms\Utils\Table\TableForm;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $table Table */
    /* @var $model TableForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_table_copy';
    
    $this->title                   = $table->name . '. Копирование';
    $this->params['breadcrumbs'][] = ['label' => 'Таблицы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $table->name, 'url' => ['view', 'id' => $table->id]];
    $this->params['breadcrumbs'][] = 'Правка';
    
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
    
    $form           = ActiveForm::begin(
        [
            'options'     => [
                'class' => 'active__form',
            ],
            'fieldConfig' => [
                'errorOptions' => [
                    'encode' => false,
                    'class'  => 'help-block',
                ],
            ],
        ],
    )
?>

    <div class='card'>
        
        <?= $this->render(
            '/layouts/tops/_createHeader',
            [
                'title'    => $this->title,
                'textType' => $textType,
            ],
        )
        ?>
        
        <?= $this->render(
            '@app/views/utils/table/_update',
            [
                'layoutId' => LAYOUT_ID,
                'model'    => $model,
                'form'     => $form,
            
            ],
        )
        ?>

    </div>
<?php
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
