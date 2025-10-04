<?php
    
    use core\edit\entities\Tools\Keyword;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model Keyword */
    
    const TOOLS_KEYWORD_PART_LAYOUT = '#content_keyword_partView';
    echo PrintHelper::layout(TOOLS_KEYWORD_PART_LAYOUT);

?>
<div class='row'>

    <div class='col-lg-6'>

        <div class='card h-100'>

            <div class='card-header bg-light'>
                <strong>
                    Информация
                </strong>
            </div>
            <div class='card-body table-responsive mb-3'>
                
                <?= $this->render(
                    '@app/views/tools/keyword/_partDetailView.php',
                    [
                        'model' => $model,
                    ],
                )
                ?>

            </div>
        </div>

    </div>

    <div class='col-lg-6'>

        <div class='card h-100'>


            <div class='card-header bg-light'>
                <strong>
                    Ключевое слово
                </strong>
            </div>
            <div class='card-body'>
                <?= Html::encode($model->name)
                ?>
            </div>


            <div class='card-header bg-light'>
                <strong>
                    Другие ключевые слова
                </strong>
            </div>
            <div class='card-body'>
                <?php
                    try {
                        $words = json_decode($model->words, true, 512, JSON_THROW_ON_ERROR);
                    }
                    catch (JsonException $e) {
                    } // Преобразуем строку JSON в массив
                    
                    if (is_array($words)) {
                        // Преобразуем каждый элемент массива в элемент списка <li>
                        $listItems = array_map(static function ($word) {
                            return '<li>' . $word . '</li>';
                        }, $words);
                        
                        // Обернем все элементы в список <ul>
                        echo '<ul>' . implode('', $listItems) . '</ul>';
                    }
                    
                    return ''; // Пустое значение
                ?>

            </div>
        </div>

    </div>
</div>
