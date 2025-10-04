<?php

    use core\helpers\FaviconHelper;
    use core\helpers\IconHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;


    /* @var $faqs array */
    /* @var $footnotes array */
    
    $faqs      = $faqs ?? [];
    $footnotes = $footnotes ?? [];


?>

<div class='card-header bg-info-subtle'>
    <span class='fw-bold'>Метаданные</span>
</div>
<div class='card-body'>
    <?php
        if ($faqs): ?>
            <dl class='row'>
                <dt class='col-sm-4'>Комментарии</dt>
                <dd class='col-sm-8'>
                    <?php
                        foreach ($faqs as $faq): ?>
                            <?= FaviconHelper::statusSmall($faq['status']) ?>&nbsp;
                            <?= Html::a(
                                $faq['name'],
                                [
                                    '/seo/faq/view',
                                    'id' => $faq['id'],
                                ],
                                [
                                    'target' => '_blank',
                                ],
                            );
                            ?>
                            <button type='button'
                                    class='btn btn-sm btn-light'
                                    data-bs-toggle='modal'
                                    data-bs-target='#faqModal-<?= $faq['id'] ?>'>
                                <?= IconHelper::biChatLeftText('смотреть') ?>
                            </button>
                            <?= FaviconHelper::updateSmall(Constant::FAQ_TYPE, $faq['id'], true) ?>
                        <?php
                        endforeach; ?>
                </dd>
            </dl>
        <?php
        else: ?>
            Комментарии отсутствуют
        <?php
        endif; ?>
    <hr class='my-2'>
    <?php
        if ($footnotes): ?>
            <strong>Примечания:</strong>
            <ul>
                <?php
                    foreach ($footnotes as $footnote):?>
                        <li>
                            <?= FaviconHelper::statusSmall($footnote['status']) ?>
                            &nbsp;<?= Html::a(
                                $footnote['name'],
                                [
                                    '/seo/footnote/view',
                                    'id' => $footnote['id'],
                                ],
                                [
                                    'target' => '_blank',
                                ],
                            ) ?>
                            <button type='button'
                                    class='btn btn-sm btn-light'
                                    data-bs-toggle='modal'
                                    data-bs-target='#footnoteModal-<?= $footnote['id'] ?>'>
                                <?= IconHelper::biChatLeftText('смотреть') ?>
                            </button>
                            <?= FaviconHelper::updateSmall(Constant::FOOTNOTE_TYPE, $footnote['id'], true) ?>
                        </li>
                    <?php
                    endforeach; ?>
            </ul>
        
        <?php
        else: ?>
            Примечания отсутствуют
        <?php
        endif; ?>
    <hr class='my-2'>
</div>
