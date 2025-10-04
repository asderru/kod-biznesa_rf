<?php
    
    use core\edit\search\Library\ChapterSearch;
    use core\helpers\ButtonHelper;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $searchModel ChapterSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $models core\edit\entities\Library\Book[] */
    /* @var $actionId string */
    /* @var $siteId int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $textType int */
    
    const LAYOUT_ID = '#library_calculate_index';
    
    $this->params['breadcrumbs'][] = ['label' => $label, 'url' => ['/library/book/index']];
    
    $this->title = 'Подсчет слов';
    
    $buttons = [];
    
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
<div class='card'>
    
    <?= $this->render(
        '/layouts/tops/_viewHeaderIndex',
        [
            'textType' => $textType,
            'title'    => $label,
            'buttons'  => $buttons,
        ],
    )
    ?>

    <div class="card-body">
        <table class='table table-sm'>
            <tbody>

            <tr>
                <td>
                </td>
                <td>
                    Все книги
                </td>
                <td><?= Html::a(
                        'подсчитать все слова',
                        [
                            'view-all',
                        ],
                        [
                            'class' => 'btn btn-sm btn-success mb-2',
                        ],
                    )
                    ?>
                </td>
                <td>
                    <?= Html::a(
                        'подсчитать слова',
                        [
                            'view-all',
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
                            'view-all',
                            'type' => 'lex',
                        ],
                        [
                            'class' => 'btn btn-sm btn-info mb-2',
                        ],
                    )
                    ?>
                </td>
            </tr>
            <?php
                $i = 1;
                foreach ($models as $model) { ?>

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
                                    'class' => 'btn btn-sm btn-info mb-2',
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
</div>
