<?php
    
    namespace frontend\controllers\admin;
    
    use core\helpers\CacheHelper;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use core\tools\Constant;
    use yii\data\DataProviderInterface;
    use yii\web\Controller;
    use yii\web\ErrorAction;
    use yii\web\View;
    
    abstract class MainController extends Controller
    {
        
        // Константы по умолчанию
        protected const bool MODEL_CACHE_INDEX = false;
        protected const bool MODEL_CACHE_VIEW  = false;
        protected const int  TEXT_TYPE         = Constant::SITE_TYPE;
        protected BreadcrumbsService $breadcrumbsService;
        protected MetaService        $metaService;
        protected SchemaService      $schemaService;
        
        public function __construct(
            $id,
            $module,
            BreadcrumbsService $breadcrumbsService,
            MetaService $metaService,
            SchemaService $schemaService,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->breadcrumbsService = $breadcrumbsService;
            $this->metaService        = $metaService;
            $this->schemaService      = $schemaService;
        }
        
        
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
            $behaviors = parent::behaviors();
            
            if (static::MODEL_CACHE_INDEX === true) {
                $behaviors[] = CacheHelper::modelIndex(static::TEXT_TYPE);
            }
            
            if (static::MODEL_CACHE_VIEW === true) {
                $behaviors[] = CacheHelper::modelView(static::TEXT_TYPE);
            }
            
            return $behaviors;
        }
        
        /**
         * Инициализирует сервисы страницы
         *
         * @param array                      $package
         * @param array|null                 $preloadImages
         * @param DataProviderInterface|null $dataProvider
         * @return void
         */
        protected function initializeWebPageServices(
            array                  $package,
            ?DataProviderInterface $dataProvider = null,
            ?array                 $preloadImages = null,
        ): void
        {
            $this->metaService::generateMetaTags($package, $preloadImages);
            $this->schemaService::setSchemaOrg($package, $dataProvider);
            $breadcrumbs = $this->breadcrumbsService::generateBreadcrumbs($package);
            if ($breadcrumbs) {
                $this->view->params['breadcrumbs'] = $breadcrumbs;
                $this->registerBreadcrumbsSchema($breadcrumbs);
            }
        }
        
        private function registerBreadcrumbsSchema(array $breadcrumbs): void
        {
            $schemaItems = [];
            foreach ($breadcrumbs as $position => $crumb) {
                $item = [
                    '@type'    => 'ListItem',
                    'position' => $position + 1,
                    'item'     => [
                        '@type' => 'WebPage',
                        'name'  => $crumb['schema']['name'] ?? $crumb['label'],
                    ],
                ];
                
                if (!empty($crumb['url'])) {
                    $item['item']['@id'] = $crumb['url'];
                    $item['item']['url'] = $crumb['url'];
                }
                
                $schemaItems[] = $item;
            }
            
            $schema = [
                '@context'        => 'https://schema.org',
                '@type'           => 'BreadcrumbList',
                'itemListElement' => $schemaItems,
            ];
            
            // Регистрируем JSON-LD скрипт
            $this->view->registerJsVar('breadcrumbsSchema', $schema, View::POS_END);
            $this->view->registerJs(
                "document.querySelector('head').appendChild(document.createElement('script'))" .
                ".setAttribute('type', 'application/ld+json');" .
                "document.querySelector('script[type=\"application/ld+json\"]')" .
                '.textContent = JSON.stringify(breadcrumbsSchema);',
                View::POS_END,
            );
        }
    }
