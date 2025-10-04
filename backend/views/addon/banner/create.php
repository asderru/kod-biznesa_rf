<?php
    
    use backend\helpers\SelectHelper;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\Addon\BannerForm;
    use core\helpers\ButtonHelper;
    use core\helpers\ModelHelper;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $parent Model|Razdel */
    /* @var $model BannerForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#addon_banner_create';
    
    $this->title                   = 'Баннер для ' . $parent->name;
    $this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Новый баннер';
    
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
    );

?>

    <div class="container">
        <div class="card">
            
            <?php
                $this->render(
                    '/layouts/tops/_createHeader',
                    [
                        'title'    => $this->title,
                        'textType' => $textType,
                    ],
                );
            ?>
            <div class='card-body'>

                <div class="row">

                    <div class="col-md-6">

                        <div class='card h-100'>

                            <div class='card-header bg-light'>
                                Название баннера (для использования в панели управления)
                            </div>

                            <div class="card-body">
                                <?= $form
                                    ->field($model, 'name')
                                    ->textInput(
                                        [
                                            'maxlength' => true,
                                            'value'     => $parent->name,
                                        ],
                                    )
                                    ->label(false)
                                ?>
                            </div>

                            <div class='card-header bg-light'>
                                Текст баннера (для использования в рекламе)
                            </div>

                            <div class='card-body'>
                                <?= $form
                                    ->field(
                                        $model,
                                        'description',
                                    )
                                    ->textarea(
                                        [
                                            'rows'  => 6,
                                            'value' => strip_tags($parent->description),
                                        ],
                                    )
                                    ->label('Текст баннера. Макс - 100 знаков')
                                ?>

                                <div class='d-flex justify-content-between'>
                                    <?= $form->field($model, 'rating')
                                             ->textInput(
                                                 [
                                                     'type'  => 'number',
                                                     'min'   => 1,
                                                     'max'   => 100,
                                                     'value' => 10, // Устанавливаем значение по умолчанию 1
                                                 ],
                                             )
                                             ->label('Рейтинг от 1 до 100')
                                    ?>
                                    <?= SelectHelper::status($form, $model) ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class='card h-100'>
                            
                            <?php
                                try {
                                    echo
                                    Html::img(
                                        $parent->getImageUrl(6),
                                        [
                                            'class' => 'card-img-top',
                                        ],
                                    
                                    );
                                }
                                catch (Throwable $e) {
                                
                                }
                            ?>

                            <div class='card-body'>
                                
                                <?= $form
                                    ->field($model, 'reference')
                                    ->textInput(
                                        [
                                            'maxlength' => true,
                                            'value'     => ModelHelper::getSiteView($parent),
                                        ],
                                    )
                                    ->label('Ссылка')
                                ?>
                                Адрес картинки: <?= $parent->getImageUrl(6); ?>
                            </div>

                            <div class="card-footer">
                                
                                <?= ButtonHelper::submit()
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    echo $form->field($model, 'site_id')
              ->hiddenInput(['value' => $parent->site_id])->label(false)
    ;
    echo $form->field($model, 'siteMode')
              ->hiddenInput(['value' => $parent->siteMode])->label(false)
    ;
    echo $form->field($model, 'textType')
              ->hiddenInput(['value' => $parent->textType])->label(false)
    ;
    echo $form->field($model, 'parentId')
              ->hiddenInput(['value' => $parent->id])->label(false)
    ;
    try {
        echo $form->field($model, 'picture')
                  ->hiddenInput(['value' => $parent->getImageUrl(6)])->label(false)
        ;
    }
    catch (Throwable $e) {
    
    }
    
    ActiveForm::end();
