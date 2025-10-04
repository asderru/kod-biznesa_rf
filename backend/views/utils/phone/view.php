<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use yii\widgets\DetailView;
    
    /** @var yii\web\View $this */
    /** @var core\edit\entities\Utils\Phone $model */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_phone_view';
    
    $this->title = 'Информация по тел.: ' . $model->getFormattedPhone();
    
    $buttons = [
        ButtonHelper::activate($model->id),
        ButtonHelper::clearCache($model->site_id, $textType, $model->id),
        ButtonHelper::delete($model),
    ];
    
    $this->params['breadcrumbs'][] = ['label' => 'Все телефоны', 'url' => ['index']];
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
    
    try {
        echo
        PagerWidget::widget(
            [
                'model'  => $model,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            LAYOUT_ID, 'PagerWidget_widget', $e,
        );
    }
?>

<div class="card">;
    
    <?= $this->render(
        '/layouts/tops/_viewHeader-start',
        [
            'id'      => $model->id,
            'title'   => 'Информация по телефону: ' . $model->getFormattedPhone(),
            'status'  => $model->status, // передаем массив кнопок
            'buttons' => $buttons, // передаем массив кнопок
        ],
    )
    ?>

    <div class='card-body mb-3'>

        <div class='row mb-3'>

            <div class='col-xl-6'>

                <div class='card'>

                    <div class='card-header bg-light'>
                        <strong>
                            Информация
                        </strong>
                    </div>
                    <div class='card-body'>
                        
                        <?php
                            try {
                                echo DetailView::widget([
                                    'model'      => $model,
                                    'attributes' => [
                                        'id',
                                        'site_id',
                                        'text_type',
                                        'parent_id',
                                        'name',
                                        'country_code',
                                        'phone',
                                    ],
                                ]);
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'Phone_DetailView_widget', LAYOUT_ID, $e,
                                );
                            } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
