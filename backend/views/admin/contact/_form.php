<?php
    
    
    use core\edit\forms\Admin\ContactForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\web\JqueryAsset;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $model ContactForm */
    
    const ADMIN_CONTACT_FORM_LAYOUT = '#admin_contact_form';
    echo PrintHelper::layout(ADMIN_CONTACT_FORM_LAYOUT);
    
    $form = ActiveForm::begin(
        [
            'id'          => 'contact-form',
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

    <div class="card mb-2 ">
        <div class="card-header bg-body-secondary"><h4>Контактная информация</h4></div>
        <div class="card-body mb-2 p-2">
            <div class="row">
                <div class='col-lg-3 col-md-6'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>О компании</h5>
                        </div>
                        <div class='card-body'>
                            <?= $form->field($model, 'name')->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'placeholder' => 'Название компании',
                            ],
                            )->label(false)
                            ?>
                            <?= $form->field($model, 'website')->textInput([
                                'maxlength'   => true,
                                'placeholder' => 'Адрес вебсайта',
                            ],
                            )->label(false)
                            ?>
                            <?= $form->field($model, 'email')->textInput([
                                'maxlength'   => true,
                                'placeholder' => 'Email для контактов',
                            ],
                            )->label(false)
                            ?>
                            <?= $form->field($model, 'zakazMail')->textInput([
                                'maxlength'   => true,
                                'placeholder' => 'Email для заказов',
                            ],
                            )->label(false)
                            ?>
                            <?= $form->field($model, 'description')->textarea([
                                'rows'        => 6,
                                'placeholder' => 'О компании',
                            ],
                            )->label(false)
                            ?>
                        </div>
                    </div>
                </div>
                <div class='col-lg-3 col-md-6'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Адрес</h5>
                        </div>
                        <div class='card-body'>
                            <?= $form->field($model, 'address[streetAddress]')
                                     ->textInput(
                                         [
                                             'placeholder' => 'Улица, дом',
                                         ],
                                     )->label(false)
                            ?>
                            <?= $form->field($model, 'address[addressLocality]')
                                     ->textInput(
                                         [
                                             'placeholder' => 'Город',
                                         ],
                                     )->label(false)
                            ?>
                            <?= $form->field($model, 'address[postalCode]')
                                     ->textInput(
                                         [
                                             'placeholder' => 'Почтовый индекс',
                                         ],
                                     )->label(false)
                            ?>
                            <?= $form->field($model, 'address[addressCountry]')
                                     ->textInput(
                                         [
                                             'placeholder' => 'Страна',
                                         ],
                                     )->label(false)
                            ?>
                        </div>
                    </div>
                </div>
                <div class='col-lg-3 col-md-6'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Телефоны</h5>
                        </div>
                        <div class='card-body' id="phone-fields">
                            <!-- Начальное поле для телефона -->
                            <div class="phone-field">
                                <?= $form->field($model, 'phones[0][label]')
                                         ->textInput(['placeholder' => 'Название (например, Офис)'])
                                         ->label(false)
                                ?>
                                <?= $form->field($model, 'phones[0][number]')
                                         ->textInput(['placeholder' => 'Номер телефона (например, +7 (999) 888 7766)'])
                                         ->label(false)
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-success-subtle">
                            <!-- Кнопка добавления новых полей -->
                            <button type='button' id='add-phone-field' class='btn btn-success'>Добавить телефон</button>
                        </div>
                    </div>
                </div>
                <div class='col-lg-3 col-md-6'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Аналитика</h5>
                        </div>
                        <div class='card-body' id="analytic-fields">
                            <!-- Начальное поле для идентификатора -->
                            <div class="analytic-field">
                                <?= $form->field($model, 'analytics[0][label]')
                                         ->textInput(['placeholder' => 'Название (например, Yandex)'])
                                         ->label(false)
                                ?>
                                <?= $form->field($model, 'analytics[0][number]')
                                         ->textInput(['placeholder' => 'Идентификатор'])
                                         ->label(false)
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-success-subtle">
                            <!-- Кнопка добавления новых полей -->
                            <button type="button" id="add-analytic-field" class="btn btn-success">Добавить идентификатор
                            </button>
                        </div>
                    </div>
                </div>
                <div class='col-lg-3 col-md-6'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Социальные сети</h5>
                        </div>
                        <div class='card-body' id="socialNetwork-fields">
                            <!-- Начальное поле для социальной сети -->
                            <div class="socialNetwork-field">
                                <?= $form->field($model, 'socialNetworks[0][label]')
                                         ->textInput(['placeholder' => 'Название (например, Facebook)'])
                                         ->label(false)
                                ?>
                                <?= $form->field($model, 'socialNetworks[0][number]')
                                         ->textInput(['placeholder' => 'Ссылка на страницу'])
                                         ->label(false)
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-success-subtle">
                            <!-- Кнопка добавления новых полей -->
                            <button type="button" id="add-socialNetwork-field" class="btn btn-success">Добавить Ссылку
                            </button>
                        </div>
                    </div>
                </div>
                <div class='col-lg-3 col-md-6'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Мессенджеры</h5>
                        </div>
                        <div class='card-body' id="messenger-fields">
                            <!-- Начальное поле для социальной сети -->
                            <div class="messenger-field">
                                <?= $form->field($model, 'messengers[0][label]')
                                         ->textInput(['placeholder' => 'Название (например, Telegram)'])
                                         ->label(false)
                                ?>
                                <?= $form->field($model, 'messengers[0][number]')
                                         ->textInput(['placeholder' => 'Идентификатор мессенджера'])
                                         ->label(false)
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-success-subtle">
                            <!-- Кнопка добавления новых полей -->
                            <button type="button" id="add-messenger-field" class="btn btn-success">Добавить
                                Идентификатор
                            </button>
                        </div>
                    </div>
                </div>
                <div class='col-lg-3 col-md-6'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Платежные системы</h5>
                        </div>
                        <div class='card-body' id="money-fields">
                            <!-- Начальное поле для платежной системы -->
                            <div class="money-field">
                                <?= $form->field($model, 'money[0][label]')
                                         ->textInput(['placeholder' => 'Название (например, YouMoney)'])
                                         ->label(false)
                                ?>
                                <?= $form->field($model, 'money[0][number]')
                                         ->textInput(['placeholder' => 'Идентификатор платежной системы'])
                                         ->label(false)
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-success-subtle">
                            <!-- Кнопка добавления новых полей -->
                            <button type="button" id="add-money-field" class="btn btn-success">Добавить Идентификатор
                            </button>
                        </div>
                    </div>
                </div>
                <div class='col-lg-3 col-md-6'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Доступные языки</h5>
                        </div>
                        <div class='card-body' id="languages-fields">
                            <!-- Начальное поле для социальной сети -->
                            <div class="languages-field">
                                <?= $form->field($model, 'languages[0][label]')
                                         ->textInput(['placeholder' => 'Язык, англ.написание (например, Russian)'])
                                         ->label(false)
                                ?>
                                <?= $form->field($model, 'languages[0][number]')
                                         ->textInput(['placeholder' => 'Язык,оригинал (например, Русский)'])
                                         ->label(false)
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-success-subtle">
                            <!-- Кнопка добавления новых полей -->
                            <button type="button" id="add-languages-field" class="btn btn-success">Добавить
                                Идентификатор
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class='card-body mb-2 p-2'>
            <div class='row'>
                <div class='col-lg-6 col-md-12'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Время работы</h5>
                        </div>
                        <div class='card-body'>
                            <?php
                                $days = [
                                    'Monday'    => 'Понедельник',
                                    'Tuesday'   => 'Вторник',
                                    'Wednesday' => 'Среда',
                                    'Thursday'  => 'Четверг',
                                    'Friday'    => 'Пятница',
                                    'Saturday'  => 'Суббота',
                                    'Sunday'    => 'Воскресенье',
                                ];
                            ?>

                            <div class="work-days mb-3">
                                <label>Отметить рабочие дни:</label>
                                <?php
                                    foreach ($days as $dayKey => $dayLabel): ?>
                                        <div class="form-check form-check-inline">
                                            <?= Html::checkbox("ContactForm[work_hours][$dayKey][isOpen]", false, [
                                                'class' => 'form-check-input',
                                                'id'    => "work_day_$dayKey",
                                            ])
                                            ?>
                                            <label class="form-check-label"
                                                   for="work_day_<?= $dayKey ?>"><?= $dayLabel ?></label>
                                        </div>
                                    <?php
                                    endforeach; ?>
                            </div>
                            
                            <?php
                                foreach ($days as $dayKey => $dayLabel): ?>
                                    <div class="work-day-fields mb-3">
                                        <h6><?= $dayLabel ?></h6>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <?= Html::textInput("ContactForm[work_hours][$dayKey][opens]", null, [
                                                    'class'       => 'form-control',
                                                    'placeholder' => 'Время открытия (например, 09:00)',
                                                    'id'          => "work_hours_open_$dayKey",
                                                ])
                                                ?>
                                            </div>
                                            <div class="col-lg-6">
                                                <?= Html::textInput("ContactForm[work_hours][$dayKey][closes]", null, [
                                                    'class'       => 'form-control',
                                                    'placeholder' => 'Время закрытия (например, 18:00)',
                                                    'id'          => "work_hours_close_$dayKey",
                                                ])
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class='col-lg-6 col-md-12'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Выбрать точку на карте</h5>
                        </div>
                        <div class='card-body'>
                            <div id='map' style='height: 300px;'></div>
                            
                            <?= Html::activeHiddenInput($model, 'latitude', ['id' => 'latitude'])
                            ?>
                            <?= Html::activeHiddenInput($model, 'longitude', ['id' => 'longitude'])
                            ?>

                            <!-- Поле для масштаба карты -->
                            <?= Html::activeHiddenInput($model, 'scale', ['id' => 'contactform-scale'])
                            ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class='form-group'>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary'])
            ?>
        </div>
    </div>


