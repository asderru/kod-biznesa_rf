<?php
    
    namespace backend\controllers\addon;
    
    use core\edit\entities\Addon\Panel;
    use core\edit\forms\Addon\PanelForm;
    use core\edit\forms\SortForm;
    use core\edit\repositories\Addon\PanelRepository;
    use core\edit\repositories\Admin\InformationRepository;
    use core\edit\search\Addon\BannerSearch;
    use core\edit\search\Addon\PanelSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Addon\PanelManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
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
    
    class PanelController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE     = Panel::TEXT_TYPE;
        private const string MODEL_LABEL = Panel::MODEL_LABEL;
        private const string MODEL_PREFIX  = Panel::MODEL_PREFIX;
        private const string ACTION_INDEX  = 'Addon_PanelController_';
        private const string SERVICE_INDEX = 'PanelManageService_';
        
        private PanelRepository       $repository;
        private PanelManageService    $service;
        private InformationRepository $sites;
        
        public function __construct(
            $id,
            $module,
            PanelRepository $repository,
            PanelManageService $service,
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
                    'modelName' => Panel::className(),
                ],
            ];
        }
        
        /**
         * Lists all Panel models.
         * @param int|null $id
         * @return Response|string
         * @action Addon_PanelController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new PanelSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/addon/panel/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'siteId'       => $id,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Exception
         * @action Addon_PanelController_actionResort
         */
        public function actionResort(
            ?int $id,
        ):
        Response|string
        {
            $actionId     = static::ACTION_INDEX . 'actionResort';
            $searchModel  = new PanelSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
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
                '@app/views/addon/panel/resort',
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
         * Displays a single Panel model.
         * @param integer $id
         * @return Response|string
         * @action Addon_PanelController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $model = $this->repository::get($id);
            
            return $this->render(
                '@app/views/addon/panel/view',
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
         * Creates a new Panel model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @throws Throwable
         * @action Addon_PanelController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form           = new PanelForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $model = $this->service->create($form);
                    if ($model) {
                        Yii::$app->session->
                        setFlash(
                            'success',
                            'Панель успешно создана!',
                        );
                        return $this->redirect(
                            [
                                'insert',
                                'id' => $model->id,
                            ],
                        );
                    }
                }
                catch (DomainException $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'create',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/addon/panel/create',
                [
                    'model'    => $form,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Panel model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Addon_PanelController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $panel = $this->repository::get($id);
            
            if (!$panel) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Banner  #' . $id . ' не найден!',
                );
                return $this->redirect(
                    [
                        'index',
                    ],
                );
            }
            
            $form = new PanelForm($panel);
            
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
                            'id' => $panel->id,
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
                '@app/views/addon/panel/update',
                [
                    'model'    => $form,
                    'panel'    => $panel,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Updates an existing Panel model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Addon_PanelController_actionInsert
         */
        public function actionInsert(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionInsert';
            
            $model = $this->repository::get($id);
            
            $searchModel  = new BannerSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            
            return $this->render(
                '@app/views/addon/panel/insert',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model'        => $model,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        public function actionBanner(): ?Response
        {
            if (Yii::$app->request->isAjax) {
                $keylist = Yii::$app->request->get('keylist');
                $modelId = Yii::$app->request->get('modelId');
                
                $this->service->insert($keylist, $modelId);
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Изменения внесены в базу!',
                         )
                ;
                return $this->redirect(
                    [
                        'view',
                        'id' => $modelId,
                    ],
                );
            }
            return $this->redirect(
                [
                    'index',
                ],
            );
        }
        
        
        /**
         * Deletes an existing Panel model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Addon_PanelController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect(
                    [
                        'index',
                    ],
                );
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
        
        /**
         * @param integer $id
         * @param int     $panelId
         * @return mixed
         * @action Addon_PanelController_actionMoveBannerUp
         */
        public function actionMoveBannerUp(int $id, int $panelId): Response
        {
            $this->service->moveBannerUp($id, $panelId);
            return $this->redirect(
                [
                    'view',
                    'id' => $id,
                ],
            );
        }
        
        /*####### Sort #######################################################*/
        
        
        /**
         * @throws Throwable
         */
        public function actionClearSort(int $id): void
        {
            $this->service->clearCache();
            $this->service::reorderSort($id);
            $this->redirect(
                [
                    'resort',
                    'id' => $id,
                ],
            );
        }
        
    }
