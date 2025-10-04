<?php
    
    namespace frontend\extensions\helpers;
    
    use core\edit\entities\Admin\Contact;
    use core\edit\entities\Admin\Information;
    use core\helpers\ParametrHelper;
    use core\tools\Constant;
    use Exception;
    use Yii;
    use yii\caching\TagDependency;
    
    class ContactHelper
    {
        private const int     TEXT_TYPE = Constant::EDIT_INFO_TYPE;
        private const string  CACHE_TAG = 'cache_tag_' . Parametr::siteId() . '_' . self::TEXT_TYPE . '_';
        
        /**
         */
        public static function getSite(): Information
        {
            $cacheKey = self::generateCacheKey(
                __METHOD__,
            );
            
            // Используем метод getOrSet для кэширования
            $result = Yii::$app->cache->getOrSet($cacheKey, function () {
                return ParametrHelper::getSite();
            },
                Constant::DEFAULT_CACHE_DURATION,
                new TagDependency([
                    'tags' =>
                        [
                            self::CACHE_TAG . Parametr::siteId(),
                        ],
                ]),
            );
            
            if ($result === null) {
                Yii::$app->cache->delete($cacheKey);
                return Yii::$app->params['siteName'];
            }
            
            return $result;
        }
        
        /**
         */
        public static function getContact(): null|Contact
        {
            $cacheKey = self::generateCacheKey(
                __METHOD__,
            );
            
            // Используем метод getOrSet для кэширования
            $result = Yii::$app->cache->getOrSet($cacheKey, function () {
                return ParametrHelper::getContact();
                return $model->contact;
            },
                Constant::DEFAULT_CACHE_DURATION,
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
         * @throws Exception
         */
        public static function getName(): string
        {
            return self::getSite()?->name ?? Yii::$app->params['siteName'];
        }
        
        public static function getAddress(): ?string
        {
            return self::getContact()?->address;
            
        }
        
        public static function getLgt(): ?string
        {
            return self::getContact()?->lgt;
        }
        
        public static function getLtd(): ?string
        {
            return self::getContact()?->ltd;
        }
        
        public static function getPhone(): string
        {
            
            $phone = self::getContact()->phone;
            
            // Удаление всех нецифровых символов из номера телефона
            $cleanedPhone = preg_replace('/\D/', '', $phone);
            
            // Форматирование номера телефона
            $formattedPhone = '+7 (' . substr($cleanedPhone, 1, 3) . ') ' . substr($cleanedPhone, 4, 3) . '-' . substr($cleanedPhone, 7, 4);
            
            // Формирование ссылки с новым форматированным номером
            return '<a href="tel:' . $cleanedPhone . '">' . $formattedPhone . '</a>';
        }
        
        
        /**
         * @throws Exception
         */
        public static function getEmail(): string
        {
            $email = self::getContact()->email;
            
            // Формирование ссылки с новым форматированным email
            return '<a href="mailto:' . $email . '">' . $email . '</a>';
        }
        
        
        private static function generateCacheKey(
            string $functionName,
        ): string
        {
            $siteId = Parametr::siteId();
            
            $key = $siteId . '_' . self::TEXT_TYPE . '_' . $siteId . '_' . $functionName;
            
            // Добавляем ключ в список связанных ключей
            $listKey       = 'cache_keys_' . $siteId . '_' . self::TEXT_TYPE . '_' . $siteId;
            $relatedKeys   = Yii::$app->cache->get($listKey) ?: [];
            $relatedKeys[] = $key;
            Yii::$app->cache->set($listKey, $relatedKeys);
            
            return $key;
        }
        
        
    }
