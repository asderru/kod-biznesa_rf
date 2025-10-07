<?php
    
    namespace frontend\urls;
    
    use core\edit\entities\Library\Author;
    use core\helpers\traits\CacheableTrait;
    use core\read\readers\Library\AuthorReader;
    use core\tools\params\Prefix;
    use Exception;
    use InvalidArgumentException;
    use Yii;
    use yii\caching\Cache;
    use yii\db\ActiveRecord;
    use yii\web\UrlManager;
    use yii\web\UrlNormalizerRedirectException;
    use yii\web\UrlRuleInterface;
    
    class AuthorRule extends BaseObject implements UrlRuleInterface
    {
        use RulesTrait;
        use CacheableTrait;
        
        protected const int     TEXT_TYPE      = Author::TEXT_TYPE;
        private const string    ROUTE_INDEX    = 'author/index';
        private const string    ROUTE_VIEW     = 'author/view';
        
        public function __construct(
            private readonly AuthorReader $reader,
            private readonly Cache        $cache,
            array                         $config = [],
        )
        {
            parent::__construct($config);
        }
        
        /**
         * Публичный метод для генерации пути модели
         *
         * @param int $siteId
         * @return string Сгенерированный путь
         * @throws Exception если не удалось сгенерировать валидный путь
         */
        public static function generateModelPath(int $siteId): string
        {
            // Получаем родителей и их slug'и
            $root = Author::getRoot($siteId);
            $path = self::generateParentSlugsPath($root);
            
            if (!self::validateGeneratedPath($path)) {
                throw new InvalidArgumentException('Generated path is invalid: ' . $path);
            }
            
            return $path;
        }
        
        /**
         * Генерация пути из слагов родителей
         *
         * @param Author|ActiveRecord $root
         * @return string Сгенерированный путь
         * @throws Exception
         */
        public static function generateParentSlugsPath(Author|ActiveRecord $root): string
        {
            try {
                
                $path = $root->slug;
                
                // Нормализуем URL
                return self::normalizeUrl($path);
                
            }
            catch (Exception $e) {
                Yii::error([
                    'message' => 'Error generating path',
                    'error'   => $e->getMessage(),
                    'modelId' => $model['id'] ?? 'unknown',
                ], __METHOD__);
                throw $e;
            }
        }
        
        /**
         * Валидация сгенерированного пути
         *
         * @param string $path Сгенерированный путь
         * @return bool Результат валидации
         */
        private static function validateGeneratedPath(string $path): bool
        {
            // Проверяем базовые требования к пути
            if (empty($path) || !str_starts_with($path, '/') || !str_ends_with($path, '/')) {
                return false;
            }
            
            // Разбиваем путь на части
            $parts = array_filter(explode('/', trim($path, '/')));
            
            // Проверяем что путь содержит хотя бы два компонента:
            // - корневой раздел (который является префиксом)
            // - текущий раздел
            return count($parts) >= 2;
        }
        
        /**
         * @throws UrlNormalizerRedirectException
         */
        public function parseRequest($manager, $request): false|array
        {
            try {
                $pathInfo = $this->sanitizePath($request->getPathInfo());
                $prefix = Prefix::author();
                
                if (!$this->isValidModelRoute($pathInfo, $prefix)) {
                    return false;
                }
                
                if ($pathInfo === $prefix) {
                    return [
                        self::ROUTE_INDEX, [],
                    ];
                }
                
                $path = $this->extractPathSlug($pathInfo, $prefix);
                if (empty($path)) {
                    return false;
                }
                
                // Получаем модель с использованием кэширования
                $model = $this->getCachedModel($path);
                if (!$model || empty($model['id'])) {
                    return false;
                }
                
                // Нормализуем текущий путь и путь из модели для корректного сравнения
                $normalizedCurrentPath = $this->normalizeUrl($pathInfo);
                $normalizedModelPath   = $this->normalizeUrl($model['link']);
                
                if ($normalizedCurrentPath !== $normalizedModelPath) {
                    throw new UrlNormalizerRedirectException(
                        [self::ROUTE_VIEW, 'id' => $model['id']],
                        301,
                    );
                }
                
                return [self::ROUTE_VIEW, ['id' => $model['id']]];
                
            }
            catch (UrlNormalizerRedirectException $e) {
                throw $e;
            }
            catch (Exception $e) {
                Yii::error('Error parsing URL: ' . $e->getMessage(), __METHOD__);
                return false;
            }
        }
        
        /**
         * Создает URL для раздела на основе переданных параметров
         *
         * @param UrlManager $manager URL менеджер
         * @param string     $route   Маршрут
         * @param array      $params  Параметры URL
         * @return false|string Сгенерированный URL или false в случае ошибки
         */
        public function createUrl($manager, $route, $params): false|string
        {
            // Проверяем, соответствует ли маршрут нашему обработчику
            if ($route !== self::ROUTE_VIEW) {
                return false;
            }
            
            // Проверяем наличие обязательного параметра id
            if (empty($params['id'])) {
                return false;
            }
            
            try {
                // Получаем модель из кэша по ID
                $model = $this->getCachedModelById((int)$params['id']);
                
                if (!$model || empty($model['link'])) {
                    return false;
                }
                
                // Формируем URL с учетом параметров
                $url = $model['link'];
                
                // Удаляем использованные параметры
                unset($params['id']);
                
                // Добавляем оставшиеся параметры как GET-параметры
                if (!empty($params)) {
                    $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($params);
                }
                
                return $url;
                
            }
            catch (Exception $e) {
                Yii::error('Error creating URL: ' . $e->getMessage(), __METHOD__);
                return false;
            }
        }
    }
