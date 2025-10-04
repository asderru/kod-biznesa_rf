<?php
    
    use core\helpers\AppHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    /* @var $context string */
    
    if (!AppHelper::showLibrary()) {
        return null;
    }

?>


<li class='sidebar-item'>
    <a data-bs-target='#library-sidebar' data-bs-toggle='collapse' class='sidebar-link collapsed'>
        <i class='fa-solid fa-landmark-dome'></i>
        <span class='align-middle'>Библиотека</span>
    </a>
    <ul id='library-sidebar' class='sidebar-dropdown list-unstyled collapse ' data-bs-parent='#sidebar'>

        <li class="sidebar-item<?= ($context === 'library/book') ? ' active' : null ?>">
            <?= Html::a(
                'Книги / ' . Constant::BOOK_LABEL,
                '/library/book',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'library/chapter') ? ' active' : null ?>">
            <?= Html::a(
                'Главы / ' . Constant::CHAPTER_LABEL,
                '/library/chapter',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <li class="sidebar-item<?= ($context === 'library/author') ? ' active' : null ?>">
            <?= Html::a(
                'Авторы / ' . Constant::AUTHOR_LABEL,
                '/library/author',
                [
                    'class' => 'sidebar-link',
                ],
            )
            ?>
        </li>

        <!--###### СhatGPT ##################################-->

        <!-- Nav item -->
        <li class="sidebar-item">
            <?=
                Html::a(
                    Html::img(
                        '/img/favicon/chatgpt16.png',
                    ) .
                    '&nbsp;ChatGPT',
                    'https://chat.openai.com/',
                    [
                        'class'  => 'sidebar-link',
                        'target' => '_blank',
                    ],
                )
            ?>
        </li>

    </ul>
</li>
