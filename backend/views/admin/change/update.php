<?php
    
    use backend\helpers\BreadCrumbHelper;
    use core\edit\forms\Admin\ChangeSiteForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $parent core\edit\entities\Blog\Category */
    /* @var $model ChangeSiteForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_change_update';
    
    $this->title = $parent->name . '. Смена сайта';
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeView($textType, $parent->id, $parent->name);
    $this->params['breadcrumbs'][] = 'Перемещение';
    
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

?>
<div class="card">
    <div class='card-body'>
        <div class='row'>
            <div class='col-lg-6'>
                <div class='card'>
                    <div class='card-body'>

                        <div class='table-responsive'>
                            <table class='table table-sm table-striped table-bordered'>
                                <tbody>
                                <tr>
                                    <th scope='row'>id</th>
                                    <td><?= $parent->id ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Сайт</th>
                                    <td><?= ($parent->site_id) . '. ' . ParametrHelper::getSiteName($parent->site_id)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Тип текста</th>
                                    <td><?= TypeHelper::getName($textType, null, false, true);
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Название перемещаемого текста</th>
                                    <td><?= Html::encode($parent->name)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Полное название</th>
                                    <td><?= Html::encode($parent->title)
                                        ?></td>
                                </tr>
                                <tr>
                                    <th scope='row'>Описание</th>
                                    <td><?= FormatHelper::asHtml($parent->description)
                                        ?></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class='col-lg-6'>
                <div class='card'>
                    <div class='card-header'>
                        Примечание: при переносе модели без наследников следует выбрать родительскую модель. При
                        переносе модели с наследниками, она окажется в структуре выбранного сайта последней (вместе с
                        наследниками и с сохранением иерархии).
                    </div>
                    <div class='card-body'>
                        <?php
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
                            );
                            echo $this->render(
                                '../../seo/type/_selectRazdel',
                                [
                                    'model'    => $model,
                                    'parent'   => $parent,
                                    'textType' => $textType,
                                    'form'     => $form,
                                    'label'    => $label,
                                ],
                            );
                            
                            
                            echo ButtonHelper::submit('Переместить');
                            
                            
                            ActiveForm::end();
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
