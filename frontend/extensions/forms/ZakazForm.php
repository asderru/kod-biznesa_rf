<?php

	namespace frontend\extensions\forms;

	use core\tools\validators\PhoneValidator;
	use JetBrains\PhpStorm\ArrayShape;
	use Yii;
	use yii\base\Model;

	class ZakazForm extends Model
	{
        public null|string $name    = null;
        public null|string $email   = null;
        public null|string $phone   = null;
        public null|string $subject = null;
        public null|string $code    = null;
        public null|string $body    = null;

		public function rules(): array
		{
			return [
				[
					[
						'name', 'email',
					],
					'required',
					'message' =>
						'Пункт обязателен к заполнению',
				],
				[
					'email', 'email',
				],
				[
					'phone', PhoneValidator::class,
				],
				[
					'subject',
					'string',
					'max'     => 63,
					'message' =>
						'Поле "{attribute}" не может содержать более 60 знаков',
				],
				[
					'code',
					'string',
					'max'     => 127,
					'message' =>
						'Поле "{attribute}" не может содержать более 250 знаков',
				],
				[
					'body',
					'string',
					'max'     => 253,
					'message' =>
						'Поле "{attribute}" не может содержать более 250 знаков',
				],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		#[ArrayShape([
			'name'    => "string",
			'email'   => "string",
			'phone'   => "string",
			'subject' => "string",
			'code'    => "string",
			'body'    => "string",
		])]
		public function attributeLabels(): array
		{
			return [
				'name'    => 'Имя',
				'email'   => 'E-mail',
				'phone'   => 'Телефон',
				'subject' => 'Название',
				'code'    => 'Код',
				'body'    => 'Заметки',
			];
		}

		public function sendEmail(): bool
		{
			return Yii::$app->mailer
				->compose(
					'zakaz',
					[
						'order' => $this,
					]
				)
				->setTo($this->zakazMail)
				->send()
			;
		}
	}
