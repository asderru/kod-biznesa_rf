<?php
    
    use backend\widgets\PagerWidget;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Library\Book */
    /* @var $childs core\edit\entities\Library\Book[] */
    /* @var $type bool|string */
    /* @var $content array */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_calculate_view';
    
    $this->title = ($type)
        ? 'Использованы слова в ' . $model->name
        :
        'Все слова в ' . $model->name;
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    
    $this->params['breadcrumbs'][] = 'Подсчет слов';
    
    $buttons = [];
    
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

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        
        <?php
            if ($model->hasChildren()) { ?>
                <table class='table table-sm'>
                    <tbody>
                    
                    <?php
                        $i = 1;
                        foreach ($childs as $model) { ?>


                            <tr>
                                <td>
                                    <?= $i ?>
                                </td>
                                <td>
                                    <?= Html::a(
                                        Html::encode($model->depthName),
                                        $model->viewUrl,
                                    )
                                    ?>
                                </td>
                                <td><?= Html::a(
                                        'подсчитать все слова',
                                        [
                                            'view',
                                            'id' => $model->id,
                                        ],
                                        [
                                            'class' => 'btn btn-sm btn-success mb-2',
                                        ],
                                    )
                                    ?>
                                </td>
                                <td><?= Html::a(
                                        'подсчитать слова',
                                        [
                                            'view',
                                            'id'   => $model->id,
                                            'type' => 'unique',
                                        ],
                                        [
                                            'class' => 'btn btn-sm btn-primary mb-2',
                                        ],
                                    )
                                    ?>
                                </td>
                                <td>
                                    <?= Html::a(
                                        'подсчитать лексемы',
                                        [
                                            'view',
                                            'id'   => $model->id,
                                            'type' => 'lex',
                                        ],
                                        [
                                            'class' => 'btn btn-sm btn-info text-white mb-2',
                                        ],
                                    )
                                    ?>
                                </td>
                            </tr>
                            
                            <?php
                            $i++;
                        } ?>
                    </tbody>
                </table>
                <hr>
                <?php
            } ?>
    </div>

    <div class='card-body'>
        <div class='d-flex justify-content-between'>
            <div>
                <h4>Слова в книге:</h4>
                <small>всего слов: <?= count($content)
                    ?></small>
            </div>
            <div>
                <?= ($type) ? Html::a(
                        'всего слов',
                        [
                            'view',
                            'id'   => $model->id,
                            'type' => false,
                        ],
                        [
                            'class' => 'btn btn-sm btn-success mb-2',
                        ],
                    ) . Html::a(
                        'лексемы',
                        [
                            'view',
                            'id'   => $model->id,
                            'type' => 'lex',
                        ],
                        [
                            'class' => 'btn btn-sm btn-info mb-2',
                        ],
                    ) : null
                ?>
                <?= (!$type) ? Html::a(
                    'уникальные слова',
                    [
                        'view',
                        'id'   => $model->id,
                        'type' => 'unique',
                    ],
                    [
                        'class' => 'btn btn-sm btn-primary mb-2',
                    ],
                ) : null
                ?>
                <?= ($type !== 'lex') ? Html::a(
                    'подсчитать лексемы',
                    [
                        'view',
                        'id'   => $model->id,
                        'type' => 'lex',
                    ],
                    [
                        'class' => 'btn btn-sm btn-info mb-2',
                    ],
                ) : null
                ?>
            </div>
        </div>
        <hr>
        <?php
            foreach ($content as $n => $row) {
                echo ($n + 1) . '. ' . $row . "<br>\r\n";
            }
        ?>
    </div>

<?php
    echo '</div>';
