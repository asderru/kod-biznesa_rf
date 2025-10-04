<?php
    
    use core\helpers\ButtonHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Admin\ScaleForm */
    /* @var $info core\edit\entities\Admin\Information */
    
    const LAYOUT_ID = '#admin_information_scale';
    
    $this->title = 'Масштаб карты для сайта ' . $info->name;
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Информация о сайте',
        'url'   => ['view', 'id' => Parametr::siteId()],
    ];
    $this->params['breadcrumbs'][] = 'Масштаб';
?>

<?php
    
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

    <div class="col-xl-6">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5>Вставить масштаб (стандартный масштаб - 10).</h5>
            </div>
            <div class="card-body">
                <?= $form
                    ->field($model, 'scale')
                    ->textInput()
                    ->label('Масштаб')
                ?>
                <small>
                    Максимальный 50, минимальный - 0. Чем больше масштаб, тем
                    подробнее карта.
                </small>
            </div>
            <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                <?= ButtonHelper::submit()
                ?>
            </div>
        </div>
    </div>


<?php
    ActiveForm::end();
