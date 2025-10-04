<?php
    
    use core\edit\forms\Admin\ChangeSiteForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FormatHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $parent core\edit\entities\Blog\Category */
    /* @var $model ChangeSiteForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_structure_update';
    
    $this->title = $parent->name . '. Правка';
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = [
        'label' => $parent->name,
        'url'   => ['view', 'id' => $parent->id],
    ];
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
                        Выбрать сайт и родительскую модель
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
