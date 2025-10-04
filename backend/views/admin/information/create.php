<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\tools\TinyHelper;
    use core\edit\forms\Admin\InformationForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\SiteModeReader;
    use core\read\readers\Admin\InformationReader;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model InformationForm */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_information_create';
    
    $this->title = 'Добавить сайт';
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::index($label);
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

        <div class='row  mb-3'>

            <div class='col-xl-6'>

                <div class='card h-100'>
                    <div class='card-header bg-light'>
                        <strong>
                            Общая информация
                        </strong>

                    </div>
                    <div class='card-body pb-0'>
                        <?php
                            try {
                                echo
                                $form
                                    ->field($model, 'templateId')
                                    ->dropDownList(
                                        SiteModeReader::getTemplatesMap(),
                                    )
                                    ->label(' Выбрать шаблон ')
                                ;
                            }
                            catch (Exception $e) {
                                PrintHelper::exception(
                                    $actionId, 'SiteModeReader_getTemplatesMap ' . LAYOUT_ID, $e,
                                );
                            }
                            
                            try {
                                echo
                                $form
                                    ->field($model, 'parentId')
                                    ->dropDownList(
                                        InformationReader::getDropDownFilter(0),
                                    )
                                    ->label(' Указать родительский сайт ')
                                ;
                            }
                            catch (Exception $e) {
                                PrintHelper::exception(
                                    $actionId, 'SiteReader_getDropDownFilter ' . LAYOUT_ID, $e,
                                );
                            }
                            
                            try {
                                echo
                                $form
                                    ->field($model, 'siteMode')
                                    ->dropDownList(
                                        SiteModeReader::getSiteModesMap(),
                                        [
                                            'prompt' => ' -- ',
                                            'id'     => 'site',
                                        ],
                                    )
                                    ->label('Указать тип сайта')
                                ;
                            }
                            catch (Exception $e) {
                                PrintHelper::exception(
                                    'SiteModeReader_getSiteModesMap ', LAYOUT_ID, $e,
                                );
                            } ?>


                        <div class='row'>

                            <div class='col-md-6'>
                                <?= $form->field($model, 'name')->textInput([
                                    'maxlength' => true,
                                ])
                                         ->label('Домен (доменное имя сайта)')
                                ?>
                            </div>

                            <div class="col-md-6">
                                
                                <?= $form->field($model, 'id')->textInput(['maxlength' => true])
                                         ->label('Уникальный ID сайта')
                                ?>

                            </div>

                        </div>
                        
                        <?= $form->field($model, 'admin')->textInput(
                            [
                                'maxlength' => true,
                                'required'  => true,
                            ],
                        )
                        ?>
                        
                        
                        <?= $form->field($model, 'title')
                                 ->textarea(
                                     [
                                         'rows' => 3,
                                     ],
                                 )
                        ?>


                    </div>


                </div>

            </div>

            <div class="col-xl-6">

                <div class='card h-100'>

                    <div class='card-header bg-light'>
                        <strong>
                            Описание сайта (мета-тег desccription для главной страницы сайта)
                        </strong>
                    </div>
                    <div class='card-body pb-0'>
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
                </div>
            </div>
        </div>
    </div>
    <div class='card-header bg-light'>
        <strong>
            Текст
        </strong>
    </div>
    <div class="card-body">
        <?= $form->field(
            $model, 'text',
            [
                'inputOptions' =>
                    ['id' => 'text-edit-area',],
            ],
        )->textarea()
        ?>
    </div>
    <div class='card-footer'>
        <?= ButtonHelper::submit()
        ?>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
