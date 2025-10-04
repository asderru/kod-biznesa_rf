<?php
    
    use backend\helpers\BreadCrumbHelper;
    use backend\tools\TinyHelper;
    use backend\widgets\PagerWidget;
    use core\edit\entities\Library\Chapter;
    use core\edit\forms\Library\ChapterForm;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model ChapterForm */
    /* @var $chapter Chapter */
    /* @var $lexemes core\edit\entities\Tools\Lexeme[] */
    /* @var $lexemesBook core\edit\entities\Tools\Lexeme[] */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_chapter_update';
    
    $this->title = $chapter->name . '. Правка';
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::BOOK_TYPE);
    $this->params['breadcrumbs'][] = BreadCrumbHelper::typeIndex(Constant::CHAPTER_TYPE);
    $this->params['breadcrumbs'][] = ['label' => $chapter->name, 'url' => ['view', 'id' => $chapter->id]];
    $this->params['breadcrumbs'][] = 'Правка';
    
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
                'model'  => $chapter,
                'folder' => true,
            ],
        );
    }
    catch (Throwable $e) {
        PrintHelper::exception(
            $actionId, LAYOUT_ID, $e,
        );
    }
    
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
?>

<div class="card">
    
    <?= $this->render(
        '/layouts/tops/_updateHeader',
        [
            'model'    => $chapter,
            'title'    => $this->title,
            'textType' => $textType,
        ],
    )
    ?>

    <div class='card-body'>
        <div class="row mb-3">

            <div class="col-xl-6">
                <div class="card mb-2">
                    <div class="card-body">
                        <?php
                            echo $form->field($model, 'name')
                                      ->textInput(['maxlength' => true],
                                      )
                            ;
                            echo $form->field($model, 'title')->textarea(
                                [
                                    'rows' => 2,
                                ],
                            );
                        
                        ?>
                        
                        <?= $form->field($model, 'video')->textInput(['maxlength' => true])
                        ?>

                    </div>
                </div>
            </div>
            <div class='col-xl-6'>
                <div class='card'>
                    <div class="card-header bg-body-secondary">
                        <strong>Краткое описание текста</strong>
                    </div>
                    <div class='card-body'>
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
    <div class="card-header bg-body-secondary">
        <strong>Полный текст</strong>
    </div>
    <div class="card-body">
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
    <div class='card-footer'>
        <?= ButtonHelper::submit()
        ?>

        <!-- Button trigger modal -->
        <button type='button' class='btn btn-sm btn-outline-primary' data-bs-toggle='modal'
                data-bs-target='#lexemeModal2'>
            Проверить лексему по книге
        </button>

        <!-- Button trigger modal -->
        <button type='button' class='btn btn-sm btn-primary' data-bs-toggle='modal'
                data-bs-target='#lexemeModal'>
            Проверить лексему по всей базе
        </button>

    </div>

    <div class='card-header bg-light'>
        <strong>
            Метки
        </strong>
    </div>

    <div class='card-body'>
        
        <?= $form->field($model->tags, 'textNew')
                 ->label('Добавить новые метки, через запятую:')
        ?>
        <hr>
        
        <?= $form->field($model->tags, 'existing')
                 ->inline()
                 ->checkboxList($model->tags::tagsList($chapter->site_id))
                 ->label('Отметить:') ?>
    </div>
</div>
<?php
    ActiveForm::end();
    TinyHelper::getText();
    TinyHelper::getDescription();
?>

<!-- Modal -->
<div class='modal fade' id='lexemeModal2' tabindex='-1' aria-labelledby='lexemeModal2Label'
     aria-hidden='true'>
    <div class='modal-dialog modal-dialog-scrollable'>
        <div class='modal-content'>
            <div class='modal-header'>
                <div class='input-group mb-3'>
                    <input type='text' class='form-control' placeholder="Введите слово"
                           aria-label="lexeme" aria-describedby='button-lexeme'>
                    <button class='btn btn-outline-secondary' type='button' id='button-lexeme'>искать</button>
                </div>

            </div>
            <div class='modal-body'>
                <?php
                    foreach ($lexemesBook as $n => $row) {
                        echo '<div class="text-start">' . ($n + 1) . '. ' . $row . '</div>';
                    }
                ?>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>закрыть</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class='modal fade' id='lexemeModal' tabindex='-1' aria-labelledby='lexemeModalLabel'
     aria-hidden='true'>
    <div class='modal-dialog modal-dialog-scrollable'>
        <div class='modal-content'>
            <div class='modal-header'>
                <div class='input-group mb-3'>
                    <input type='text' class='form-control' placeholder="Введите слово"
                           aria-label="lexeme" aria-describedby='button-lexeme'>
                    <button class='btn btn-outline-secondary' type='button' id='button-lexeme'>искать</button>
                </div>

            </div>
            <div class='modal-body'>
                <?php
                    foreach ($lexemes as $n => $row) {
                        echo '<div class="text-start">' . ($n + 1) . '. ' . $row . '</div>';
                    }
                ?>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>закрыть</button>
            </div>
        </div>
    </div>
</div>


<script>
    // Ждем, пока загрузится весь документ
    document.addEventListener('DOMContentLoaded', function () {
        // Находим кнопку по идентификатору
        var button = document.getElementById('button-lexeme');

        // Назначаем обработчик события "клик" на кнопку
        button.addEventListener('click', function () {
            // Находим инпут по атрибуту aria-label
            var input = document.querySelector("input[aria-label='lexeme']");

            // Получаем значение из инпута
            var searchWord = input.value.trim().toLowerCase();

            // Находим все элементы с текстом в modal-body
            var lexemeElements = document.querySelectorAll('.modal-body > .text-start');

            // Проходим по каждому элементу и проверяем, содержит ли он искомое слово
            lexemeElements.forEach(function (element) {
                // Получаем текстовое содержимое элемента
                var text = element.textContent.trim().toLowerCase();

                // Проверяем, содержит ли текстовое содержимое искомое слово
                if (text.includes(searchWord)) {
                    // Если содержит, то делаем элемент видимым
                    element.style.display = 'block'; // или какой-то другой стиль для вашего случая
                } else {
                    // Если не содержит, то скрываем элемент
                    element.style.display = 'none';
                }
            });
        });
    });
</script>
