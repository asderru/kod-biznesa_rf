<?php
    
    use backend\widgets\PagerWidget;
    use core\edit\forms\UploadPhotoForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Utils\Photo */
    /* @var $uploadForm UploadPhotoForm */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    /* @var $pictures array */
    /* @var $sizes array */
    
    const LAYOUT_ID = '#utils_picture_view';
    
    $this->title = 'Изображения модели ' . $model->name;
    
        $this->params['breadcrumbs'][] = $this->title;
    
    $buttons = [
            
            ];
    
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
    '/layouts/tops/_viewHeaderModel',
        [
            'model' => $model,
            'textType' => $textType,
            'buttons'  => $buttons, // передаем массив кнопок
        ],
)
?>

    <div class='card-body'>
        <div class='table-responsive'>
            <table class='table'>
                <thead>
                <tr>
                    <th scope='col'>#</th>
                    <th scope='col'>Изображение</th>
                    <th scope='col'>Свойства</th>
                    <th scope='col'>Правка</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($pictures as $index => $picture) : ?>
                        <?php
                        if ($picture) : ?>
                            <?php
                            $size = $sizes[$index]; ?>
                            <tr>
                                <th scope="row"><?= $index ?></th>
                                <td>
                                    <?= Html::img($picture, [
                                        'class' => 'img-fluid custom-img',
                                        'style' => "max-width:{$size['width']}px",
                                        'alt'   => "Image $index",
                                    ])
                                    ?>
                                </td>
                                <td>
                                    Адрес - <?= Html::a($picture, $picture, ['target' => '_blank'])
                                    ?><br>
                                    Ширина - <?= $size['width'] ?> пикс.<br>
                                    Высота - <?= $size['height'] ?> пикс.<br>
                                    Колонки - <?= $index ?>
                                </td>
                                <td><?= Html::a(
                                        'Удалить',
                                        [
                                            'delete',
                                            'url'      => $picture,
                                            'textType' => $model::TEXT_TYPE,
                                            'id'       => $model->id,
                                        ],
                                        [
                                            'class'        => 'btn btn-sm btn-danger',
                                            'data-method'  => 'post', // This will ensure the request is made via POST
                                            'data-confirm' => 'Вы уверены, что хотите удалить этот элемент?', // Optional: adds a confirmation dialog
                                        ],
                                    )
                                    ?><br>
                                    <?= Html::a(
                                        'Править',
                                        [
                                            'create',
                                            'url'      => $picture,
                                            'textType' => $model::TEXT_TYPE,
                                            'id'       => $model->id,
                                            'column'   => $index,
                                        ],
                                        [
                                            'class' => 'btn btn-sm btn-primary',
                                        ],
                                    )
                                    ?>
                                    <br>
                                    <?= Html::a(
                                        'Заменить',
                                        [
                                            'update',
                                            'url'      => $picture,
                                            'textType' => $model::TEXT_TYPE,
                                            'id'       => $model->id,
                                        ],
                                        [
                                            'class' => 'btn btn-sm btn-success',
                                        ],
                                    )
                                    ?>
                                </td>

                            </tr>
                        <?php
                        endif; ?>
                    <?php
                    endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

<?php echo '</div>';
