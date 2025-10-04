<?php
    
    namespace frontend\extensions\forms;
    
    use Yii;
    use yii\base\Model;
    
    class ContactForm extends Model
    {
        public ?string $name    = null;
        public ?string $email   = null;
        public ?string $phone   = null;
        public ?string $body    = null;
        public ?string $subject = null;  // Убедитесь, что это свойство объявлено
        
        /**
         * @return array
         */
        public function rules(): array
        {
            return [
                [['name'], 'required', 'message' => 'Необходимо указать имя!'],
                ['email', 'required', 'message' => 'Необходимо указать email!'],
                ['body', 'required', 'message' => 'Необходимо написать сообщение!'],
                [['subject'], 'safe'],
                [['phone'], 'string'],
                ['email', 'email', 'message' => 'Укажите корректный email адрес!'],
            ];
        }
        
        public function sendEmail(string $email): bool
        {
            // Установим значение по умолчанию для subject, если оно не задано
            $subject = $this->subject ?? 'Сообщение с сайта';  // Добавляем значение по умолчанию
            
            return Yii::$app->mailer->compose()
                                    ->setTo($email)
                                    ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                                    ->setReplyTo([$this->email => $this->name])
                                    ->setSubject($subject)  // Используем переменную с проверкой
                                    ->setTextBody($this->body)
                                    ->send()
            ;
        }
    }
