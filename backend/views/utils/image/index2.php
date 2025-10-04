<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $images array */

$this->title = 'Image Management';
$this->params['breadcrumbs'][] = $this->title;

$uploadUrl = Url::to(['upload']);
$replaceUrl = Url::to(['replace']);
$deleteUrl = Url::to(['delete']);

?>
<div class="image-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row mb-3">
        <div class="col">
            <button type="button" class="btn btn-success" onclick="showUploadModal()">Upload New Image</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Name</th>
                    <th>Path</th>
                    <th>Size</th>
                    <th>Modified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($images as $image): ?>
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
                            Replace
                        </button>
                        <button type="button"
                                class="btn btn-danger btn-sm delete-btn"
                                data-path="<?= Html::encode($image['path']) ?>">
                            Delete
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- [Модальные окна остаются без изменений] -->

<?php
$script = <<<JS
// Обработчики кнопок через делегирование событий
$(document).on('click', '.replace-btn', function() {
    var path = $(this).data('path');
    $('#oldPath').val(path);
    $('#replaceModal').modal('show');
});

$(document).on('click', '.delete-btn', function() {
    var path = $(this).data('path');
    if (confirm('Are you sure you want to delete this image?')) {
        deleteImage(path);
    }
});

function showUploadModal() {
    $('#uploadModal').modal('show');
}

function uploadImage() {
    var formData = new FormData($('#uploadForm')[0]);
    $.ajax({
        url: '${uploadUrl}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Upload failed');
        }
    });
}

async function replaceImage() {
    try {
        const formData = new FormData(document.getElementById('replaceForm'));
        console.log('FormData contents:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        const response = await fetch(replaceUrl, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        console.log('Server response:', data);
        
        if (data.success) {
            console.log('Replace successful, reloading page...');
            location.reload();
        } else {
            console.error('Replace failed:', data.message);
            alert(data.message);
        }
    } catch (error) {
        console.error('Replace error:', error);
        alert('Replace failed');
    }
}
function deleteImage(path) {
    $.ajax({
        url: '${deleteUrl}',
        type: 'POST',
        data: {path: path},
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Delete failed');
        }
    });
}
JS;


$this->registerJs($script);
?>
<!-- Replace Modal -->
<div class='modal' id='replaceModal' tabindex='-1' aria-labelledby='replaceModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='replaceModalLabel'>Replace Image</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <form id='replaceForm'>
                    <input type='hidden' name='oldPath' id='oldPath'>
                    <div class='mb-3'>
                        <label class='form-label'>New Image File</label>
                        <input type='file' class='form-control' name='newFile' accept='image/*' required>
                    </div>
                </form>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                <button type='button' class='btn btn-primary' onclick='replaceImage()'>Replace</button>
            </div>
        </div>
    </div>
</div>
