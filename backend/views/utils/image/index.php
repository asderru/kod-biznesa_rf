<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;

    /* @var $this yii\web\View */
    /* @var $images array */

    $this->title                   = 'Работа с изображениями';
    $this->params['breadcrumbs'][] = $this->title;

    $uploadUrl  = Url::to(['upload']);
    $replaceUrl = Url::to(['replace']);
    $deleteUrl  = Url::to(['delete']);

?>
<div class="image-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row mb-3">
        <div class='col'>
            <div class='alert alert-warning alert-dismissible fade show border-start border-warning border-4 shadow-sm'
                 role='alert'>
                <div class='d-flex align-items-start'>
                    <div class='flex-shrink-0 me-3'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor'
                             class='bi bi-exclamation-triangle-fill' viewBox='0 0 16 16'>
                            <path d='M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z'/>
                        </svg>
                    </div>
                    <div class='flex-grow-1'>
                        <h5 class='alert-heading mb-2'>
                            <strong>Внимание!</strong>
                        </h5>
                        <p class='mb-2'>
                            Для работы с виджетом зарегистрируйтесь на сайте
                            <a href='https://www.tiny.cloud/' class='alert-link fw-semibold' target='_blank'
                               rel='noopener noreferrer'>
                                https://www.tiny.cloud/
                                <svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='currentColor'
                                     class='bi bi-box-arrow-up-right ms-1' viewBox='0 0 16 16'>
                                    <path fill-rule='evenodd'
                                          d='M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z'/>
                                    <path fill-rule='evenodd'
                                          d='M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z'/>
                                </svg>
                            </a>
                        </p>
                        <p class='mb-0'>
                              <span class='badge bg-warning text-dark px-3 py-2'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor'
                                     class='bi bi-tag-fill me-1' viewBox='0 0 16 16'>
                                  <path d='M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1H2zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z'/>
                                </svg>
                                Требуется план: <strong>Essential $79/месяц</strong>. Полученный код сообщите администратору!
                              </span>
                        </p>
                    </div>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <button type="button" class="btn btn-success" onclick="showUploadModal()">Загрузить каринку на
                сервер!
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Превью</th>
                <th>Файл</th>
                <th>Адрес</th>
                <th>Размер</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>
            <?php
                foreach ($images as $image): ?>
                    <tr>
                        <td>
                            <img src="<?= Html::encode($image['webPath']) ?>"
                                 alt="<?= Html::encode($image['name']) ?>"
                                 style="max-width: 100px; max-height: 100px;">
                        </td>
                        <td><?= Html::encode($image['name']) ?></td>
                        <td><?= Html::encode($image['path']) ?></td>
                        <td><?= Yii::$app->formatter->asShortSize($image['size']) ?></td>
                        <td><?= Yii::$app->formatter->asDatetime($image['modified']) ?></td>
                        <td>
                            <button type="button"
                                    class="btn btn-primary btn-sm replace-btn"
                                    data-path="<?= Html::encode($image['path']) ?>">
                                Заменить
                            </button>
                            <button type="button"
                                    class="btn btn-danger btn-sm delete-btn"
                                    data-path="<?= Html::encode($image['path']) ?>">
                                Удалить
                            </button>
                        </td>
                    </tr>
                <?php
                endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- [Модальные окна остаются без изменений] -->
<!-- Replace Modal -->
<div class='modal fade' id='replaceModal' tabindex='-1' aria-labelledby='replaceModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='replaceModalLabel'>Заменить изображение</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <div class='alert alert-danger' id='replaceError' style='display: none;'></div>
                <form id='replaceForm' enctype='multipart/form-data'>
                    <input type='hidden' id='oldPath' name='oldPath'>
                    <div class='mb-3'>
                        <label for='newFile' class='form-label'>Выбрать новое изображение</label>
                        <input type='file' class='form-control' id='newFile' name='newFile' accept='image/*'>
                    </div>
                </form>
                <div class='progress mt-3' style='display: none;'>
                    <div class='progress-bar' role='progressbar' style='width: 0%'></div>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Отмена</button>
                <button type='button' class='btn btn-primary' onclick='replaceImage()'>Заменить!</button>
            </div>
        </div>
    </div>
</div>


