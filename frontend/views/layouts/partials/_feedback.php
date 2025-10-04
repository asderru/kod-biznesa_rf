<?php
    
    use frontend\extensions\helpers\ContactHelper;

?>

<p>Если у вас возникли вопросы по продвижению имеющегося сайта или по созданию нового
    с последующим сео-продвижением, вас готовы выслушать по телефону
    <strong>
        <?php
            
            try {
                echo
                ContactHelper::getPhone();
            }
            catch (Exception $e) {
            }
        ?></strong>
    с 8-00 до 22-00 по московскому времени.
</p>

<p>Так же в любое время дня и ночи вы можете оставить заявку с указанием контактных
    данных, по которым мы свяжемся с вами и ответим на все возникшие вопросы.
<div class='text-center p-4'>
    <button
            type='button'
            class='btn btn-sm btn-outline-secondary'
            aria-hidden='false'
            data-bs-toggle='modal'
            data-bs-target='#feedbackModal'
            data-bs-feedback='Shopnseo. MainPage'
    >Задать вопрос!
    </button>
</div>
