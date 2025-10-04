<?php

    use core\edit\entities\Content\ContentCard;
    use core\helpers\ButtonHelper;
    use core\helpers\types\TypeUrlHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;

    /**
     * @var  $this        yii\web\View
     * @var  $parent      Model
     * @var  $model       ContentCard
     * @var  $cardFields  array
     * @var  $actionId    string
     * @var  $label       string
     * @var  $prefix      string
     * @var  $textType    int
     */
    
    // Константы и основные переменные
    const LAYOUT_ID = '#content_card_view';
    // Формирование заголовка
    $this->title = 'Карточка контента для ' . $parent->name;
    
    $buttons = [
        ButtonHelper::import($textType),
        ButtonHelper::export($textType),
    ];
    // Формирование массива кнопок
    $buttons = [
        ButtonHelper::update($model->id),
        ButtonHelper::expressType($textType, $model->id),
    ];
    
    // Хлебные крошки
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Все карточки',
        'url'   => ['content/card/index'],
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $this->title,
    ];
    
    // Рендеринг компонентов
    echo $this->render(
        '/layouts/tops/_infoHeader', [
        'label'    => $label,
        'textType' => $textType,
        'prefix'   => $prefix,
        'actionId' => $actionId,
        'layoutId' => LAYOUT_ID,
    ],
    );
?>

<div class="card">
    <div class='card-header bg-light d-flex justify-content-between p-2'>
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>

        <div class='btn-group-sm d-grid gap-2 d-sm-block'>
            <button type='button' class='btn btn-sm btn-outline-dark' data-bs-toggle='modal'
                    data-bs-target='#contentModal'>
                Смотреть контент
            </button>
            <?= Html::a(
                    'Править контент', TypeUrlHelper::getUpdate($model->text_type, $model->parent_id),
                [
                    'class' => 'btn btn-sm btn-outline-success',
                ],
            ) ?>
            <?= ButtonHelper::update($model->id, 'Редактировать карточку') ?>
            <?= ButtonHelper::collapse() ?>
        </div>
    </div>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?php
            foreach ($buttons as $button) {
                echo $button;
            }
        ?>
    </div>
    <div class='card-body'>
        <div class='row'>
            <div class='col-md-6'>
                <div class='mb-3'>
                    <strong>Название:</strong> <?= $model->name ?>
                </div>
                <div class="mb-3">
                    <strong>Тема:</strong> <?= $model->description ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <strong>Дата
                        обновления:</strong> <?= Yii::$app->formatter->asDatetime($model->updated_at) ?>
                </div>
            </div>
        </div>
        <div class='row mb-3'>

            <div class='col-xl-6'>
                <div class='card'>
                    <div class='card-header bg-light'>
                        <h5 class='card-title mb-0'>Карточка контента</h5>
                    </div>
                    <div class='card-body'>
                        <!-- Основные поля -->
                        <div class='mb-3'>
                            <strong>Название:</strong>
                            <p><?= Html::encode($model->name) ?></p>
                        </div>
                        <div class="mb-3">
                            <strong>Описание:</strong>
                            <p><?= Html::encode($model->description) ?></p>
                        </div>

                        <!-- Основные текстовые поля -->
                        <?php
                            $fieldNames = ['first_field', 'second_field', 'third_field', 'fourth_field'];
                            foreach ($fieldNames as $index => $fieldName):
                                if (isset($cardFields[$index])): ?>
                                    <div class="mb-3">
                                        <strong><?= Html::encode($cardFields[$index]['name']) ?>:</strong>
                                        <p><?= Html::encode($model->{$fieldName}) ?></p>
                                    </div>
                                <?php
                                endif;
                            endforeach;
                        ?>
                    </div>
                </div>
            </div>

            <div class='col-xl-6'>
                <div class='card'>
                    <div class='card-header bg-light'>
                        <strong>
                            Дополнительные поля
                        </strong>
                    </div>

                    <div class='card-body'>


                        <!-- Дополнительные поля (added_field) -->
                        <?php
                            if (!empty($model->added_field) && is_array($model->added_field)):
                                foreach ($model->added_field as $key => $addedField):
                                    if (isset($cardFields[$key])): ?>
                                        <div class="mb-3">
                                            <strong><?= Html::encode($addedField['label']) ?>:</strong>
                                            <p><?= Html::encode($addedField['subject']) ?></p>
                                        </div>
                                    <?php
                                    endif;
                                endforeach;
                            endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card-footer bg-body-tertiary text-center p-4">
        <?= ButtonHelper::update($model->id, 'редактировать') ?>
    </div>
</div>


<div class='modal' id='contentModal' tabindex='-1' aria-labelledby='contentModal' aria-hidden='true'>
    <div class='modal-dialog modal-xl modal-dialog-centered'>
        <div class='modal-content'>
            <?= $this->render(
                '/layouts/modals/_modalContent',
                [
                    'model' => $parent,
                ],
            )
            ?>
        </div>
    </div>
</div>
