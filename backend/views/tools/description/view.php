<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use backend\helpers\StatusHelper;
    use backend\tools\TinyHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ModelHelper;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $content core\edit\entities\Content\Content */
    /* @var $parent core\edit\entities\Content\Content */
    /* @var $model core\edit\forms\Content\DescriptionEditForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#content_description_view';
    
    $label = 'Мета-описание для «' . $parent->name . '»‎. Сайт ' . $parent->site->name;
    
    $this->title                   = $content->name . '. Мета-описание';
    $this->params['breadcrumbs'][] = ['label' => 'Мета-описания', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $parent->name;
    
    $count = strlen($content->description);
    
    $class = 'text-secondary p-2';
    // PrintHelper::print($content->id);
    $prev = $content->getPrevModel();
    
    $next = $content->getNextModel();
    
    if ($prev) {
        $prevUrl
            = Html::a(
            Html::encode($prev->name),
            [
                'view',
                'id' => $prev->id,
            ],
        );
    }
    else {
        $prevUrl = Html::a(
            'На главную', 'index',
            [
                'class' => $class,
            ],
        );
    }
    
    if ($next) {
        $nextUrl
            = Html::a(
            Html::encode($next->name),
            [
                'view',
                'id' => $next->id,
            ],
        );
    }
    else {
        $nextUrl = Html::a(
            'На главную', 'index',
            [
                'class' => $class,
            ],
        );
    }
    
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
            'PagerWidget ', LAYOUT_ID, $e,
        );
    }

    try {
        echo
        $this->render(
            '/layouts/top/_header',
            [
                'buttons' => [
                    StatusHelper::statusLabel($content->status),
                    ButtonHelper::viewType($content->text_type),
                ],
            ],
        );
    }
    catch (Exception $e) {
    }
?>

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
    )
?>

<div class='row'>

    <div class="col-xl-6 ">

        <!--##### Content ###############################################-->
        <div class='card round-2 mb-2'>

            <div class="card-header d-flex justify-content-between">
                Мета-описание для <strong>
                    <?= $content->name ?>
                </strong>
                <span>
                    <?= ButtonHelper::submit()
                    ?>
                    </span>
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

            <div class='card-body'>
                <strong>HTML-разметка:</strong><br>
                <?= htmlspecialchars($parent->description)
                ?>
            </div>


            <div class='card-footer bg-light small'>
                Первоначально знаков:
                <strong>
                    <?= $count ?>
                </strong>. При нажатии на "<strong>Слов:</strong>" (выше и справа), появится актуальное количество
                знаков. Оптимальное для метаописания количество знаков - <strong>160</strong>.
                <hr>
            </div>
        </div>
    </div>

    <div class="col-xl-6" style='height: 100vh; overflow-y: auto;'>
        <!--###### Top Card #################################################-->


        <div class='card round-2 mb-2'>

            <div class='card-header bg-light'>
                Полное название
            </div>
            <div class='card-body'>
                
                <?= $form->field($model, 'title')->textarea(
                    [
                        'rows' => 3,
                    ],
                )
                    ->label(false)
                ?>


            </div>
            
            <?php
                ActiveForm::end(); ?>

            <div class='card-header bg-light d-flex justify-content-between'>
                    <span>
                        Текст
                    </span>
                <div class='btn-area'>
                    <strong>
                        Копировать:
                    </strong>
                    <span id='btn-copy-1000' class='btn btn-sm btn-secondary'>1000 знаков
                    </span>
                    <span id='btn-copy-3000' class='btn btn-sm btn-dark'>3000 знаков
                    </span>
                    <span id='btn-copy' class='btn btn-sm btn-primary'>Весь текст
                    </span>
                </div>
            </div>
            <div id="copy-text" class='card-body'>
                <?= FormatHelper::asHtml($parent->text)
                ?>
            </div>
        </div>

    </div>
</div>
<?= TinyHelper::getDescription()
?>

<script>
    document.getElementById('btn-copy').addEventListener('click', function () {
        var copyText = document.getElementById('copy-text');
        var btnCopy = document.getElementById('btn-copy');

        // Создаем временный элемент textarea для копирования текста
        var textarea = document.createElement('textarea');
        textarea.value = copyText.innerText;
        document.body.appendChild(textarea);

        // Выделяем весь текст в textarea
        textarea.select();

        // Копируем текст в клипборд
        document.execCommand('copy');

        // Удаляем временный элемент
        document.body.removeChild(textarea);

        // Меняем класс кнопки после копирования
        btnCopy.classList.remove('btn-primary');
        btnCopy.classList.add('btn-outline-danger');

        // Меняем надпись кнопки после копирования
        btnCopy.innerText = 'Скопировано!';
    });
</script>
<script>
    document.getElementById('btn-copy-1000').addEventListener('click', function () {
        var copyText = document.getElementById('copy-text');
        var btnCopy = document.getElementById('btn-copy-1000');

        // Создаем временный элемент textarea для копирования текста
        var textarea = document.createElement('textarea');
        textarea.value = copyText.innerText.substring(0, 1000); // Копируем только первые 1000 символов
        document.body.appendChild(textarea);

        // Выделяем весь текст в textarea
        textarea.select();

        // Копируем текст в клипборд
        document.execCommand('copy');

        // Удаляем временный элемент
        document.body.removeChild(textarea);

        // Меняем класс кнопки после копирования
        btnCopy.classList.remove('btn-secondary');
        btnCopy.classList.add('btn-outline-danger');

        // Меняем надпись кнопки после копирования
        btnCopy.innerText = 'Скопировано!';
    });
</script>

<script>
    document.getElementById('btn-copy-3000').addEventListener('click', function () {
        var copyText = document.getElementById('copy-text');
        var btnCopy = document.getElementById('btn-copy-3000');

        // Создаем временный элемент textarea для копирования текста
        var textarea = document.createElement('textarea');
        textarea.value = copyText.innerText.substring(0, 3000); // Копируем только первые 1000 символов
        document.body.appendChild(textarea);

        // Выделяем весь текст в textarea
        textarea.select();

        // Копируем текст в клипборд
        document.execCommand('copy');

        // Удаляем временный элемент
        document.body.removeChild(textarea);

        // Меняем класс кнопки после копирования
        btnCopy.classList.remove('btn-dark');
        btnCopy.classList.add('btn-outline-danger');

        // Меняем надпись кнопки после копирования
        btnCopy.innerText = 'Скопировано!';
    });
</script>
