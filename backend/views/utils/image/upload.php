<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $images array */

$this->title = 'Image Management';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="image-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row mb-3">
        <div class="col">
            <button type="button" class="btn btn-success" onclick="showUploadModal()">Upload New Image</button>
        </div>
    </div>
</div>

    <!-- Upload Modal -->
    <div class='modal fade' id='uploadModal' tabindex='-1' aria-labelledby='uploadModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='uploadModalLabel'>Upload Image</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <div class='alert alert-danger' id='uploadError' style='display: none;'></div>
                    <form id='uploadForm' enctype='multipart/form-data'>
                        <div class='mb-3'>
                            <label for='imageFile' class='form-label'>Select Image</label>
                            <input type='file' class='form-control' id='imageFile' name='imageFile' accept='image/*'>
                        </div>
                    </form>
                    <div class='progress mt-3' style='display: none;'>
                        <div class='progress-bar' role='progressbar' style='width: 0%'></div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                    <button type='button' class='btn btn-primary' onclick='uploadImage()'>Upload</button>
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
