<?php
    
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use core\read\readers\Admin\InformationReader;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\Utils\Gallery\GalleryForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\base\Model;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model GalleryForm */
    /* @var $site Information */
    /* @var $form yii\bootstrap5\ActiveForm */
    /* @var $isAlone bool */
    /* @var $parent Model|Razdel|Product */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#utils_gallery_create';
    
    $this->title                   = ($parent) ? 'Галерея для: ' . $parent->name : 'Создать галерею';
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
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
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_createHeader',
        [
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
?>
    <div class='card-body'>

        <div class='row'>

            <div class='col-xl-6'>
                <div class='card h-100'>
                    <div class='card-body'>
                        <?php
                            if ($parent) {
                                echo $form->field($model, 'site_id')
                                          ->hiddenInput(['value' => $parent->site_id])->label(false)
                                ;
                                
                                echo $form->field($model, 'textType')
                                          ->hiddenInput(['value' => $parent::TEXT_TYPE])->label(false)
                                ;
                                
                                echo $form->field($model, 'parentId')
                                          ->hiddenInput(['value' => $parent->id])->label(false)
                                ;
                            }
                            else {
                                try {
                                    echo $form
                                        ->field($model, 'site_id')
                                        ->dropDownList(
                                            InformationReader::getDropDownFilter(0),
                                            [
                                                'prompt'   => ' == ',
                                                'onchange' => 'updateTypeList(this.value);',
                                            ],
                                        )
                                        ->label('Выбрать сайт')
                                    ;
                                }
                                catch (Exception $e) {
                                    PrintHelper::exception(
                                        $actionId, 'SiteReader_getDropDownFilter ' . LAYOUT_ID, $e,
                                    );
                                }
                                echo $form
                                    ->field($model, 'textType')
                                    ->dropDownList(
                                        TypeHelper::getTypesMap(),
                                        [
                                            'prompt'   => ' == ',
                                            'onchange' => '
                                                    var siteId = $("#' . Html::getInputId($model, 'site_id') . '").val();
                                                    $.post("lists?siteId=" + siteId + "&textType=" + $(this).val(), function(data) {
                                                        $("select#selectedModel").html(data);
                                                    });
                                                ',
                                        ],
                                    )
                                    ->label('Выбрать модель для галереи')
                                ;
                                echo $form
                                    ->field($model, 'parentId')
                                    ->dropDownList(
                                        [],
                                        [
                                            'prompt' => ' -- ',
                                            'id'     => 'selectedModel',
                                        ],
                                    )
                                    ->label($label . '. Выбрать модель')
                                ;
                            }
                        ?>
                        
                        <?= $form->field($model, 'name')->textInput([
                            'maxlength' => true,
                            'required' => true,
                            'value'     => ($parent) ? 'Галерея ' . $parent->name : 'Галерея',
                        ]) ?>
                        
                        
                        <?= $form->field($model, 'title')
                                 ->textarea([
                                     'rows' => 4,
                                 ])
                        ?>

                    </div>
                    <div class="card-footer">
                        <?= ButtonHelper::submit()
                        ?>
                    </div>

                </div>
            </div>
            <div class="col-xl-6">
                <div class='card h-100'>
                    <div class='card-header bg-light'>
                        <strong>
                            Описание галереи
                        </strong>
                    </div>

                    <div class="card-body">
                        <?= $form->field(
                            $model, 'description',
                            [
                                'inputOptions' => [
                                    'id' => 'description-edit-area',
                                ],
                            ],
                        )
                                 ->textarea()
                                 ->label(false)
                        ?>
                    </div>
                    <div class='card-footer'>
                                <?= SelectHelper::status($form, $model) ?>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <div class="card-header bg-body-secondary">
        Текст галереи
    </div>
    <div class='card-body'>
        <?= $form->field(
            $model, 'text',
            [
                'inputOptions' => [
                    'id' => 'text-edit-area',
                ],
            ],
        )
                 ->textarea()
                 ->label(false)
        ?>
    </div>
<?php
    if ($isAlone && $model->tags):
        $siteId = $site->id ?? null;
        if ($siteId):
            ?>
            <div class='card-header bg-light'>
                <strong>Метки</strong>
            </div>
            <div class='card-body'>
                <?= $form->field($model->tags, 'textNew')
                         ->label('Добавить новые метки, через запятую:')
                ?>
                <hr>
                <?= $form->field($model->tags, 'existing')
                         ->inline()
                         ->checkboxList($model->tags::tagsList($siteId))
                         ->label('Отметить:')
                ?>
            </div>
        <?php
        endif;
    else:
        echo Html::activeHiddenInput(
            $model->tags, 'existing',
            ['value' => ''],
        );
    endif;
    
    echo '</div>';
    
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
