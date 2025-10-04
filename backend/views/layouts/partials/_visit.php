<?php
    
    /* @var $counts int */

?>

<li>
    <a
            class='btn btn-light btn-icon rounded-circle
							indicator indicator-primary'
            href='#'
            role='button'
            id='dropdownNotification'
            data-bs-toggle='dropdown'
            aria-haspopup='true'
            aria-expanded='false'
    >
        <i class='bi bi-chat-dots-fill'></i>
        <span class="strong text-danger"> <?= $counts ?? 0 ?></span>
    </a>
</li>
