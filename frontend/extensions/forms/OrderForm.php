<?php

	namespace frontend\extensions\forms;

	use JetBrains\PhpStorm\ArrayShape;
    use yii\base\Model;
    
    class OrderForm extends Model
	{
        public null|string $name  = null;
        public null|string $email = null;
        public null|string $footnote   = null;
        public null|int    $footnoteId = null;
        public null|string $notes = null;

		public function rules(): array
		{
			return [
				[
					[
                        'name', 'email', 'footnote', 'footnoteId',
					],
					'required', 'message' =>
						'Укажите Ваше имя и e-mail',
				],
				[
                    'footnoteId', 'integer',
				],
				[
					'email', 'email',
				],
				[
					[
                        'footnote', 'notes',
					],
					'string',
					'max' => 125,
				],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		#[ArrayShape([
				'name'    => "string",
				'email'   => "string",
                'footnote' => "string",
				'body'    => "string",
			]
		)] public function attributeLabels(): array
		{
			return [
				'name'    => 'Имя',
				'email'   => 'E-mail',
                'footnote' => 'Товар/услуга',
				'body'    => 'Заметки',
			];
		}

	}
