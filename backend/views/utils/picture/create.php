<?php
    
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\ActiveForm;
    use yii\helpers\Url;
    use yii\bootstrap5\Html;
    use yii\web\View;
    
    $this->registerCsrfMetaTags();
    
    /* @var $this View */
    /* @var $parent Razdel|Product */
    /* @var $photoUrl string */
    /* @var $photoPath string */
    /* @var $targetPath string */
    /* @var $form ActiveForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $sizes array */
    /* @var $width int */
    /* @var $height int */
    /* @var $targetUrl string */
    
    const LAYOUT_ID = '#utils_picture_create';
    
    $this->title = 'Редактирование';
    
    $this->params['breadcrumbs'][] = [
        'label' => 'Картинки ' . $parent->name,
        'url'   => [
            'view',
            'textType' => $parent::TEXT_TYPE,
            'id'       => $parent->id,
        ],
    ];
    
    $this->params['breadcrumbs'][] = $this->title;
    
    $saveCropUrl = Url::to(['/utils/picture/save-crop']);
    
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

    <div class="card">
        <div class="card-header bg-body-secondary">
            <h4>Правка картинки <?= $parent->name ?></h4>
        </div>
        <div class="card-body d-flex justify-content-around">
            <ol>
                <li>Источник - <?= $parent->name ?></li>
                <li>Url оригинала - <?= Html::a($photoUrl, $photoUrl, [
                        'target' => '_blank',
                    ])
                    ?>
                </li>
                <li>Путь к оригиналу - <?= $photoPath ?></li>
                <li>Url картинки - <?= Html::a($targetUrl, $targetUrl, [
                        'target' => '_blank',
                    ])
                    ?>
                </li>
                <li>Путь к картинке - <?= $targetPath ?></li>
            </ol>
            <p>
                Кликнуть по картинке, выделить нужную область и сохранить! Кнопка сохранения - под картинкой)
            </p>
        </div>
        <div class='crop-container'>
            <img src="<?= $photoUrl ?>" id='cropImage'/>

            <div class='card-footer'>
                <div class='form-group text-center'>
                    <?= Html::button('Сохранить', ['class' => 'btn btn-success', 'id' => 'saveCrop'])
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
    try {
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js');
    }
    catch (InvalidConfigException $e) {
    
    }
    try {
        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.4/js/Jcrop.min.js', ['depends' => 'yii\web\JqueryAsset']);
    }
    catch (InvalidConfigException $e) {
    
    }
    try {
        $this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.4/css/Jcrop.min.css');
    }
    catch (InvalidConfigException $e) {
    
    }
    
    $js             = <<<JS
$(document).ready(function() {
    let jcrop_api;
    let cropCoords;
    const targetWidth = $width;
    const targetHeight = $height;
    const sourcePath = "$photoPath";
    const targetPath = "$targetPath";
    
    // Initialize Jcrop
    $("#cropImage").Jcrop({
        aspectRatio: targetWidth / targetHeight,
        minSize: [targetWidth, targetHeight],
        onSelect: function(c) {
            cropCoords = {
                x: Math.round(c.x),
                y: Math.round(c.y),
                w: Math.round(c.w),
                h: Math.round(c.h)
            };
            console.log("Selected coordinates:", cropCoords);
        },
        onRelease: function() {
            cropCoords = null;
        }
    }, function() {
        jcrop_api = this;
        
        let img = $("#cropImage")[0];
        let naturalWidth = img.naturalWidth;
        let naturalHeight = img.naturalHeight;
        
        let selectionWidth = Math.min(naturalWidth, targetWidth);
        let selectionHeight = Math.min(naturalHeight, targetHeight);
        
        let x = (naturalWidth - selectionWidth) / 2;
        let y = (naturalHeight - selectionHeight) / 2;
        
        jcrop_api.setSelect([x, y, x + selectionWidth, y + selectionHeight]);
    });

    $("#saveCrop").click(function() {
        if (!cropCoords) {
            alert("Пожалуйста, выберите область для обрезки");
            return;
        }

        const \$button = $(this);
        \$button.prop("disabled", true);
        \$button.text("Сохранение...");

        $.ajax({
            url: '$saveCropUrl',
            type: "POST",
            dataType: 'json',
            data: {
                sourcePath: sourcePath,
                targetPath: targetPath,
                coords: cropCoords
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {
                if (response.success) {
                    alert("Изображение успешно сохранено");
                    window.location.reload();
                } else {
                    alert("Ошибка при сохранении: " + (response.message || "Неизвестная ошибка"));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error details:", {
                    status: jqXHR.status,
                    statusText: jqXHR.statusText,
                    responseText: jqXHR.responseText
                });
                alert("Произошла ошибка при отправке запроса: " +
                    (jqXHR.responseJSON?.message || textStatus));
            },
            complete: function() {
                \$button.prop("disabled", false);
                \$button.text("Сохранить");
            }
        });
    });
});
JS;
    
    $this->registerJs($js);
