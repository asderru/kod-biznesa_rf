<?php
    
    namespace backend\controllers;
    
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Tech\Cache;
    use core\edit\entities\User\User;
    use core\edit\repositories\Blog\CategoryRepository;
    use core\edit\repositories\Blog\PostRepository;
    use core\edit\repositories\Content\PageRepository;
    use core\edit\repositories\Forum\GroupRepository;
    use core\edit\repositories\Forum\ThreadRepository;
    use core\edit\repositories\Library\BookRepository;
    use core\edit\repositories\Library\ChapterRepository;
    use core\edit\repositories\Seo\FaqRepository;
    use core\edit\repositories\Seo\FootnoteRepository;
    use core\edit\repositories\Seo\MaterialRepository;
    use core\edit\repositories\Seo\NewsRepository;
    use core\edit\repositories\Shop\ProductRepository;
    use core\edit\repositories\Shop\RazdelRepository;
    use core\edit\repositories\Tech\CacheRepository;
    use core\edit\repositories\Tools\DraftRepository;
    use core\edit\repositories\Utils\GalleryRepository;
    use core\edit\search\Blog\CategorySearch;
    use core\edit\search\Blog\PostSearch;
    use core\edit\search\Content\NoteSearch;
    use core\edit\search\Content\PageSearch;
    use core\edit\search\Content\TagSearch;
    use core\edit\search\Forum\GroupSearch;
    use core\edit\search\Forum\ThreadSearch;
    use core\edit\search\Library\AuthorSearch;
    use core\edit\search\Library\BookSearch;
    use core\edit\search\Library\ChapterSearch;
    use core\edit\search\Magazin\ArticleSearch;
    use core\edit\search\Magazin\SectionSearch;
    use core\edit\search\SearchModelInterface;
    use core\edit\search\Seo\AnonsSearch;
    use core\edit\search\Seo\MaterialSearch;
    use core\edit\search\Seo\NewsSearch;
    use core\edit\search\Shop\ProductSearch;
    use core\edit\search\Shop\RazdelSearch;
    use core\edit\search\Tools\DraftSearch;
    use core\edit\search\Utils\GallerySearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Tech\SiteStatusManageService;
    use core\helpers\ParametrHelper;
    use core\tools\Constant;
    use Exception;
    use InvalidArgumentException;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\base\InvalidConfigException;
    use yii\data\ActiveDataProvider;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\helpers\FileHelper;
    use yii\web\Controller;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    /**
     *
     * @property-read array $allCacheItems
     */
    class SiteController3 extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE             = Information::TEXT_TYPE;
        private const string MODEL_LABEL           = Information::MODEL_LABEL;
        private const string MODEL_PREFIX          = Information::MODEL_PREFIX;
        private const string ACTION_INDEX          = 'SiteController_';
