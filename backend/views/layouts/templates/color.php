<?php
    
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\ColorForm;
    use core\helpers\ButtonHelper;
    use core\helpers\types\TypeHelper;
    use core\read\readers\Admin\SiteModeReader;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $parent Razdel|Category| */
    /* @var $model ColorForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const TEMPLATES_COLOR_LAYOUT = '#library_parent_color';
    
    $this->title                   = $parent->name . '. Цветовая гамма';
    
    $url = TypeHelper::getLongEditUrl($textType);
    
    $this->params['breadcrumbs'][] = [
        'label' => TypeHelper::getName($textType, null, true, true),
        'url'   => [$url . 'index'],
    ];
    
    $this->params['breadcrumbs'][] = [
        'label' => $label,
        'url'   => [$url . 'view', 'id' => $parent->id],
    ];
    
    $this->params['breadcrumbs'][] = 'Цвет';
?>


<?= $this->render(
    '/layouts/tops/_infoHeader',
    [
        'label'    => $label,
        'textType' => $textType,
        'prefix'   => $prefix,
        'actionId' => $actionId,
        'layoutId' => TEMPLATES_COLOR_LAYOUT,
    ],
)
?>

<?php
    $form                        = ActiveForm::begin(
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
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header bg-body-secondary">
                    <strong>Выбрать цветовое решение для <?= $parent->name ?></strong>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'color')
                        ->radioList(SiteModeReader::colorList())
                             ->label(false)
                    ?>
                </div>
                <div class='card-footer'>
                    <?= ButtonHelper::submit()
                    ?>
                </div>
            </div>


        </div>

    </div>
<?php
    ActiveForm::end();
