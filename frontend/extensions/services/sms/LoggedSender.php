<?php
    
    namespace frontend\extensions\services\sms;
    
    use yii\log\Logger;
    
    class LoggedSender implements SmsSender
    {
        private SmsSender $next;
        private Logger    $logger;
        
        public function __construct(SmsSender $next, Logger $logger)
        {
            $this->next   = $next;
            $this->logger = $logger;
        }
        
        public function send(int $number, string $text): void
        {
            $this->next->send($number, $text);
            $this->logger->log('Message to ' . $number . ': ' . $text, Logger::LEVEL_INFO);
        }
    }
