<?php
    
    namespace frontend\extensions\helpers;
    
    use core\edit\entities\Tech\Cache;
    use core\tools\Constant;
    use Yii;
    use yii\caching\DbDependency;
    use yii\caching\FileCache;
    use yii\caching\TagDependency;
    use yii\filters\PageCache;
    use yii\helpers\BaseHtml;
    
    class CacheHelper extends BaseHtml
    {
        public static function index(): ?array
        {
            return (YII_ENV_PROD)
                ?
                [
                    'class'      => PageCache::class,
                    'cache'      => 'fileCache',
                    'only'       => [
                        'index',
                    ],
                    'duration'   => 0,
                    'variations' => [
                        Parametr::siteId(),
                        'all',
                    ],
                    'dependency' => [
                        'class' => TagDependency::class,
                        'tags'  => [
                            'hasUpdates-' . Parametr::siteId() . '-all',
                        ],
                    ],
                ]
                :
                [
                    'class'   => PageCache::class,
                    'enabled' => false,
                ];
        }
        
        public static function modelIndex(int $typeId): ?array
        {
            return (YII_ENV_PROD)
                ?
                [
                    'class'      => PageCache::class,
                    'cache'      => FileCache::class,
                    'only'       => [
                        'index',
                    ],
                    'dependency' => [
                        'class' => DbDependency::class,
                        'sql' => "SELECT MAX(updated_at) FROM "
                                   . Cache::tableName()
                                 . " WHERE site_id =" . Parametr::siteId()
                                 . " AND text_type =" . $typeId,
                    ],
                ]
                :
                [
                    'class' => PageCache::class,
                    'only'  => [
                        'index',
                    ],
                ];
        }
        
        public static function modelView(int $typeId): null|array
        {
            $parentId = Yii::$app->request->get('id') ?? Yii::$app->request->get('slug');
            // PrintHelper::print($parentId);
            if (
                $typeId === Constant::BOOK_TYPE && $parentId !== 2 && $parentId !== 3 && $parentId !== 7
                && $parentId !== 11
            ) {
                return [
                    'class' => PageCache::class,
                    'only'  => ['index'],
                ];
            }
            
            // Если "slug" определен, преобразуйте его в уникальное число
            if ($parentId !== null) {
                $parentId = crc32($parentId);
            }
            else {
                // Если ни "id", ни "slug" не определены, установите $parentId в 0
                $parentId = 0;
            }
            $variations = md5($parentId . $typeId . Parametr::siteId());
            
            return (YII_ENV_PROD)
                ? [
                    'class'      => PageCache::class,
                    'cache'      => FileCache::class,
                    'only'       => ['view'],
                    'variations' => [$variations],
                    'dependency' => [
                        'class' => DbDependency::class,
                        'sql'   => 'SELECT MAX(updated_at) FROM ' . Cache::tableName()
                                   . ' WHERE site_id =' . Parametr::siteId()
                                   . ' AND text_type =' . $typeId
                                   . ' AND parent_id =' . $parentId,
                    ],
                ]
                : [
                    'class'      => PageCache::class,
                    'only'       => ['view'],
                    'variations' => [$variations],
                    'dependency' => [
                        'class' => DbDependency::class,
                        'sql'   => 'SELECT MAX(updated_at) FROM ' . Cache::tableName()
                                   . ' WHERE site_id =' . Parametr::siteId()
                                   . ' AND text_type =' . $typeId
                                   . ' AND parent_id =' . $parentId,
                    ],
                ];
        }
        
        public static function sqlMain(): string
        {
            return
                "SELECT MAX(updated_at) FROM "
                . Cache::tableName()
                . " WHERE site_id =" . Parametr::siteId()
                . " AND text_type =" . Constant::SITE_TYPE;
        }
        
        public static function sqlFaq(null|int $id = null): string
        {
            // Генерация ключа для кэша
            $cacheKey = 'sqlSection_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlFaq($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlFaq(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::FAQ_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::FAQ_TYPE;
        }
        
        public static function sqlFootnote(null|int $id = null): string
        {
            // Генерация ключа для кэша
            $cacheKey = 'sqlSection_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlFootnote($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlFootnote(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::FOOTNOTE_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::FOOTNOTE_TYPE;
        }
        
        public static function sqlPage(null|int $id = null): string
        {
            // Генерация ключа для кэша
            $cacheKey = 'sqlPage_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlPage($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlPage(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::PAGE_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::PAGE_TYPE;
        }
        
        public static function sqlBook(null|int $id = null): string
        {
            // Генерация ключа для кэша
            $cacheKey = 'sqlBook_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlBook($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlBook(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::BOOK_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::BOOK_TYPE;
        }
        
        public static function sqlChapter(null|int $id = null): string
        {
            // Генерация ключа для кэша
            $cacheKey = 'sqlSection_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlChapter($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlChapter(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::CHAPTER_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::CHAPTER_TYPE;
        }
        
        public static function sqlSection(?int $id = null): string
        {
            // Генерация ключа для кэша
            $cacheKey = 'sqlSection_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlSection($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlSection(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::SECTION_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::SECTION_TYPE;
        }
        
        public static function sqlArticle(null|int $id = null): string
        {
            // Генерация ключа для кэша
            $cacheKey = 'sqlSection_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlArticle($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlArticle(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::ARTICLE_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::ARTICLE_TYPE;
        }
        
        public static function sqlGroup(null|int $id = null): string
        {
            // Генерация ключа для кэша
            $cacheKey = 'sqlGroup_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlGroup($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlGroup(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::GROUP_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::GROUP_TYPE;
        }
        
        public static function sqlThread(null|int $id = null): string
        {
            // Генерация ключа для кэша
            $cacheKey = 'sqlSection_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlThread($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlThread(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::THREAD_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::THREAD_TYPE;
        }
        
        public static function sqlTag(null|int $id = null): string
        {
            // Генерация ключа для кэша
            // Генерация ключа для кэша
            $cacheKey = 'sqlTag_' . ($id ?? Constant::STATUS_ROOT);
            
            return Yii::$app->cache->getOrSet($cacheKey, function () use ($id) {
                // Ваш запрос SQL
                $sql = self::buildSqlTag($id);
                
                // Выполнение запроса к базе данных
                return Yii::$app->db->createCommand($sql)->queryScalar();
            }, 600);
        }
        
        private static function buildSqlTag(?int $id = null): string
        {
            return ($id)
                ? 'SELECT updated_at FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::TAG_TYPE . ' AND parent_id = ' . $id
                : 'SELECT MAX(updated_at) FROM ' . Cache::tableName() . ' WHERE site_id = ' . Parametr::siteId() . ' AND text_type = ' . Constant::TAG_TYPE;
        }
        
    }
