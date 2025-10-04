<?php
    
    namespace backend\helpers;
    
    use core\edit\entities\Shop\Product\Property;
    use core\tools\Constant;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use yii\helpers\BaseHtml;
    use yii\bootstrap5\Html;
    use yii\helpers\ArrayHelper;
    
    class PropertyHelper extends BaseHtml
    {
        
        /**
         * @throws Exception
         */
        public static function periodName(int $period): string
        {
            return ArrayHelper::getValue(self::periodList(), $period);
        }
        
        #[ArrayShape([
            Constant::PERIOD_NONE  => "string",
            Constant::PERIOD_ONCE  => "string",
            Constant::PERIOD_HOUR  => "string",
            Constant::PERIOD_DAY   => "string",
            Constant::PERIOD_WEEK  => "string",
            Constant::PERIOD_MONTH => "string",
            Constant::PERIOD_YEAR  => 'string',
        ])]
        public static function periodList(): array
        {
            return [
                Constant::PERIOD_NONE  => 'Оплата по договоренности',
                Constant::PERIOD_ONCE  => 'Разово (оплата в момент покупки)',
                Constant::PERIOD_HOUR  => 'Ежечасно',
                Constant::PERIOD_DAY   => 'Ежедневно',
                Constant::PERIOD_WEEK  => 'Еженедельно',
                Constant::PERIOD_MONTH => 'Ежемесячно',
                Constant::PERIOD_YEAR  => 'Раз в год',
            ];
        }
        
        /**
         * @throws Exception
         */
        public static function periodLabel(Property $model): string
        {
            $period = $model->period;
            $class  = match ($model->period) {
                Constant::PERIOD_NONE                         => 'btn btn-sm btn-dark',
                Constant::PERIOD_ONCE                         => 'btn btn-sm btn-success',
                Constant::PERIOD_HOUR                         => 'btn btn-sm btn-info text-dark',
                Constant::PERIOD_DAY                          => 'btn btn-sm btn-warning text-dark',
                Constant::PERIOD_WEEK                         => 'btn btn-sm btn-primary',
                Constant::PERIOD_MONTH, Constant::PERIOD_YEAR => 'btn btn-sm btn-danger',
                default                                       => 'btn btn-sm btn-secondary',
            };
            
            return Html::tag(
                'span',
                ArrayHelper::getValue(self::periodList(), $period), [
                    'class' => $class,
                ],
            );
        }
        
        /**
         * @throws Exception
         */
        public static function scoreName(int $score): string
        {
            return ArrayHelper::getValue(self::scoreList(), $score);
        }
        
        #[ArrayShape([
            Constant::SCORE_UNCOUNT    => "string",
            Constant::SCORE_DISCOUNT   => "string",
            Constant::SCORE_COUNT      => "string",
            Constant::SCORE_ALLCOUNT   => "string",
            Constant::SCORE_COUNTSTOCK => "string",
        ])]
        public static function scoreList(): array
        {
            return [
                Constant::SCORE_UNCOUNT    => '1. Учет не ведется',
                Constant::SCORE_DISCOUNT   => '2. Учитывается оптовая скидка',
                Constant::SCORE_COUNT      => '3. Учитывается максимальное число в заказе',
                Constant::SCORE_ALLCOUNT   => '4. Учитывается максимальное число в заказе и сравнивается с наличием на складе',
                Constant::SCORE_COUNTSTOCK => '5. Учет ведется автоматически',
            ];
        }
        
        /**
         * @throws Exception
         */
        public static function scoreLabel(?Property $model): ?string
        {
            if (!$model) {
                return null;
            }
            
            $score = $model->score;
            $class = match ($model->score) {
                Constant::PERIOD_NONE                         => 'btn btn-sm btn-dark',
                Constant::PERIOD_ONCE                         => 'btn btn-sm btn-success',
                Constant::PERIOD_HOUR                         => 'btn btn-sm btn-info text-dark',
                Constant::PERIOD_DAY                          => 'btn btn-sm btn-warning text-dark',
                Constant::PERIOD_WEEK                         => 'btn btn-sm btn-primary',
                Constant::PERIOD_MONTH, Constant::PERIOD_YEAR => 'btn btn-sm btn-danger',
                default                                       => 'btn btn-sm btn-secondary',
            };
            
            return Html::tag(
                'span',
                ArrayHelper::getValue(self::scoreList(), $score), [
                    'class' => $class,
                ],
            );
        }
    }
