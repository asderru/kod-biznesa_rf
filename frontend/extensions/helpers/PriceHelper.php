<?php
    
    namespace frontend\extensions\helpers;
    
    use core\edit\entities\Content\Content;
    use core\edit\entities\Shop\Product\Property;
    use core\tools\Constant;
    use NumberFormatter;
    use Yii;
    use yii\base\InvalidConfigException;
    use yii\caching\TagDependency;
    
    class PriceHelper
    {
        
        private const int     TEXT_TYPE = Constant::EDIT_INFO_TYPE;
        private const string  CACHE_TAG = 'cache_tag_' . Parametr::siteId() . '_' . self::TEXT_TYPE . '_';
        
        public static function format(int $price): string
        {
            return number_format($price, 0, '', '.');
        }
        
        public static function period(?int $period): string|null
        {
            return match ($period) {
                Constant::PERIOD_NONE, Constant::PERIOD_ONCE => null,
                Constant::PERIOD_HOUR                        => 'в час',
                Constant::PERIOD_DAY                         => 'ежедневно',
                Constant::PERIOD_WEEK                        => 'в неделю',
                Constant::PERIOD_MONTH                       => 'в месяц',
                Constant::PERIOD_YEAR                        => 'за год',
            };
        }
        
        public function price(Content $model): string|null
        {
            $cacheKey = self::generateCacheKey(
                $model->id,
                __METHOD__,
            );
            
            
            // Используем метод getOrSet для кэширования
            $result = Yii::$app->cache->getOrSet($cacheKey, function ()
            use ($model) {
                
                $property = $model->hasOne(
                    Property::class,
                    [
                        'id' => 'property_id',
                    ],
                )
                                  ->one()
                ;
                
                $price = $property?->price1;
                
                return ($price) ? self::format($price) : null;
            },
                Yii::$app->cache->defaultDuration,
                new TagDependency([
                    'tags' =>
                        [
                            self::CACHE_TAG . Parametr::siteId(),
                        ],
                ]),
            );
            
            if ($result === null) {
                return null;
            }
            
            return $result;
        }
        
        
        /**
         * @throws InvalidConfigException
         */
        public static function setPrice(
            int|null $price,
        ): string|null
        {
            return ($price) ?
                Yii::$app->formatter->asCurrency(
                    $price, '',
                    [
                        NumberFormatter::FRACTION_DIGITS => 0,
                    ],
                ) : null; // 1 234,57 руб.;
        }
        
        private static function generateCacheKey(
            ?int   $id,
            string $functionName,
        ): string
        {
            $siteId = Parametr::siteId();
            
            $key = $siteId . '_' . self::TEXT_TYPE . '_' . $siteId . '_' . $functionName . '_' . $id;
            
            // Добавляем ключ в список связанных ключей
            $listKey       = 'cache_keys_' . $siteId . '_' . self::TEXT_TYPE . '_' . $id;
            $relatedKeys   = Yii::$app->cache->get($listKey) ?: [];
            $relatedKeys[] = $key;
            Yii::$app->cache->set($listKey, $relatedKeys);
            
            return $key;
        }
        
    }
