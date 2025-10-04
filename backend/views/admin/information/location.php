<?php
    
    use core\helpers\ButtonHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\Admin\InformationForm */
    /* @var $info core\edit\entities\Admin\Information */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    LAYOUT_ID    = '#admin_information_location';
    $this->title = 'Локация';
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Информация о сайте',
        'url'   => [
            'view',
            'id' => Parametr::siteId(),
        ],
    ];
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
    
    $form = ActiveForm::begin(
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

        <div class='row mb-3'>

            <div class="col-xl-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5>Вставить координаты и масштаб (стандартный масштаб -
                            10)</h5>
                    </div>
                    <div class="card-body">
                        <?= $form
                            ->field($model, 'location')
                            ->textInput()
                            ->label('Координаты')
                        ?>
                        <small>Координаты хранятся в виде пространственных данных.
                            Отображение происходит кракозябрами (см.выше). Не
                            пугаться!
                            Копировать координаты: открыть карту Гугл, найти локацию.
                            Указать мышкой точку на карте, кликнуть по правой клавише
                            и скопировать координаты в буфер обмена. Потом вставить
                            сюда.
                        </small>
                    </div>

                    <div class="card-footer">
                        <?= $form
                            ->field($model, 'scale')
                            ->textInput()
                            ->label('Масштаб')
                        ?>
                        <small>
                            Чем больше масштаб, тем подробнее карта.
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5>Место на карте</h5>
                    </div>
                    <div class="card-body">
                        <?= $this->render(
                            '_map',
                            [
                                'model' => $info,
                                'scale' => $info?->contact?->scale / 200,
                            ],
                        )
                        ?>

                    </div>
                    <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                        <?= ButtonHelper::submit()
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php
    ActiveForm::end();
