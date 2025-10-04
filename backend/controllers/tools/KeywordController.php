<?php
    
    namespace backend\controllers\tools;
    
    use core\edit\entities\Tools\Keyword;
    use core\edit\forms\SortForm;
    use core\edit\forms\Tools\KeywordForm;
    use core\edit\repositories\Tools\KeywordRepository;
    use core\edit\search\Tools\KeywordSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Tools\KeywordManageService;
    use core\helpers\ParentHelper;
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
    
    class KeywordController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Keyword::TEXT_TYPE;
        private const string  MODEL_LABEL   = Keyword::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Keyword::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Tools_KeywordController_';
        private const string  SERVICE_INDEX = 'KeywordManageService_';
        
        private KeywordRepository    $repository;
        private KeywordManageService $service;
        
        public function __construct(
            $id,
            $module,
            KeywordRepository $repository,
            KeywordManageService $service,
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
                    'modelName' => Keyword::className(),
                ],
            ];
        }
        
        /**
         * Lists all Keyword models.
         * @param ?int $id
         * @return Response|string
         * @throws Exception
         * @action Tools_KeywordController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new KeywordSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            
            return $this->render(
                '@app/views/tools/keyword/index',
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
         * @action Tools_KeywordController_actionResort
         */
        public function actionResort(
            int $siteId,
            int $typeId,
            int $parentId,
        ): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $searchModel  = new KeywordSearch();
            $dataProvider = $searchModel->searchNode(
                Yii::$app->request->queryParams,
                $siteId,
                $typeId,
                $parentId,
            );
            
            $form = new SortForm();
            
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
                            'index',
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
                '@app/views/tools/keyword/resort',
                [
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'siteId'       => $siteId,
                    'typeId'       => $typeId,
                    'parentId'     => $parentId,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Keyword model.
         * @param integer $id
         * @return Response|string
         * @action Tools_KeywordController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
            
            return $this->render(
                '@app/views/tools/keyword/view',
                [
                    'model'    => $model,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Keyword model.
         * @return Response|string
         * @throws Throwable
         * @action Tools_KeywordController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form           = new KeywordForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $keyword = $this->service->create($form);
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Ключевые слова успешно созданы!',
                    );
                    
                    return
                        (Yii::$app->controller->id !== 'keyword')
                            ?
                            $this->redirect(Yii::$app->request->referrer)
                            :
                            $this->redirect(
                                'view',
                                [
                                    'id' => $keyword->id,
                                ],
                            );
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
                '@app/views/tools/keyword/create',
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
         * Updates an existing Keyword model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Throwable
         * @action Tools_KeywordController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $keyword = $this->repository::get($id);
            
            if (!$keyword) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Ключевые слова  #' . $id . ' не найдены!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new KeywordForm($keyword);
            
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
                            'id' => $keyword->id,
                        ],
                    );
                }
                catch (DomainException|Exception $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'edit_',
                        $e,
                    );
                }
            }
            
            $parent = ParentHelper::getModel($keyword->text_type, $keyword->parent_id);
            
            return $this->render(
                '@app/views/tools/keyword/update',
                [
                    'model'    => $form,
                    'keyword'  => $keyword,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Deletes an existing Keyword model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Tools_KeywordController_actionDelete
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
                return $this->redirect([
                    'index',
                ]);
            }
            
            try {
                $this->service->remove($model->id);
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Ключевые слова успешно удалены!',
                );
            }
            catch (StaleObjectException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'remove',
                    $e,
                );
            }
            
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /*####### Sort #######################################################*/
        
        /**
         * Displays a single Post model.
         * @return Response|string
         * @throws Throwable
         * @action PostController_view
         */
        public function actionClearCache(): Response|string
        {
            if ($this->service->clearCache()) {
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Кэш ключевых слов очищен!',
                         )
                ;
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
            
        }
        
        
        /**
         * @throws Throwable
         */
        public function actionClearSort(int $id): void
        {
            $this->service->clearCache();
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                
            }
            
            $this->service::reorderSort($model);
            
            $this->redirect(
                [
                    '@app/views/tools/keyword/resort',
                    'id' => $id,
                ],
            );
        }
        
        /*####### Active, Draft, Archive #####################################*/
        
        /**
         * @param integer $id
         * @return Response
         * @throws Throwable
         */
        public function actionActivate(int $id): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionActivate';
            
            try {
                $this->service->activate($id);
                Yii::$app->session->
                setFlash(
                    'success',
                    'Ключевые слова активированы!',
                );
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'activate',
                    $e,
                );
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /**
         * @param integer $id
         * @return mixed
         * @throws Throwable
         */
        public function actionDraft(int $id): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionDraft';
            
            try {
                $this->service->draft($id);
                Yii::$app->session->
                setFlash(
                    'success',
                    'Ключевые слова деактивированы!',
                );
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'draft',
                    $e,
                );
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /**
         * @param integer $id
         * @return mixed
         * @throws Throwable
         */
        public function actionArchive(int $id): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionArchive';
            
            try {
                $this->service->archive($id);
                Yii::$app->session->
                setFlash(
                    'success',
                    'Ключевые слова отправлены в архив!',
                );
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'archive',
                    $e,
                );
                
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
    }
