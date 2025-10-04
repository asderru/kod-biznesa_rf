<?php
    
    use backend\widgets\PagerWidget;
    use core\helpers\ButtonHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    use yii\web\JqueryAsset;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Admin\Contact */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_contactView';
    
    $this->title                   = 'Контакты';
    $this->params['breadcrumbs'][] = ['label' => 'Контакты', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
        ButtonHelper::update($model->id, 'Редактировать'),
        ButtonHelper::create(),
        ButtonHelper::clearCache($model->site_id, $textType, $model->id),
        ButtonHelper::delete($model),
    ];
    
    $target = ($model->site_id === Parametr::siteId()) ? '_self' : '_blank';
    
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
    
    echo '<div class="card">';
    
    echo $this->render(
        '/layouts/tops/_viewHeader-start',
        [
            'id'     => $model->id,
            'title'  => $this->title,
            'status' => Constant::STATUS_ACTIVE,
            'buttons' => $buttons,
        ],
    )
?>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?= Html::a(
            'Перейти на сайт',
            [
                'admin/information/view',
                'id' => $model->site_id,
            ],
            [
                'class' => 'btn btn-primary',
            ],
        )
        ?>
    </div>

    <div class='card-body mb-2 p-2'>
        <div class='row'>
            <div class='col-lg-3 col-md-6'>
                <div class='card h-100 pb-2'>
                    <div class='card-header bg-light'>
                        <h5>О компании</h5>
                    </div>
                    <div class='card-body'>
                        <p>Название компании <?= Html::encode($model->name)
                            ?></p>
                        <p>Адрес вебсайта <?= Html::encode($model->website)
                            ?></p>
                        <p>Email для контактов <?= Html::encode($model->email)
                            ?></p>
                        <p>Email для заказов <?= Html::encode($model->zakaz_mail)
                            ?></p>
                        <p>О компании <?= Html::encode($model->description)
                            ?></p>
                    </div>
                </div>
            </div>
            <div class='col-lg-3 col-md-6'>
                <div class='card h-100 pb-2'>
                    <div class='card-header bg-light'>
                        <h5>Адрес компании</h5>
                    </div>
                    <div class='card-body'>
                        <p>Улица и дом: <?= Html::encode($model->address['streetAddress'] ?? '')
                            ?></p>
                        <p>Город: <?= Html::encode($model->address['addressLocality'] ?? '')
                            ?></p>
                        <p>Почтовый индекс: <?= Html::encode($model->address['postalCode'] ?? '')
                            ?></p>
                        <p>Страна: <?= Html::encode($model->address['addressCountry'] ?? '')
                            ?></p>
                    </div>
                </div>
            </div>
            <div class='col-lg-3 col-md-6'>
                <div class='card h-100 pb-2'>
                    <div class='card-header bg-light'>
                        <h5>Контактные телефоны</h5>
                    </div>
                    <div class='card-body'>
                        <?php
                            foreach ($model->getPhonesList() as $phone): ?>
                                <p>
                                    <strong><?= Html::encode($phone['label'] ?? 'Телефон')
                                        ?>:</strong>
                                    <?= Html::encode($phone['number'] ?? '')
                                    ?>
                                </p>
                            <?php
                            endforeach; ?>
                    </div>
                </div>
            </div>

            <div class='col-lg-3 col-md-6'>
                <div class='card h-100 pb-2'>
                    <div class='card-header bg-light'>
                        <h5>Аналитика</h5>
                    </div>
                    <div class='card-body'>
                        <?php
                            if (!empty($model->analytics)): ?>
                                <?php
                                foreach ($model->analytics as $analytic): ?>
                                    <div class="analytic-item mb-3 border-bottom">
                                        <strong>Сервис:</strong> <?= Html::encode($analytic['label'])
                                        ?>
                                        <br>
                                        <strong>ID:</strong> <?= Html::encode($analytic['number'])
                                        ?>
                                    </div>
                                
                                <?php
                                endforeach; ?>
                            <?php
                            else: ?>
                                <p>Данные по аналитике отсутствуют.</p>
                            <?php
                            endif; ?>
                    </div>
                </div>
            </div>

            <div class='col-lg-3 col-md-6'>
                <div class='card h-100 pb-2'>
                    <div class='card-header bg-light'>
                        <h5>Социальные сети</h5>
                    </div>
                    <div class='card-body'>
                        <?php
                            try {
                                foreach ($model->getSocialNetworksList() as $network): ?>
                                    <p>
                                        <strong><?= Html::encode($network['label'] ?? 'Соц. сеть')
                                            ?>:</strong>
                                        <a href="<?= Html::encode($network['number'] ?? '#')
                                        ?>"
                                           target="_blank"
                                           rel="noopener noreferrer">
                                            <?= Html::encode($network['number'] ?? '')
                                            ?>
                                        </a>
                                    </p>
                                <?php
                                endforeach;
                            }
                            catch (Exception $e) {
                            
                            } ?>
                    </div>
                </div>
            </div>

            <div class='col-lg-3 col-md-6'>
                <div class='card h-100 pb-2'>
                    <div class='card-header bg-light'>
                        <h5>Мессенджеры</h5>
                    </div>
                    <div class='card-body'>
                        <?php
                            foreach ($model->getMessengersList() as $messenger): ?>
                                <p>
                                    <strong><?= Html::encode($messenger['label'] ?? 'Мессенджер')
                                        ?>:</strong>
                                    <?= Html::encode($messenger['number'] ?? '')
                                    ?>
                                </p>
                            <?php
                            endforeach; ?>
                    </div>
                </div>
            </div>


            <div class='col-lg-3 col-md-6'>
                <div class='card h-100 pb-2'>
                    <div class='card-header bg-light'>
                        <h5>Платежные системы</h5>
                    </div>
                    <div class='card-body'>
                        <?php
                            foreach ($model->getMoneySystemsList() as $system): ?>
                                <p>
                                    <strong><?= Html::encode($system['label'] ?? 'Платежная система')
                                        ?>:</strong>
                                    <?= Html::encode($system['number'] ?? '')
                                    ?>
                                </p>
                            <?php
                            endforeach; ?>
                    </div>
                </div>
            </div>

            <div class='col-lg-3 col-md-6'>
                <div class='card h-100 pb-2'>
                    <div class='card-header bg-light'>
                        <h5>Доступные языки</h5>
                    </div>
                    <div class='card-body'>
                        <?php
                            foreach ($model->getLanguagesList() as $language): ?>
                                <p>
                                    <strong><?= Html::encode($language['label'] ?? 'Language')
                                        ?>:</strong>
                                    <?= Html::encode($language['number'] ?? '')
                                    ?>
                                </p>
                            <?php
                            endforeach; ?>
                    </div>
                    <div class="card-footer bg-success-subtle">
                        <button type="button" id="add-languages-field" class="btn btn-success">
                            Добавить язык
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>График работы</h5>
                        </div>
                        <div class='card-body'>
                            <?php
                                $workHours = $model->getWorkHoursList();
                                foreach ($workHours as $day => $schedule): ?>
                                    <div class="work-schedule-item mb-2">
                                        <strong><?= Html::encode($schedule['label'])
                                            ?>:</strong>
                                        <?php
                                            if ($schedule['isOpen']): ?>
                                                <div>
                                                    <?php
                                                        if (!empty($schedule['opens']) && !empty($schedule['closes'])): ?>
                                                            <?= Html::encode($schedule['opens'])
                                                            ?> - <?= Html::encode($schedule['closes'])
                                                            ?>
                                                        <?php
                                                        else: ?>
                                                            Рабочий день (время не указано)
                                                        <?php
                                                        endif; ?>
                                                </div>
                                            <?php
                                            else: ?>
                                                <div class="text-muted">Выходной</div>
                                            <?php
                                            endif; ?>
                                    </div>
                                <?php
                                endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class='col-lg-9 col-md-12'>
                    <div class='card h-100 pb-2'>
                        <div class='card-header bg-light'>
                            <h5>Расположение на карте</h5>
                        </div>
                        <div class='card-body'>
                            <?php
                                $locationData = $model->locationData;
                                if ($locationData): ?>
                                    <div id="map" style="height: 400px; width: 100%; position: relative;"></div>
                                    
                                    <?php
                                    try {
                                        $this->registerJsFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [
                                            'depends' => [JqueryAsset::class],
                                        ]);
                                    }
                                    catch (InvalidConfigException $e) {
                                    
                                    }
                                    try {
                                        $this->registerCssFile('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
                                    }
                                    catch (InvalidConfigException $e) {
                                    
                                    }
                                    
                                    // Инициализация карты
                                    $js = <<<JS
                                            // Убираем DOMContentLoaded, так как Yii2 уже гарантирует загрузку после DOM
                                            var map = L.map('map', {
                                                center: [{$locationData['lat']}, {$locationData['lon']}],
                                                zoom: {$locationData['scale']},
                                                scrollWheelZoom: true
                                            });
                                            
                                            // Добавляем слой карты
                                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                                                maxZoom: 19
                                            }).addTo(map);
                                            
                                            // Добавляем маркер
                                            var marker = L.marker([{$locationData['lat']}, {$locationData['lon']}]).addTo(map);
                                            marker.bindPopup('Мы находимся здесь').openPopup();
                                            
                                            // Принудительно обновляем размер карты после инициализации
                                            setTimeout(function() {
                                                map.invalidateSize();
                                            }, 100);
                                    JS;
                                    
                                    // Регистрируем скрипт с более высоким приоритетом
                                    $this->registerJs($js);
                                    ?>
                                <?php
                                else: ?>
                                    <div class="alert alert-info">
                                        Координаты местоположения не указаны
                                    </div>
                                <?php
                                endif; ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>

<?php
    echo '</div>';