<?php
    ActiveForm::end();
    
    
    $js                             = <<<JS
let phoneIndex = 1;
let analyticIndex = 1;
let socialNetworkIndex = 1;
let messengerIndex = 1;
let moneyIndex = 1;
let languagesIndex = 1;

// Функция добавления нового набора полей для телефона
$('#add-phone-field').on('click', function() {
    const fieldHtml = `
        <div class="phone-field">
            <input type="text" name="ContactForm[phones][\${phoneIndex}][label]" placeholder="Название (например, Офис)" class="form-control" />
            <input type="text" name="ContactForm[phones][\${phoneIndex}][number]" placeholder="Номер телефона (например, +7 (977) 136 0225)" class="form-control" />
            <button type="button" class="remove-phone-field btn btn-danger">Удалить</button>
        </div>
    `;
    $('#phone-fields').append(fieldHtml);
    phoneIndex++;
});

// Удаление набора полей для телефона
$(document).on('click', '.remove-phone-field', function() {
    $(this).closest('.phone-field').remove();
});

// Функция добавления нового набора полей для аналитики
$('#add-analytic-field').on('click', function() {
    const analyticHtml = `
        <div class="analytic-field">
            <input type="text" name="ContactForm[analytics][\${analyticIndex}][label]" placeholder="Название (например, Яндекс)" class="form-control" />
            <input type="text" name="ContactForm[analytics][\${analyticIndex}][number]" placeholder="Идентификатор" class="form-control" />
            <button type="button" class="remove-analytic-field btn btn-danger">Удалить</button>
        </div>
    `;
    $('#analytic-fields').append(analyticHtml);
    analyticIndex++;
});

