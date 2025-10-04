<?php
    
    namespace backend\controllers\addon;
    
    use core\edit\entities\Addon\Banner;
    use core\edit\forms\Addon\BannerForm;
    use core\edit\forms\SortForm;
    use core\edit\repositories\Addon\BannerRepository;
    use core\edit\repositories\Admin\InformationRepository;
    use core\edit\search\Addon\BannerSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Addon\BannerManageService;
    use core\helpers\ClearHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use DomainException;
    use Exception;
    use himiklab\sortablegrid\SortableGridAction;
    use JetBrains\PhpStorm\ArrayShape;
    use JetBrains\PhpStorm\Pure;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class BannerController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE     = Banner::TEXT_TYPE;
        private const string MODEL_LABEL = Banner::MODEL_LABEL;
        private const string MODEL_PREFIX  = Banner::MODEL_PREFIX;
        private const string ACTION_INDEX  = 'Addon_BannerController_';
        private const string SERVICE_INDEX = 'BannerManageService_';
        
        private BannerRepository      $repository;
        private BannerManageService   $service;
        private InformationRepository $sites;
        
        public function __construct(
            $id,
            $module,
            BannerRepository $repository,
            BannerManageService $service,
            InformationRepository $sites,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
            $this->sites      = $sites;
        }
        
        /**
         * {@inheritdoc}
         */
        #[ArrayShape(['verbs' => 'array'])]
        public function behaviors(): array
        {
            return [
                'verbs' => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ];
        }
        
        #[Pure]
        #[ArrayShape([
            'sort' => 'array',
        ])]
        public function actions(): array
        {
            return [
                'sort' => [
                    'class'     => SortableGridAction::className(),
                    'modelName' => Banner::className(),
                ],
            ];
        }
        
        /**
         * Lists all Banner models.
         * @return Response|string
         * @action Addon_BannerController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new BannerSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/addon/banner/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Exception
         * @action Addon_BannerController_actionResort
         */
        public function actionResort(
            int $typeId, ?int $id,
        ):
        Response|string
        {
            $actionId     = static::ACTION_INDEX . 'actionResort';
            $searchModel  = new BannerSearch();
            $dataProvider = $searchModel->searchSort(
                Yii::$app->request->queryParams,
                $typeId,
                $id ?? Parametr::siteId(),
            );
            
            $form = new SortForm();
            
            $site = $this->sites::get($id);
            
            if (!$site) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->setSort($form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Сортировка проведена успешно!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'resort',
                            'id' => $site->id,
                        ],
                    );
                }
                catch (DomainException $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'setSort',
                        $e,
                    );
                }
            }
            return $this->render(
                '@app/views/addon/banner/resort',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'site'         => $site,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Banner model.
         * @param integer $id
         * @return Response|string
         * @action Addon_BannerController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $model = $this->repository::get($id);
            
            return $this->render(
                '@app/views/addon/banner/view',
                [
                    'model'    => $model,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new Banner model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int $textType
         * @param int $parentId
         * @return string|Response
         * @action Addon_BannerController_actionCreate_
         */
        public function actionCreate(int $textType, int $parentId): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            if (empty($textType) || empty($parentId)) {
                Yii::$app->session->setFlash('danger', 'Родительская модель не определена.');
                return $this->redirect(['index']);
            }
            
            // Попытка получить родительскую модель
            $parent = ParentHelper::getModel($textType, $parentId);
            
            // Если модель не найдена, перенаправить с сообщением об ошибке
            if ($parent === null) {
                Yii::$app->session->setFlash('danger', 'Родительская модель не найдена.');
                return $this->redirect(['index']);
            }
            
            $form = new BannerForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->create($form);
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Баннер успешно создан!',
                    );
                    $url = TypeHelper::getLongEditUrl($textType);
                    return $this->redirect(
                        [
                            $url . 'view',
                            'id' => $parentId,
                        ],
                    );
                }
                catch (DomainException|StaleObjectException|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'create',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/addon/banner/create',
                [
                    'model'    => $form,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new Banner model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response|null
         * @action BannerController_actionGenerate
         */
        public function actionGenerate(): null|string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionGenerate';
            
            $form           = new BannerForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $model = $this->service->create($form);
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Баннер успешно создан!',
                    );
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ],
                    );
                }
                catch (DomainException|StaleObjectException|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'create',
                        $e,
                    );
                }
            }
            return null;
        }
        
        /**
         * Updates an existing Banner model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Addon_BannerController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $banner = $this->repository::get($id);
            
            if (!$banner) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new BannerForm($banner);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->edit($id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id'       => $banner->id,
                            'actionId' => $actionId,
                        ],
                    );
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'edit_',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/addon/banner/update',
                [
                    'model'    => $form,
                    'banner'   => $banner,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Deletes an existing Banner model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Addon_BannerController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            $model    = $this->repository::get($id);
            
            if (!$model) {
                return $this->redirect('index');
            }
            try {
                $this->service->remove($model->id);
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Баннер успешно удален!',
                );
            }
            catch (DomainException|StaleObjectException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'remove',
                    $e,
                );
            }
            
            return $this->redirect(
                (ClearHelper::getAction() !== 'view')
                    ?
                    Yii::$app->request->referrer
                    :
                    'index',
            );
        }
        
        /*###### Active ######################################################*/
        
        public function actionActivate(
            int $id,
        ): Response
        {
            $actionId = static::ACTION_INDEX . 'actionActivate';
            
            try {
                $this->service->activate($id);
                Yii::$app->session->
                setFlash(
                    'success',
                    'Баннер активирован!',
                );
            }
            catch (DomainException|Throwable  $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'activate',
                    $e,
                );
            }
            
            Yii::$app->getSession()
                     ->setFlash('success', 'Баннер  #' . $id . ' активирован!')
            ;
            
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        public function actionDraft(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDraft';
            
            try {
                $this->service->draft($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'warning',
                             'Отправлено в черновики!',
                         )
                ;
                
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'draft',
                    $e,
                );
            }
            
            Yii::$app->getSession()
                     ->setFlash('warning', 'Баннер  #' . $id . ' деактивирован!')
            ;
            
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        public function actionArchive(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionArchive';
            
            try {
                $this->service->archive($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'warning',
                             'Отправлено в архив!',
                         )
                ;
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'archive',
                    $e,
                );
            }
            
            Yii::$app->getSession()
                     ->setFlash('warning', 'Баннер  ' . $id . ' отправлен в архив!')
            ;
            
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /*####### Sort #######################################################*/
        
        /**
         * Displays a single Post model.
         * @return Response|string
         * @throws Throwable
         * @action Addon_BannerController_actionClearCache
         */
        public function actionClearCache(): Response|string
        {
            if ($this->service->clearCache()) {
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Кэш постов очищен!',
                         )
                ;
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
            
        }
        
        /**
         * @throws Throwable
         */
        public function actionClearSort(int $id, int $typeId): void
        {
            $this->service->clearCache();
            $this->service::reorderSort($id, $typeId);
            $this->redirect(
                [
                    'resort',
                    'id'     => $id,
                    'typeId' => $typeId,
                ],
            );
        }
        
    }
