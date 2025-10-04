<?php
    
    namespace frontend\extensions\services\sms;
    
    interface SmsSender
    {
        public function send(int $number, string $text): void;
    }