// Удаление набора полей для аналитики
$(document).on('click', '.remove-analytic-field', function() {
    $(this).closest('.analytic-field').remove();
});

// Функция добавления нового набора полей для социальных сетей
$('#add-socialNetwork-field').on('click', function() {
    const socialNetworkHtml = `
        <div class="socialNetwork-field">
            <input type="text" name="ContactForm[socialNetworks][\${socialNetworkIndex}][label]" placeholder="Название (например, Facebook)" class="form-control" />
            <input type="text" name="ContactForm[socialNetworks][\${socialNetworkIndex}][number]" placeholder="Ссылка на страницу" class="form-control" />
            <button type="button" class="remove-socialNetwork-field btn btn-danger">Удалить</button>
        </div>
    `;
    $('#socialNetwork-fields').append(socialNetworkHtml);
    socialNetworkIndex++;
});

// Удаление набора полей для социальных сетей
$(document).on('click', '.remove-socialNetwork-field', function() {
    $(this).closest('.socialNetwork-field').remove();
});

// Функция добавления нового набора полей для мессенджеров
$('#add-messenger-field').on('click', function() {
    const messengerHtml = `
        <div class="messenger-field">
            <input type="text" name="ContactForm[messengers][\${messengerIndex}][label]" placeholder="Название (например, Telegram)" class="form-control" />
            <input type="text" name="ContactForm[messengers][\${messengerIndex}][number]" placeholder="Идентификатор мессенджера" class="form-control" />
            <button type="button" class="remove-messenger-field btn btn-danger">Удалить</button>
        </div>
    `;
    $('#messenger-fields').append(messengerHtml);
    messengerIndex++;
});

