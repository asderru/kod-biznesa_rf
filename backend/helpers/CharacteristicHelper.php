<?php
    
    namespace backend\helpers;
    
    use core\edit\entities\Shop\Product\Characteristic;
    use yii\helpers\ArrayHelper;
    
    class CharacteristicHelper
    {
        public static function typeList(): array
        {
            return [
                Characteristic::TYPE_STRING  => 'Строка',
                Characteristic::TYPE_INTEGER => 'Число',
                Characteristic::TYPE_FLOAT   => 'Число с запятой',
            ];
        }
        
        /**
         * @throws \Exception
         */
        public static function typeName($type): string
        {
            return ArrayHelper::getValue(self::typeList(), $type);
        }
        
    }