private const int    CACHE_DURATION        = Constant::DEFAULT_CACHE_DURATION;
                private const string CACHE_KEY_PREFIX      = 'site_controller_cache_'; // 1 час
        private const string CACHE_KEY_LAST_UPDATE = 'site_controller_last_update';
        private const array CACHE_TYPES
            = [
                Constant::RAZDEL_TYPE   => ['repository' => RazdelRepository::class],
                Constant::PRODUCT_TYPE  => ['repository' => ProductRepository::class],
                Constant::BOOK_TYPE     => ['repository' => BookRepository::class],
                Constant::CHAPTER_TYPE  => ['repository' => ChapterRepository::class],
                Constant::CATEGORY_TYPE => ['repository' => CategoryRepository::class],
                Constant::POST_TYPE     => ['repository' => PostRepository::class],
                Constant::GROUP_TYPE    => ['repository' => GroupRepository::class],
                Constant::THREAD_TYPE   => ['repository' => ThreadRepository::class],
                Constant::FAQ_TYPE      => ['repository' => FaqRepository::class],
                Constant::FOOTNOTE_TYPE => ['repository' => FootnoteRepository::class],
                Constant::PAGE_TYPE     => ['repository' => PageRepository::class],
                Constant::DRAFT_TYPE    => ['repository' => DraftRepository::class],
                Constant::NEWS_TYPE     => ['repository' => NewsRepository::class],
                Constant::MATERIAL_TYPE => ['repository' => MaterialRepository::class],
                Constant::GALLERY_TYPE  => ['repository' => GalleryRepository::class],
            ];
        private const array TYPE_CONFIGS
            = [
                Constant::RAZDEL_TYPE   => [
                    'searchModel' => RazdelSearch::class,
                    'view'        => '@app/views/site/view1',
                ],
                Constant::PRODUCT_TYPE  => [
                    'searchModel' => ProductSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::SECTION_TYPE  => [
                    'searchModel' => SectionSearch::class,
                    'view'        => '@app/views/site/view1',
                ],
                Constant::ARTICLE_TYPE  => [
                    'searchModel' => ArticleSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::BOOK_TYPE     => [
                    'searchModel' => BookSearch::class,
                    'view'        => '@app/views/site/view1',
                ],
                Constant::CHAPTER_TYPE  => [
                    'searchModel' => ChapterSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::CATEGORY_TYPE => [
                    'searchModel' => CategorySearch::class,
                    'view'        => '@app/views/site/view1',
                ],
                Constant::POST_TYPE     => [
                    'searchModel' => PostSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::PAGE_TYPE     => [
                    'searchModel' => PageSearch::class,
                    'view'        => '@app/views/site/view1',
                ],
                Constant::GROUP_TYPE    => [
                    'searchModel' => GroupSearch::class,
                    'view'        => '@app/views/site/view1',
                ],
                Constant::BRAND_TYPE    => [
                    'searchModel' => ThreadSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::NEWS_TYPE     => [
                    'searchModel' => NewsSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::MATERIAL_TYPE => [
                    'searchModel' => MaterialSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::AUTHOR_TYPE   => [
                    'searchModel' => AuthorSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::ANONS_TYPE    => [
                    'searchModel' => AnonsSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::GALLERY_TYPE  => [
                    'searchModel' => GallerySearch::class,
                    'view'        => '@app/views/site/view1',
                ],
                Constant::TAG_TYPE      => [
                    'searchModel' => TagSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::NOTE_TYPE     => [
                    'searchModel' => NoteSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
                Constant::DRAFT_TYPE    => [
                    'searchModel' => DraftSearch::class,
                    'view'        => '@app/views/site/view2',
                ],
            ];
        private const int DEFAULT_PAGE_SIZE = 50;
        public $layout = '@app/views/layouts/main';
        private CacheRepository         $repository;
        private SiteStatusManageService $service;
        
        public function __construct(
            $id,
            $module,
            CacheRepository $repository,
            SiteStatusManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
        }
        
        /**
         * {@inheritdoc}
         */
        #[ArrayShape([
            'access' => "array",
            'verbs'  => "array",
        ])]
        public function behaviors(): array
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['login', 'error'],
                            'allow'   => true,
                        ],
                        [
                            'actions' => ['logout', 'index', 'express', 'view', 'upload'],
                            'allow'   => true,
                            'roles'   => ['@'],
                        ],
                        [
                            'actions' => ['start', 'convert', 'activate', 'update'],
                            'allow'   => true,
                            'roles'   => ['superadmin'],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'logout' => ['POST'],
                    ],
                ],
            ];
        }
        
        /**
         * {@inheritdoc}
         */
        #[ArrayShape(['error' => "string[]"])]
        public function actions(): array|string
        {
            return [
                'error' => [
                    'class'  => ErrorAction::class,
                    'layout' => 'blank',
                ],
            ];
        }
        
        /**
         * Displays homepage.
         * @param int|null $id
         * @return Response|string
         * @throws Throwable
         * @action SiteController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId   = static::ACTION_INDEX . 'actionIndex';
            $superadmin = (new User)->isSuperadmin();
            $site = ParametrHelper::getSite($id);
            $sites = ParametrHelper::getSites();
            $isAlone    = ($site->children()->all() === []);
            $siteId     = ($isAlone) ? $site->id : null;
            
            $result      = $this->getCachedData($siteId);
            $resolvedIds = $this->resolveModelIds($result);
            
            $panel = explode('.', Yii::getAlias('@backHost'))[0];
            
            return $this->render(
                '@app/views/site/index',
                [
                    'superadmin' => $superadmin,
                    'sites'      => $sites,
                    'site'       => $site,
                    'isAlone'    => $isAlone,
                    'actionId'   => $actionId,
                    'panel'      => $panel,
                    'textType'   => static::TEXT_TYPE,
                    'prefix'     => static::MODEL_PREFIX,
                    'label'      => static::MODEL_LABEL,
                ] + $result + $resolvedIds,
            ); // Merge the processed items into the render array
        }
        
        /**
         * Displays a single Razdel model.
         * @param integer $id
         * @param ?int    $siteId
         * @return Response|string
         * @throws Exception
         * @action SiteController_actionView
         */
        public function actionView(int $id, ?int $siteId = null): Response|string
        {
            $config = self::TYPE_CONFIGS[$id] ?? throw new InvalidConfigException("Unknown type: $id");
            
            return $this->processView($id, $siteId, $config);
        }
        
        
        /**
         * @action SiteController_actionUpdate
         */
        public function actionUpdate(): Response
        {
            // Устанавливаем максимальное время выполнения скрипта в 600 секунд
            set_time_limit(600);
            
            try {
                // Создаем временное соединение с удаленной базой данных
                $remoteDb = Yii::$app->remoteDb;
                $remoteDb->open();
                
                $dsn = Yii::$app->remoteDb->dsn;
                preg_match('/dbname=([^;]*)/', $dsn, $matches);
                $databaseName = $matches[1] ?? 'Неизвестно';
                
                // Получаем список таблиц
                $allTables = $remoteDb->schema->getTableNames();
                
                $tables = array_filter($allTables, function ($table) {
                    return !str_starts_with($table, 'auth_')
                           && !str_starts_with($table, 'user_')
                           && !str_starts_with($table, 'oauth_')
                           && !str_starts_with($table, 'q_')
                           && $table !== 'users';
                });
                
                $tempDir = Yii::getAlias('@runtime/temp_db_dump');
                
                if (!FileHelper::createDirectory($tempDir)) {
                    throw new Exception('Cannot create directory');
                }
                
                if (!is_writable($tempDir)) {
                    throw new Exception('Directory is not writable');
                }
                
                // Экспортируем данные из удаленной базы
                foreach ($tables as $table) {
                    $command = $remoteDb->createCommand("SELECT * FROM `$table`");
                    $data    = $command->queryAll();
                    file_put_contents("$tempDir/$table.json", json_encode($data));
                }
                
                // Сохраняем структуру таблиц
                foreach ($tables as $table) {
                    $createTableSql = $remoteDb->createCommand("SHOW CREATE TABLE `$table`")->queryOne()['Create Table'];
                    file_put_contents("$tempDir/$table.sql", $createTableSql);
                }
                
                $remoteDb->close();
                
                // Подключаемся к локальной базе данных
                $localDb = Yii::$app->db;
                $localDb->open();
                // Удаляем таблицы в локальной базе данных, кроме исключенных
                $localDb->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
                foreach ($tables as $table) {
                    $localDb->createCommand("DROP TABLE IF EXISTS `$table`")->execute();
                }
                $localDb->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
                // Создаем структуру таблиц
                foreach ($tables as $table) {
                    $createTableSql = file_get_contents("$tempDir/$table.sql");
                    $localDb->createCommand($createTableSql)->execute();
                }
                
                // Импортируем данные в локальную базу
                foreach ($tables as $table) {
                    $data = json_decode(file_get_contents("$tempDir/$table.json"), true);
                    foreach ($data as $row) {
                        $localDb->createCommand()->insert($table, $row)->execute();
                    }
                }
                
                // Закрываем соединение
                $localDb->close();
                
                // Удаляем временные файлы
                FileHelper::removeDirectory($tempDir);
                
                Yii::$app->session->setFlash('success', 'База данных "' . $databaseName . '" успешно обновлена');
                return $this->redirect(['index']);
            }
            catch (Exception $e) {
                Yii::error('Ошибка при обновлении базы данных ": ' . $e->getMessage());
                Yii::$app->session->setFlash(
                    'danger', 'Произошла ошибка при обновлении базы данных"'
                             . '": ' . $e->getMessage(),
                );
                return $this->redirect(['index']);
            }
        }
        
        /**
         * @throws Exception
         */
        public function actionExpress(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionExpress';
            
            $isAlone = ParametrHelper::isAlone();
            
            return $this->render(
                '@app/views/site/express',
                [
                    'isAlone'  => $isAlone,
                    'actionId' => $actionId,
                ],
            );
        }
        
        public function actionConvert(): void
        {
            $sourceFolder = Yii::$app->params['frontendPath'] . '/img';
            
            $this->convertImagesRecursively($sourceFolder);

// Display success message
            Yii::$app->session->setFlash('success', 'Все картинки сконвертированы в WebP!');
            $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }
        
        public function actionActivate(): Response
        {
            if ($this->service::setProduction()) {
                Yii::$app->getSession()->setFlash(
                    'success',
                    'Установлена рабочая конфигурация сайта!',
                );
            }

// Возвращаем пользователя на предыдущий URL или на главную страницу, если referrer не задан
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }
        
        private function convertImagesRecursively(
            string $folder,
        ): void
        {
            if (!is_dir($folder)) {
// Если директория не существует, создаем ее
                mkdir($folder, 0777, true);
            }
            
            $files = scandir($folder);
            
            foreach ($files as $file) {
                $filePath = $folder . DIRECTORY_SEPARATOR . $file;
                
                if ($file !== '.' && $file !== '..') {
                    if (is_dir($filePath)) {
// Recursively call the function for the subfolder
                        $this->convertImagesRecursively($filePath);
                    }
                    elseif (is_file($filePath) && in_array(pathinfo($filePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                        $image = null;
                        
                        switch (pathinfo($filePath, PATHINFO_EXTENSION)) {
                            case 'jpg':
                            case 'jpeg':
                                $image = imagecreatefromjpeg($filePath);
                                break;
                            case 'png':
                                $image = imagecreatefrompng($filePath);
                                break;
                            case 'gif':
                                $image = imagecreatefromgif($filePath);
                                break;
                        }
                        
                        if ($image !== false) {
// Convert the image to true color if it's palette-based
                            $trueColorImage = imagecreatetruecolor(imagesx($image), imagesy($image));
                            imagecopy($trueColorImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                            
                            $webpFilePath = str_replace('.' . pathinfo($filePath, PATHINFO_EXTENSION), '.webp', $filePath);
                            
                            if (!file_exists($webpFilePath) || filemtime($webpFilePath) < filemtime($filePath)) {
                                imagewebp($trueColorImage, $webpFilePath);
                            }
                            
                            imagedestroy($image);
                            imagedestroy($trueColorImage);
                        }
                    }
                }
            }
        }
        
        /**
         * @throws Exception
         */
        private function getCachedData(
            ?int $siteId,
        ): array
        {
            $cache    = Yii::$app->cache;
            $nodeIds  = ParametrHelper::getNodeIds();
            $cacheKey = self::CACHE_KEY_PREFIX . implode('_', $nodeIds);

// Get last update time
            $currentLastUpdate = Cache::find()
                                      ->select('MAX(updated_at)')
                                      ->where(['in', 'site_id', $nodeIds])
                                      ->andWhere(['>', 'status', Constant::STATUS_ROOT])
                                      ->scalar()
            ;

// Check if data needs refresh
            $cachedLastUpdate = $cache->get(self::CACHE_KEY_LAST_UPDATE);
            
            if ($cachedLastUpdate === false || $cachedLastUpdate < $currentLastUpdate) {
                $result = $this->getAndCacheNewData($siteId, $cacheKey, $currentLastUpdate);
            }
            else {
                $result = $cache->get($cacheKey)
                    ?:
                    $this->getAndCacheNewData($siteId, $cacheKey, $currentLastUpdate ?? 0);
            }
            
            return $result;
        }
        
        /**
         * @throws Exception
         */
        private function getAndCacheNewData(
            ?int $siteId, string $cacheKey, ?int $currentLastUpdate,
        ): array
        {
            $cache = Yii::$app->cache;
            if ($currentLastUpdate === null) {
                $currentLastUpdate = 0; // Или задайте поведение по умолчанию
            }

// Получаем все элементы кэша одним запросом
            $cacheItems = $this->getAllCacheItems();

// Обрабатываем элементы и получаем резервные данные при необходимости
            $result = $this->processTypesWithFallback($cacheItems, $siteId);

// Сохраняем результат в кэш
            $cache->set($cacheKey, $result, self::CACHE_DURATION);
            $cache->set(self::CACHE_KEY_LAST_UPDATE, $currentLastUpdate, self::CACHE_DURATION);
            
            return $result;
        }
        
        /**
         * @throws Exception
         */
        private function getAllCacheItems(): array
        {
            $types = array_keys(self::CACHE_TYPES);
            
            return Cache::find()
                        ->where(['>', 'status', Constant::STATUS_ROOT])
                        ->andWhere(['in', 'text_type', $types])
                        ->andWhere(['in', 'site_id', ParametrHelper::getNodeIds()])
                        ->orderBy(['text_type' => SORT_ASC, 'updated_at' => SORT_DESC])
                        ->all()
            ;
        }
        
        private function processTypesWithFallback(
            array $cacheItems, ?int $siteId,
        ): array
        {
            $result       = [];
            $groupedCache = [];
            
            foreach ($cacheItems as $item) {
                if (!isset($groupedCache[$item->text_type])) {
                    $groupedCache[$item->text_type] = $item;
                }
            }
            
            foreach (self::CACHE_TYPES as $type => $config) {
                $key          = $this->getKeyForType($type);
                $result[$key] = $groupedCache[$type] ?? $config['repository']::getLastData($siteId);
            }
            
            return $result;
        }
        
        private function getKeyForType(
            int $type,
        ): string
        {
            $map = [
                Constant::RAZDEL_TYPE   => 'razdel',
                Constant::PRODUCT_TYPE  => 'product',
                Constant::BOOK_TYPE     => 'book',
                Constant::CHAPTER_TYPE  => 'chapter',
                Constant::CATEGORY_TYPE => 'category',
                Constant::POST_TYPE     => 'post',
                Constant::GROUP_TYPE    => 'group',
                Constant::THREAD_TYPE   => 'thread',
                Constant::FAQ_TYPE      => 'faq',
                Constant::FOOTNOTE_TYPE => 'footnote',
                Constant::PAGE_TYPE     => 'page',
                Constant::DRAFT_TYPE    => 'draft',
                Constant::NEWS_TYPE     => 'news',
                Constant::MATERIAL_TYPE => 'material',
                Constant::GALLERY_TYPE  => 'gallery',
            ];
            
            return $map[$type] ?? throw new InvalidArgumentException("Unknown type: $type");
        }
        
        /**
         * @throws InvalidConfigException
         */
        private function processView(
            int $id, ?int $siteId, array $config,
        ): Response|string
        {
            $searchModel  = $this->createSearchModel($config['searchModel']);
            $dataProvider = $this->configureDataProvider($searchModel, $siteId);
            
            return $this->render($config['view'], [
                'dataProvider' => $dataProvider,
                'searchModel'  => $searchModel,
                'textType'     => $id,
                'pageSize'     => $dataProvider->pagination->pageSize,
            ]);
        }
        
        /**
         * @throws InvalidConfigException
         */
        private function createSearchModel(
            string $modelClass,
        ): SearchModelInterface
        {
            $model = Yii::createObject($modelClass);
            
            if (!$model instanceof SearchModelInterface) {
                throw new InvalidConfigException('Search model must implement SearchModelInterface');
            }
            
            return $model;
        }
        
        private function configureDataProvider(
            SearchModelInterface $searchModel, ?int $siteId,
        ): ActiveDataProvider
        {
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
            );
            
            $pageSize                           = (int)Yii::$app->request->get('pageSize', self::DEFAULT_PAGE_SIZE);
            $dataProvider->pagination->pageSize = $pageSize;
            
            return $dataProvider;
        }
        
        private function resolveModelIds(array $models): array
        {
            $modelTypes = [
                'razdel'   => Constant::RAZDEL_TYPE,
                'product'  => Constant::PRODUCT_TYPE,
                'book'     => Constant::BOOK_TYPE,
                'chapter'  => Constant::CHAPTER_TYPE,
                'category' => Constant::CATEGORY_TYPE,
                'post'     => Constant::POST_TYPE,
                'group'    => Constant::GROUP_TYPE,
                'thread'   => Constant::THREAD_TYPE,
                'faq'      => Constant::FAQ_TYPE,
                'footnote' => Constant::FOOTNOTE_TYPE,
                'page'     => Constant::PAGE_TYPE,
                'draft'    => Constant::DRAFT_TYPE,
                'gallery'  => Constant::GALLERY_ALIAS,
                'site'     => Constant::SITE_TYPE,
                'news'     => Constant::NEWS_TYPE,
                'material' => Constant::MATERIAL_TYPE,
            ];
            
            $resolvedIds = [];
            
            foreach ($modelTypes as $modelKey => $constantType) {
                $model                         = $models[$modelKey] ?? null;
                $resolvedIds[$modelKey . 'Id'] = match (true) {
                    $model && $model::TEXT_TYPE === $constantType        => $model->id,
                    $model && $model::TEXT_TYPE === Constant::CACHE_TYPE => $model->parent_id,
                    default                                              => null
                };
            }
            
            return $resolvedIds;
        }
        
    }
