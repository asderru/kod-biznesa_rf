<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Admin\Information;
    use core\edit\entities\Content\Page;
    use core\helpers\PrintHelper;
    use core\read\readers\Admin\InformationReader;
    use core\read\readers\Blog\PostReader;
    use core\read\readers\Content\NoteReader;
    use core\read\readers\Content\PageReader;
    use core\read\readers\Seo\AnonsReader;
    use core\read\readers\Seo\NewsReader;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use Exception;
    use frontend\controllers\admin\MainController;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class SiteController2 extends MainController
    {
        protected const int        TEXT_TYPE      = Information::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Information::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Information::MODEL_LABEL;
        protected const string     CACHE_TAG      = Information::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Information::DEFAULT_FIELDS;
        public       $layout    = '@app/views/layouts/main';
        public array              $pageNotes = [];
        private InformationReader $sites;
        private PageReader        $pages;
        private NoteReader  $notes;
        private PostReader  $posts;
        private NewsReader  $news;
        private AnonsReader $anonses;
        
        public function __construct(
            $id,
            $module,
            InformationReader $sites,
            PageReader $pages,
            NoteReader $notes,
            BreadcrumbsService $breadcrumbsService,
            MetaService $metaService,
            SchemaService $schemaService,
            $config = [],
        )
        {
            parent::__construct(
                $id, $module,
                $breadcrumbsService,
                $metaService,
                $schemaService,
                $config,
            );
            $this->sites              = $sites;
            $this->pages              = $pages;
            $this->notes              = $notes;
            $this->breadcrumbsService = $breadcrumbsService;
            $this->metaService        = $metaService;
            $this->schemaService      = $schemaService;
        }
        
        #[ArrayShape([
            'error' => 'string[]',
        ])]
        public function actions(): array
        {
            return [
                'error' => [
                    'class'  => ErrorAction::class,
                    'layout' => '@app/views/layouts/error',
                ],
            ];
        }
        
        public function behaviors(): array
        {
            return
                [
                    //  CacheHelper::index(),
                ];
        }
        
        public function actionIndex(): string|Response
        {
            $package  = $this->sites->getFullPackedSite();
            $rootPage = $this->pages->getFullPackedRoot();
            $pages    = $this->pages->getMainArray(Page::FULL_PACK_FIELDS);
            
            $pageNotes = [];
            foreach ($pages as $page) {
                $pageNotes[$page['id']] = $this->notes->getNotesArray($page['array_type'], $page['id']);
            }
            
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package);
            
            return $this->render(
                '@app/views/site/index',
                [
                    'model'     => current($package),
                    'rootPage'  => $rootPage,
                    'pages'     => $pages,
                    'pageNotes' => $pageNotes,
                    'textType'  => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * @throws Exception
         * @throws Throwable
         */
        
        public function actionError(): string|Response
        {
            try {
                $randomNumber = random_int(1, 15);
            }
            catch (Exception $e) {
                // Логируем исключение, устанавливаем значение по умолчанию
                PrintHelper::exception('randomNumber', $e);
                $randomNumber = 1; // значение по умолчанию, если генерация случайного числа не удалась
            }
            
            $exception = Yii::$app->errorHandler->exception;
            
            if ($exception !== null) {
                // Обработка ошибки и отображение соответствующего представления
                $message = $exception->getMessage();
                
                return $this->render(
                    'error',
                    [
                        'exception' => $exception,
                        'message'   => $message,
                        'textType'  => self::TEXT_TYPE,
                    ],
                );
            }
            
            return $this->redirect(
                [
                    'index',
                ],
            );
        }
    }
