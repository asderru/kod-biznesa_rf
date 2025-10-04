<?php

    use core\edit\entities\User\User;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    use yii\web\View;
    
    /** @var View $this */
    /* @var $superadmin User */
    /* @var $sites array */
    /* @var $groupedContent array */
    /* @var $team array */
    /* @var $reviews array */
    /* @var $isServer bool */
    /* @var $textType int */
    /* @var $label string */
    /* @var $prefix string */
    /* @var $actionId int */
    
    const LAYOUT_ID = '#backend_site_index_';
    
    $this->title = 'Главная панель';
?>

<div class='alert-area'>
    <?= $this->render(
        '@app/views/layouts/tops/_messages',
    ) ?>
</div>
<hr>
<div class='row'>
    <div class='col-xl-3 col-lg-4 col-md-6 col-sm-12 py-1'>
        <div class="card">
            <div class='card-header bg-info-subtle'>
                Блок с баннером
            </div>
            <div class="card-body">
                <?= Html::img(Url::to('@static', true).'/cache/14/110-kod-biznesa-rf_col-3.webp', ['alt' => 'logo',
                'class' =>
                    'img-fluid']) ?>
                <ul>
                    <li class="py-2">
                        <?= Html::a(
                            'Просмотр',
                            [
                                '/admin/information/view',
                                'id' => 110,
                            ],
                            [
                                'class' => 'btn btn-outline-primary',
                            ],
                        )
                        ?>
                    </li>
                    <li class='py-2'>
                        
                        <?= Html::a(
                            'Правка',
                            [
                                '/admin/information/update',
                                'id' => 110,
                            ],
                            [
                                'class' => 'btn btn-outline-success',
                            ],
                        )
                        ?></li>
                    <li class='py-2'>
                        
                        
                        <?= Html::a(
                            'База данных',
                            [
                                '/admin/information/base',
                                'id' => 110,
                            ],
                            [
                                'class' => 'btn btn-outline-secondary',
                            ],
                        )
                        ?></li>
                </ul>

            </div>
        </div>
    </div>
    <div class='col-xl-3 col-lg-4 col-md-6 col-sm-12 py-1'>
        <div class="card">
            <div class='card-header bg-info-subtle'>
                Первый блок
            </div>
            <div class="card-body">
                <ul>
                    <li class="py-2">
                        <?= Html::a(
                            'Просмотр',
                            [
                                '/content/page/view',
                                'id' => 2,
                            ],
                            [
                                'class' => 'btn btn-outline-primary',
                            ],
                        )
                        ?>
                    </li>
                    <li class='py-2'>
                        
                        <?= Html::a(
                            'Правка',
                            [
                                '/content/page/update',
                                'id' => 2,
                            ],
                            [
                                'class' => 'btn btn-outline-success',
                            ],
                        )
                        ?></li>
                    <li class='py-2'>
                        
                        
                        <?= Html::a(
                            'База данных',
                            [
                                '/content/page/base',
                                'id' => 2,
                            ],
                            [
                                'class' => 'btn btn-outline-secondary',
                            ],
                        )
                        ?></li>
                </ul>

            </div>
        </div>
    </div>
    
    <div class='col-xl-3 col-lg-4 col-md-6 col-sm-12 py-1'>
        <div class="card">
            <div class='card-header bg-info-subtle'>
                Второй блок
            </div>
            <div class="card-body">
                <ul>
                    <li class="py-2">
                        <?= Html::a(
                            'Просмотр',
                            [
                                '/content/page/view',
                                'id' => 3,
                            ],
                            [
                                'class' => 'btn btn-outline-primary',
                            ],
                        )
                        ?>
                    </li>
                    <li class='py-2'>
                        
                        <?= Html::a(
                            'Правка',
                            [
                                '/content/page/update',
                                'id' => 3,
                            ],
                            [
                                'class' => 'btn btn-outline-success',
                            ],
                        )
                        ?></li>
                    <li class='py-2'>
                        
                        
                        <?= Html::a(
                            'База данных',
                            [
                                '/content/page/base',
                                'id' => 3,
                            ],
                            [
                                'class' => 'btn btn-outline-secondary',
                            ],
                        )
                        ?></li>
                </ul>

            </div>
        </div>
    </div>
    
    <div class='col-xl-3 col-lg-4 col-md-6 col-sm-12 py-1'>
        <div class="card">
            <div class='card-header bg-info-subtle'>
                Блок Мероприятия
            </div>
            <div class="card-body">
                <ul>
                    <li class="py-2">
                        <?= Html::a(
                            'Просмотр',
                            [
                                '/content/page/view',
                                'id' => 4,
                            ],
                            [
                                'class' => 'btn btn-outline-primary',
                            ],
                        )
                        ?>
                    </li>
                    <li class='py-2'>
                        
                        <?= Html::a(
                            'Правка',
                            [
                                '/content/page/update',
                                'id' => 3,
                            ],
                            [
                                'class' => 'btn btn-outline-success',
                            ],
                        )
                        ?></li>
                    <li class='py-2'>
                        
                        
                        <?= Html::a(
                            'База данных',
                            [
                                '/content/page/base',
                                'id' => 3,
                            ],
                            [
                                'class' => 'btn btn-outline-secondary',
                            ],
                        )
                        ?></li>
                </ul>

            </div>
        </div>
    </div>
    
    
    <div class='col-xl-3 col-lg-4 col-md-6 col-sm-12 py-1'>
        <div class="card">
            <div class='card-header bg-info-subtle'>
                Блок Команда
            </div>
            <div class="card-body">
                <ul>
                    <li class="py-2">
                        <?= Html::a(
                            'Страница команды',
                            [
                                '/library/author/index'
                            ],
                            [
                                'class' => 'btn btn-outline-primary',
                            ],
                        )
                        ?>
                    </li>
                    <?php foreach ($team as $member):
                        if ($member['status'] !== Constant::STATUS_ROOT): ?>
                    <li class='py-2'>
                        
                        <?= Html::a(
                            $member['name'],
                            [
                                '/library/author/view',
                                'id' => $member['id'],
                            ],
                            [
                                'class' => 'btn btn-outline-secondary',
                            ],
                        )
                        ?></li>
                    <?php
                    endif;
                    endforeach; ?>
                    
                </ul>

            </div>
        </div>
    </div>
    
    
    <div class='col-xl-3 col-lg-4 col-md-6 col-sm-12 py-1'>
        <div class="card">
            <div class='card-header bg-info-subtle'>
                Отзывы
            </div>
            <div class="card-body">
                <ul>
                    <li class="py-2">
                        <?= Html::a(
                            'Все отзывы',
                            [
                                '/content/review/index',
                            ],
                            [
                                'class' => 'btn btn-outline-primary',
                            ],
                        )
                        ?>
                    </li>
                    <?php foreach ($reviews as $review):
                        if ($review['status'] !== Constant::STATUS_ROOT): ?>
                    <li class='py-2'>
                        
                        <?= Html::a(
                            $review['name'],
                            [
                                '/content/review/view',
                                'id' => $review['id'],
                            ],
                            [
                                'class' => 'btn btn-outline-secondary',
                            ],
                        )
                        ?></li>
                    <?php
                    endif;
                    endforeach; ?>

                    <li class='py-2'>
                        <?= Html::a(
                            'Профили',
                            [
                                '/user/person/index',
                            ],
                            [
                                'class' => 'btn btn-outline-dark',
                            ],
                        )
                        ?>
                    </li>
                </ul>

            </div>
        </div>
    </div>
    
    <div class='col-xl-3 col-lg-4 col-md-6 col-sm-12 py-1'>
        <div class="card">
            <div class='card-header bg-info-subtle'>
                Контакты
            </div>
            <div class="card-body">
                <ul>
                    <li class="py-2">
                        <?= Html::a(
                            'Просмотр',
                            [
                                '/admin/contact/view',
                                'id' => 110,
                            ],
                            [
                                'class' => 'btn btn-outline-primary',
                            ],
                        )
                        ?>
                    </li>
                    <li class='py-2'>
                        
                        
                        <?= Html::a(
                            'Правка',
                            [
                                '/admin/contact/update',
                                'id' => 110,
                            ],
                            [
                                'class' => 'btn btn-outline-success',
                            ],
                        )
                        ?></li>
                    <li class='py-2'>
                        
                        
                        <?= Html::a(
                            'База данных',
                            [
                                '/admin/contact/base',
                                'id' => 110,
                            ],
                            [
                                'class' => 'btn btn-outline-secondary',
                            ],
                        )
                        ?></li>
                </ul>

            </div>
        </div>
    </div>
</div>
