<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\entities\Tech\Cache;
    use yii\caching\FileCache;
    use yii\web\Controller;
    use Yii;
    
    class CacheController extends Controller
    {
        private const int    TEXT_TYPE    = Cache::TEXT_TYPE;
        private const string MODEL_LABEL  = 'Кэширование';
        private const string MODEL_PREFIX = 'cache';
        private const string ACTION_INDEX = 'Admin_CacheController_';
        
        /**
         * @action Admin_CacheController_actionIndex
         */
        public function actionIndex(): string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            $cache    = Yii::$app->cache;
            $keys     = [];
            
            // Получаем все ключи кэша
            if (method_exists($cache, 'getKeys')) {
                $keys = $cache->getKeys();
            }
            elseif ($cache instanceof FileCache) {
                $keys = $this->getFileCacheKeys($cache);
            }
            
            return $this->render(
                'index',
                [
                    'keys'     => $keys,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @action Admin_CacheController_actionView
         */
        public function actionView($key): string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            $value    = Yii::$app->cache->get($key);
            if ($value === false) {
                return $this->render('view', [
                    'key'      => $key,
                    'value'    => null,
                    'message'  => "Cache key '$key' not found or expired.",
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ]);
            }
            else {
                return $this->render('view', [
                    'key'      => $key,
                    'value'    => $value,
                    'message'  => null,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ]);
            }
        }
        
        private function getFileCacheKeys($cache): array
        {
            $keys     = [];
            $cacheDir = Yii::getAlias($cache->cachePath);
            $files    = glob($cacheDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    $keys[] = basename($file);
                }
            }
            return $keys;
        }
    }
