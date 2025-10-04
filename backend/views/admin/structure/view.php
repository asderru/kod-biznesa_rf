<?php
    
    use backend\assets\TopScriptAsset;
    use core\edit\entities\Admin\Information;
    use core\helpers\ButtonHelper;
    use core\helpers\FaviconHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\types\TypeHelper;
    use yii\bootstrap5\Html;
    
    TopScriptAsset::register($this);
    
    /* @var $this yii\web\View */
    /* @var $site Information */
    /* @var $models core\edit\entities\Blog\Category[] */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#admin_structure_view';
    
    $this->title = 'Структура ' . TypeHelper::getName($textType, 1, true) . ' сервера';
    
    $buttons = [
        ButtonHelper::indexPanel(),
        ButtonHelper::adminStructure(),
    ];
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    $this->params['breadcrumbs'][] = TypeHelper::getName($textType, null, false, true);
    
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
    <div class='card-header bg-light d-flex justify-content-between p-2'>
        <div class='h4'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <?= ButtonHelper::collapse()
        ?>
    </div>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?php
            foreach ($buttons as $button) {
                echo $button;
            }
        ?>
    </div>
    <div class='card-body'>
        <div class='row' style='height: 600px;'>
            <div id='parent-area' class='col-lg-6' style='height: 100%; overflow-y: auto;'>
                <?php
                    foreach ($models as $index => $model):
                        $indent = str_repeat(' ·', $model['depth']) . ' ';
                        $id = $model['id'];
                        ?>
                        <?php
                        if ($model['status'] === 0): ?>
                            <div class="d-flex justify-content-between border mt-2 bg-info-subtle">
                                <h4 class="p-2"><small>сайт</small> <?= ParametrHelper::getSiteName
                                    (
                                        $model['site_id'],
                                    ) ?>
                                </h4>
                                <span class="p-2">
                                <?= ButtonHelper::createType($textType, $model['site_id'], 'Добавить', 'success') ?>
                                <?= ButtonHelper::clearCache($model['site_id'], $textType) ?>
                                <?= ButtonHelper::sortType($model['site_id'], $textType) ?>
                            </span>
                            </div>
                        <?php
                        else: ?>
                            <!-- Каждый элемент аккордеона независим -->
                            <div class="accordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?= $id ?>">
                                        <button class="accordion-button collapsed"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse<?= $index ?>"
                                                aria-expanded="false"
                                                aria-controls="collapse<?= $index ?>">
                                            <?php
                                                try {
                                                    echo $indent . $model['id'] .
                                                         '. <span class="px-2">'
                                                         . $model['title'] . '</span> '
                                                         . FaviconHelper::statusSmall($model['status']);
                                                }
                                                catch (Exception $e) {
                                                
                                                }
                                            ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $index ?>"
                                         class="accordion-collapse collapse"
                                         aria-labelledby="heading<?= $id ?>">
                                        <div class="accordion-body">
                                            <?= FormatHelper::asHtml($model['description']) ?>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <?= ButtonHelper::viewType($textType, $id, 'Смотреть', true) ?>
                                                <?= Html::a(
                                                    'Сменить сайт',
                                                    [
                                                        '/admin/change/update',
                                                        'textType' => $textType,
                                                        'id'       => $id,
                                                    ],
                                                    [
                                                        'class' => 'btn btn-sm btn-success',
                                                    ],
                                                ) ?>
                                                <?= Html::a(
                                                    'Смотреть наследников',
                                                    ['descendants', 'textType' => $textType, 'parentId' => $id],
                                                    [
                                                        'class'     => 'btn btn-sm btn-primary view-descendants',
                                                        'data-pjax' => '0',
                                                    ],
                                                ) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        <?php
                        endif;
                    endforeach; ?>
            </div>


            <div class='col-lg-6'>
                <div class="card">
                    <div class="card-header">Наследники</div>
                    <div class="card-body" id='descendants-area' style='height: 100%; overflow-y: auto;'>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const viewDescendantsBtns = document.querySelectorAll('.view-descendants');
        const descendantsArea = document.getElementById('descendants-area');
        const modalElement = document.getElementById('model-details-modal');
        const modalTitle = modalElement.querySelector('.modal-title');
        const modalBody = modalElement.querySelector('.modal-body');

        viewDescendantsBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href');

                descendantsArea.innerHTML = 'Загрузка...';

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Ошибка сети');
                        }
                        return response.json();
                    })
                    .then(data => {
                        descendantsArea.innerHTML = '';
                        const groupedByType = {};

                        // Flatten and group the nested arrays
                        data.forEach(group => {
                            if (Array.isArray(group) && group.length > 0) {
                                group.forEach(item => {
                                    if (!groupedByType[item.type_name]) {
                                        groupedByType[item.type_name] = [];
                                    }
                                    groupedByType[item.type_name].push(item);
                                });
                            }
                        });

                        // Render grouped descendants
                        Object.entries(groupedByType).forEach(([typeName, items]) => {
                            const p = document.createElement('p');
                            p.className = 'mb-3';

                            const itemsList = items.map(item =>
                                `ID: ${item.id}, <a href="#"
                            class="descendant-item"
                            data-id="${item.id}"
                            data-name="${item.name}"
                            data-array-type="${item.array_type}"
                            data-title="${item.title || ''}"
                            data-description="${item.description || ''}"
                            data-status="${item.status}"
                            data-slug="${item.slug || ''}"
                            data-link="${item.link || ''}"
                            data-depth="${item.depth}"
                            data-updated-at="${item.updated_at}"
                            data-rating="${item.rating || 0}"
                            data-photo="${item.photo || ''}"
                            data-type-name="${item.type_name || ''}"
                        >${item.name}</a>`
                            ).join('<br>');

                            p.innerHTML = `<strong>${typeName}:</strong><br>${itemsList}`;
                            descendantsArea.appendChild(p);
                        });

                        // Add click event to descendants
                        descendantsArea.addEventListener('click', function (e) {
                            const link = e.target.closest('.descendant-item');
                            if (link) {
                                e.preventDefault();

                                const modelDetails = {
                                    id: link.dataset.id,
                                    name: link.dataset.name,
                                    arrayType: link.dataset.arrayType,
                                    title: link.dataset.title,
                                    description: link.dataset.description,
                                    status: link.dataset.status,
                                    slug: link.dataset.slug,
                                    link: link.dataset.link,
                                    depth: link.dataset.depth,
                                    updatedAt: new Date(parseInt(link.dataset.updatedAt) * 1000).toLocaleString(),
                                    rating: parseInt(link.dataset.rating),
                                    photo: link.dataset.photo,
                                    typeName: link.dataset.typeName
                                };

                                modalTitle.textContent = modelDetails.name;
                                modalBody.innerHTML = `
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="${modelDetails.photo}" alt="${modelDetails.name}" class="img-fluid mb-3">
                                    <hr>
                                    <a href="goto?textType=${modelDetails.arrayType}&parentId=${modelDetails.id}"
                                    class="btn btn-sm  btn-primary" target="_blank">Открыть в новом
                                    окне</a>
                                    <a href="goto-edit?textType=${modelDetails.arrayType}&parentId=${modelDetails.id}"
                                    class='btn btn-sm  btn-sucess' target='_blank'>Редактировать в новом окне</a>
                                </div>
                                <div class="col-md-8">
                                    <dl class="row">
                                        <dt class="col-sm-4">ID:</dt>
                                        <dd class="col-sm-8">${modelDetails.id}</dd>
                                        <dt class="col-sm-4">Название:</dt>
                                        <dd class="col-sm-8">${modelDetails.name}</dd>
                                        <dt class="col-sm-4">Тип наследника:</dt>
                                        <dd class="col-sm-8">${modelDetails.typeName || 'Не указан'}</dd>
                                        <dt class="col-sm-4">Заголовок:</dt>
                                        <dd class="col-sm-8">${modelDetails.title || 'Не указан'}</dd>
                                        <dt class='col-sm-4'>Идентификатор:</dt>
                                        <dd class='col-sm-8'>${modelDetails.slug || 'Не указан'}</dd>
                                        <dt class='col-sm-4'>Ссылка:</dt>
                                        <dd class='col-sm-8'>${modelDetails.link || 'Не указана'}</dd>
                                        <dt class='col-sm-4'>Обновлено:</dt>
                                        <dd class='col-sm-8'>${modelDetails.updatedAt}</dd>
                                        <dt class='col-sm-4'>Описание:</dt>
                                        <dd class='col-sm-8'>${modelDetails.description || 'Нет описания'}</dd>
                                        <dt class="col-sm-4">Статус:</dt>
                                        <dd class="col-sm-8">${modelDetails.status}</dd>
                                        <dt class="col-sm-4">Глубина:</dt>
                                        <dd class="col-sm-8">${modelDetails.depth}</dd>
                                        <dt class="col-sm-4">Рейтинг:</dt>
                                        <dd class="col-sm-8">${modelDetails.rating}</dd>
                                    </dl>
                                </div>
                            </div>
                        `;

                                const modal = new bootstrap.Modal(modalElement);
                                modal.show();
                        }
                        });

                        if (descendantsArea.children.length === 0) {
                            descendantsArea.innerHTML = 'Наследники не найдены';
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка:', error);
                        descendantsArea.innerHTML = 'Произошла ошибка при загрузке данных';
                    });
            });
        });
    });


</script>

<!-- Universal Modal for Model Details -->
<div class='modal fade' id='model-details-modal' tabindex='-1' aria-labelledby='model-details-modal-label'
     aria-hidden='true'>
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='model-details-modal-label'>Детали модели</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <!-- Content will be dynamically inserted here -->
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Закрыть</button>
            </div>
        </div>
    </div>
</div>
