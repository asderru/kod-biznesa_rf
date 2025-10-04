<?php
    
    namespace frontend\extensions\services;
    
    
    use core\tools\dispatchers\DeferredEventDispatcher;
    use Yii;
    use yii\db\Exception;
    
    class TransactionManager
    {
        private DeferredEventDispatcher $dispatcher;
        
        public function __construct(DeferredEventDispatcher $dispatcher)
        {
            $this->dispatcher = $dispatcher;
        }
        
        /**
         * @throws Exception
         */
        public function wrap(callable $function): void
        {
            $transaction = Yii::$app->db->beginTransaction();
            if (!$transaction) {
                return;
            }
            try {
                $this->dispatcher->defer();
                $function();
                $transaction->commit();
                $this->dispatcher->release();
            }
            catch (\Exception $e) {
                $transaction->rollBack();
                $this->dispatcher->clean();
                throw $e;
            }
        }
    }
