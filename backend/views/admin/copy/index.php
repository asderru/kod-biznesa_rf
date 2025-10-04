<?php
    
    use backend\helpers\StatusHelper;
    use core\read\readers\Admin\InformationReader;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\forms\ModelCopyForm;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\db\ActiveRecord;
    
    /* @var $this yii\web\View */
    /* @var $source Product */
    /* @var $model ModelCopyForm */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $sites array|Information|Information[]|ActiveRecord[] */
    
    const LAYOUT_ID = '#admin_copy_index';
    
    $buttons        = [];
    $sourceTextType = ParentHelper::getParentType($source->textType);
    
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
?>

<div class='card'>

    <div class='card-header bg-light d-flex justify-content-between'>
        <div>
            <h4>
                <?= FaviconHelper::getTypeFavSized($textType, 2) . ' Копирование модели'
                ?>
            </h4>
        </div>
    </div>

    <div class='card-body'>
        <div class="row">
            <div class="col-lg-6">
                <div class='card'>
                    <div class="card-header">
                        <h5 class='card-title'>Информация о модели</h5>
                    </div>
                    <div class='card-body table-responsive'>
                        <table class='table table-striped table-sm'>
                            <tbody>
                            <tr>
                                <td><strong>Название:</strong></td>
                                <td><?= htmlspecialchars($source->name) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Название:</strong></td>
                                <td><?= htmlspecialchars($source->name) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Название:</strong></td>
                                <td><?= htmlspecialchars($source->name) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Заголовок:</strong></td>
                                <td><?= htmlspecialchars($source->title) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Идентификатор:</strong></td>
                                <td><?= htmlspecialchars($source->slug) ?></td>
                            </tr>
                            
                            <?php
                                if (isset($source->code) && $source->code !== null): ?>
                                    <tr>
                                        <td><strong>Внутренний код:</strong></td>
                                        <td><?= htmlspecialchars($source->code) ?></td>
                                    </tr>
                                <?php
                                endif; ?>
                            
                            <?php
                                if (isset($source->brandId) && $source->brandId !== null): ?>
                                    <tr>
                                        <td><strong>Brand ID:</strong></td>
                                        <td><?= htmlspecialchars($source->brandId) ?></td>
                                    </tr>
                                <?php
                                endif; ?>
                            
                            
                            <?php
                                if (isset($source->price) && $source->price !== null): ?>
                                    <tr>
                                        <td><strong>Цена:</strong></td>
                                        <td><?= number_format($source->price, 2) ?></td>
                                    </tr>
                                <?php
                                endif; ?>

                            <tr>
                                <td><strong>Статус:</strong></td>
                                <td><?= StatusHelper::getStatusName($source->status) ?></td>
                            </tr>
                            
                            
                            <?php
                                if (isset($source->rating) && $source->rating !== null): ?>
                                    <tr>
                                        <td><strong>Рейтинг:</strong></td>
                                        <td><?= number_format($source->rating) ?></td>
                                    </tr>
                                <?php
                                endif; ?>


                            <tr>
                                <td><strong>ID:</strong> <?= number_format($source->id) ?></td>
                                <td><strong>Сайт ID:</strong> <?= number_format($source->site_id) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class='card-header'>
                        <h5 class='card-title'>Описание о модели</h5>
                    </div>
                    <div class='card-body'>
                        <?= nl2br(htmlspecialchars($source->description)) ?>
                    </div>
                    <div class="card-footer">
                        <?= ButtonHelper::updateType($source->textType, $source->id, 'Редактировать модель', '_blank'); ?>
                    </div>

                </div>
            </div>
            <div class="col-lg-6">
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
                            ->label('Выбрать сайт для копирования')
                        ;
                    }
                    catch (Exception $e) {
                        PrintHelper::exception(
                            'SiteReader_getDropDownFilter ', LAYOUT_ID, $e,
                        );
                    }
                ?>
                
                <?php
                    echo $form
                        ->field($model, 'textType')
                        ->dropDownList(
                            TypeHelper::getTypesMap(),
                            [
                                'prompt'   => ' == ',
                                'onchange' => '
                    var siteId = $("#' . Html::getInputId($model, 'site_id') . '").val();
                    $.post("/seo/type/lists?siteId=" + siteId + "&textType=" + $(this).val(), function(data) {
                        $("select#selectedModel").html(data);
                    });
                ',
                            ],
                        )
                        ->label('Выбрать тему  для копирования')
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
                        ->label($label . '. Выбрать родительскую модель')
                    ;
                    
                    echo $form->field($model, 'limit')->textInput(
                        [
                            'type' => 'number',
                            'min'  => 0,
                        ],
                    )
                              ->label('Указать количество копий')
                    ;
                    
                    
                    ActiveForm::end();
                ?>
            </div>
        </div>
    </div>
    
    <?=
        $this->render(
            '/layouts/templates/_textWidget',
            [
                'model' => $source,
            ],
        )
    ?>


</div>