<?php

    $replaceScript = <<<JS


    // Обработчик для кнопок замены
    $(document).ready(function() {
        $('.replace-btn').on('click', function() {
            const path = $(this).data('path');
            showReplaceModal(path);
        });
    });

    window.showReplaceModal = function(path) {
        $('#oldPath').val(path);
        $('#replaceError').hide();
        $('#replaceForm')[0].reset();
        $('.progress').hide();
        
        const replaceModal = new bootstrap.Modal(document.getElementById('replaceModal'));
        replaceModal.show();
    };

    window.replaceImage = function() {
        const fileInput = document.getElementById('newFile');
        const file = fileInput.files[0];
        const oldPath = $('#oldPath').val();
        
        // Валидация
        if (!file) {
            $('#replaceError').text('Please select a file').show();
            console.error('No file selected');
            return;
        }

        if (!file.type.match('image.*')) {
            $('#replaceError').text('Please select an image file').show();
            console.error('Invalid file type:', file.type);
            return;
        }

        const formData = new FormData();
        formData.append('newFile', file);
        formData.append('oldPath', oldPath);

        // Показываем прогресс
        $('.progress').show();
        $('.progress-bar').css('width', '0%');

        $.ajax({
            url: '/utils/image/replace',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        $('.progress-bar').css('width', percent + '%');
                        console.log('Replace progress:', percent + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                console.log('Replace response:', response);
                if (response.success) {
                    const replaceModal = bootstrap.Modal.getInstance(document.getElementById('replaceModal'));
                    replaceModal.hide();
                    // Обновляем страницу, чтобы показать новое изображение
                    location.reload();
                } else {
                    $('#replaceError').text(response.message).show();
                    console.error('Replace failed:', response.message);
                }
            },
            error: function(xhr, status, error) {
                $('#replaceError').text('Error replacing file: ' + error).show();
                console.error('Replace error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
            },
            complete: function() {
                $('.progress').hide();
            }
        });
    };

    // Валидация при выборе файла
    $('#newFile').on('change', function() {
        const file = this.files[0];
        if (file) {
            console.log('File selected for replacement:', {
                name: file.name,
                type: file.type,
                size: file.size + ' bytes'
            });
            
            if (!file.type.match('image.*')) {
                $('#replaceError').text('Please select an image file').show();
                console.error('Invalid file type:', file.type);
                this.value = '';
            } else {
                $('#replaceError').hide();
            }
        }
    });
JS;


    $this->registerJs($replaceScript);
?>


<!-- Upload Modal -->
<div class='modal fade' id='uploadModal' tabindex='-1' aria-labelledby='uploadModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='uploadModalLabel'>Загрузить изображение</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <div class='alert alert-danger' id='uploadError' style='display: none;'></div>
                <form id='uploadForm' enctype='multipart/form-data'>
                    <div class='mb-3'>
                        <label for='imageFile' class='form-label'>Выбрать изображение</label>
                        <input type='file' class='form-control' id='imageFile' name='imageFile' accept='image/*'>
                    </div>
                </form>
                <div class='progress mt-3' style='display: none;'>
                    <div class='progress-bar' role='progressbar' style='width: 0%'></div>
                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Отмена</button>
                <button type='button' class='btn btn-primary' onclick='uploadImage()'>Заменить</button>
            </div>
        </div>
    </div>
</div>
<?php
    // Сначала определим функции как отдельные строки JavaScript
    $uploadModalScript = <<<JS
    window.showUploadModal = function() {
        const myModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        $('#uploadError').hide();
        $('#uploadForm')[0].reset();
        $('.progress').hide();
        myModal.show();
    };

    window.uploadImage = function() {
        const fileInput = document.getElementById('imageFile');
        const file = fileInput.files[0];
        
        // Validate file selection
        if (!file) {
            $('#uploadError').text('Please select a file').show();
            console.error('No file selected');
            return;
        }

        // Validate file type
        if (!file.type.match('image.*')) {
            $('#uploadError').text('Please select an image file').show();
            console.error('Invalid file type:', file.type);
            return;
        }

        const formData = new FormData();
        formData.append('imageFile', file);

        // Show progress bar
        $('.progress').show();
        $('.progress-bar').css('width', '0%');

        $.ajax({
            url: '/utils/image/upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        $('.progress-bar').css('width', percent + '%');
                        console.log('Upload progress:', percent + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                console.log('Upload response:', response);
                if (response.success) {
                    const myModal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
                    myModal.hide();
                    // Optionally refresh the image list or show success message
                    alert('Image uploaded successfully');
                } else {
                    $('#uploadError').text(response.message).show();
                    console.error('Upload failed:', response.message);
                }
            },
            error: function(xhr, status, error) {
                $('#uploadError').text('Error uploading file: ' + error).show();
                console.error('Upload error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
            },
            complete: function() {
                $('.progress').hide();
            }
        });
    };

    // Add file input change listener for immediate validation
    $(document).ready(function() {
        $('#imageFile').on('change', function() {
            const file = this.files[0];
            if (file) {
                console.log('File selected:', {
                    name: file.name,
                    type: file.type,
                    size: file.size + ' bytes'
                });
                
                if (!file.type.match('image.*')) {
                    $('#uploadError').text('Please select an image file').show();
                    console.error('Invalid file type:', file.type);
                    this.value = ''; // Clear the input
                } else {
                    $('#uploadError').hide();
                }
            }
        });
    });
JS;

    $this->registerJs($uploadModalScript);
?>
