<?php
    
    use backend\tools\TinyHelper;
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Admin\Period */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var int $siteId */
    /* @var int $textType */
    /* @var int $parentId */
    /* @var int $contentId */
    /* @var string $name */
    /* @var string $url */
    
    const LAYOUT_ID = '#tools_seolink_create';
    
    $this->title                   = 'Создание СЕО-ссылки';
    $this->params['breadcrumbs'][] = ['label' => 'СЕО-ссылки', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $seoUrl = $url . DIRECTORY_SEPARATOR . $name . '/';
    
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
    <div class="col-lg-6">
        <div class="card">

            <div class='card-header bg-light'>
                <h4><?= $this->title ?>
                </h4>
            </div>

            <div class='card-body'>
                <h3>
                    http://<?= $url ?>/<?= $name ?>/
                </h3>
                <?= $form->field($model, 'site_id')
                         ->hiddenInput(['value' => $siteId])->label(false)
                ?>
                <?= $form->field($model, 'text_type')
                         ->hiddenInput(['value' => $textType])->label(false)
                ?>
                <?= $form->field($model, 'parent_id')
                         ->hiddenInput(['value' => $parentId])->label(false)
                ?>
                <?= $form->field($model, 'content_id')
                         ->hiddenInput(['value' => $contentId])->label(false)
                ?>
                <?= $form->field($model, 'name')
                         ->hiddenInput(['value' => $name])->label(false)
                ?>
                <?= $form->field($model, 'url')
                         ->hiddenInput(['value' => $seoUrl])->label(false)
                ?>
            </div>

            <div class='card-footer'>
                <?= ButtonHelper::submit('Подтвердить создание ссылки', 'btn btn-lg btn-danger')
                ?>
                <p class="p-2">
                    <strong>Только для опытных пользователей!</strong>
                </p>
            </div>

        </div>
    </div>

<?php
    echo '</div>';
    
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
