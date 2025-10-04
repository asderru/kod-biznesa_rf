<?php
    
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $books core\edit\entities\Library\Book[] */
    /* @var $type bool|string */
    /* @var $content array */
    /* @var $wordCount int */
    /* @var $actionId string */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_calculate-view-all';
    
    $this->title = ($type)
        ? 'Использованы слова'
        :
        'Все слова';
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['index']];
    
    $this->params['breadcrumbs'][] = $this->title;
    
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
<div class='card rounded-top rounded-1'>
    <div class='card-header bg-light d-flex justify-content-between'>
        <div class='h5'>
            <?= Html::encode($this->title)
            ?>
        </div>
        <div>
            <?php
                echo
                ButtonHelper::refresh(); ?>
            <?= ButtonHelper::collapse()
            ?>
        </div>
    </div>

    <div class='card-body mb-2 collapse btn-group-sm gap-2' id='collapseButtons'>
        <?= ButtonHelper::create('Добавить том')
        ?>
        <hr>

        <table class='table table-sm'>
            <tbody>
            
            <?php
                $i = 1;
                foreach ($books as $model) { ?>


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
    </div>

    <div class='card-body'>
        <div class='d-flex justify-content-between'>
            <div class="small">всего слов: <?= $wordCount ?></div>
            <div>
                <?= ($type) ? Html::a(
                        'всего слов',
                        [
                            'view-all',
                            'type' => false,
                        ],
                        [
                            'class' => 'btn btn-sm btn-success mb-2',
                        ],
                    ) .
                              Html::a(
                                  'лексемы',
                                  [
                                      'view-all',
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
                        'view-all',
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
                        'view-all',
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
                echo '#' . ($n) . '.  ' . $row . "<br>\r\n";
            }
        ?>


    </div>
</div>