// Удаление набора полей для мессенджеров
$(document).on('click', '.remove-messenger-field', function() {
    $(this).closest('.messenger-field').remove();
});

// Функция добавления нового набора полей для платежных систем
$('#add-money-field').on('click', function() {
    const moneyHtml = `
        <div class="money-field">
            <input type="text" name="ContactForm[money][\${moneyIndex}][label]" placeholder="Название (например, YouMoney)" class="form-control" />
            <input type="text" name="ContactForm[money][\${moneyIndex}][number]" placeholder="Идентификатор платежной системы" class="form-control" />
            <button type="button" class="remove-money-field btn btn-danger">Удалить</button>
        </div>
    `;
    $('#money-fields').append(moneyHtml);
    moneyIndex++;
});

// Удаление набора полей для платежных систем
$(document).on('click', '.remove-money-field', function() {
    $(this).closest('.money-field').remove();
});
// Функция добавления нового набора полей для платежных систем
$('#add-languages-field').on('click', function() {
    const languagesHtml = `
        <div class="languages-field">
            <input type="text" name="ContactForm[languages][\${languagesIndex}][label]" placeholder="Язык, англ.написание (например, Russian)" class="form-control" />
            <input type="text" name="ContactForm[languages][\${languagesIndex}][number]" placeholder="Язык,оригинал (например, Русский)" class="form-control" />
            <button type="button" class="remove-languages-field btn btn-danger">Удалить</button>
        </div>
    `;
    $('#languages-fields').append(languagesHtml);
    languagesIndex++;
});

// Удаление набора полей для платежных систем
$(document).on('click', '.remove-languages-field', function() {
    $(this).closest('.languages-field').remove();
});
const latitude =  $model?->latitude ?? 55.764173;
const longitude = $model?->longitude ?? 37.636773;
const scale = $model?->scale ?? 10;
// Инициализация карты
    var map = L.map('map').setView([latitude, longitude], scale);

    // Добавление слоя карты (например, OSM)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([latitude, longitude], {draggable: true}).addTo(map);

    // Функция обновления координат при перемещении маркера
    function updateCoordinates(lat, lng) {
        $('#latitude').val(lat);
        $('#longitude').val(lng);
    }

    // Функция обновления масштаба
    function updateScale() {
        $('#contactform-scale').val(map.getZoom());
    }

    // Событие для изменения позиции маркера и записи координат
    marker.on('dragend', function(e) {
        var position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);
    });

    // Событие для клика на карту
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
    });

    // Событие для изменения масштаба карты
    map.on('zoomend', function(e) {
        updateScale();
    });

    // Устанавливаем начальный масштаб
    updateScale();
    
JS;
    $this->registerJs($js);
    
    $this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', ['depends' => [JqueryAsset::class]]);
    $this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
