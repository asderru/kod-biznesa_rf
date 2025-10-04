<?php
    
    namespace backend\controllers\utils;
    
    use core\edit\entities\Utils\Table;
    use core\edit\forms\SortForm;
    use core\edit\forms\Utils\Table\TableForm;
    use core\edit\repositories\Admin\InformationRepository;
    use core\edit\repositories\Utils\TableRepository;
    use core\edit\search\Utils\TableSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Utils\TableManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
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
    
    class TableController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Table::TEXT_TYPE;
        private const string  MODEL_LABEL   = Table::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Table::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Utils_TableController_';
        private const string  SERVICE_INDEX = 'TableManageService_';
        
        private TableRepository       $repository;
        private TableManageService    $service;
        private InformationRepository $sites;
        
        public function __construct(
            $id,
            $module,
            TableRepository $repository,
            TableManageService $service,
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
                    'modelName' => Table::className(),
                ],
            ];
        }
        
        /**
         * Lists all Table models.
         * @param ?int $id
         * @return Response|string
         * @throws Exception
         * @action Utils_TableController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new TableSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            
            return $this->render(
                '@app/views/utils/table/index',
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
         * @action Utils_TableController_actionResort
         */
        public function actionResort(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $searchModel  = new TableSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
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
                '@app/views/utils/table/resort',
                [
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Table model.
         * @param integer $id
         * @return Response|string
         * @action Utils_TableController_actionView
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
            
            return $this->render(
                '@app/views/utils/table/view',
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
         * Displays a single Table model.
         * @return Response|string
         * @action Utils_TableController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form           = new TableForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $table = $this->service->create($form);
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Таблица успешно создана!',
                    );
                    
                    return $this->redirect(
                        [
                            '/content/column/create',
                            'id' => $table->id,
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
                '@app/views/utils/table/create',
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
         * Updates an existing Table model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @action Utils_TableController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $table = $this->repository::get($id);
            if (!$table) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new TableForm($table);
            
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
                            'id' => $table->id,
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
            
            return $this->render(
                '@app/views/utils/table/update',
                [
                    'model'    => $form,
                    'table'    => $table,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Updates an existing Table model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @action TableController_copy
         */
        public function actionCopy(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCopy';
            
            $table = $this->repository::get($id);
            if (!$table) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            $form = new TableForm($table);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $newTable = $this->service->copy($table->id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Таблица скопирована! Редактируйте данные!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $newTable->id,
                        ],
                    );
                }
                catch (DomainException|Exception $e) {
                    PrintHelper::exception($actionId, 'Проблема с ', $e);
                }
            }
            
            return $this->render(
                '@app/views/utils/table/copy',
                [
                    'model'    => $form,
                    'table'    => $table,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Deletes an existing Table model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Utils_TableController_actionDelete
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
                    'Таблица успешно удалена!',
                );
            }
            catch (StaleObjectException|Throwable $e) {
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
        
        ######## Editor ########################################################
        
        public function actionActivate(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionActivate';
            
            try {
                $this->service->activate($id);
                Yii::$app->getSession()
                         ->setFlash('success', 'Таблица  ' . $id . ' активирована!')
                ;
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'activate',
                    $e,
                );
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        public function actionDraft(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDraft';
            
            try {
                $this->service->draft($id);
                Yii::$app->getSession()
                         ->setFlash('warning', 'Таблица  ' . $id . ' деактивирована!')
                ;
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'draft',
                    $e,
                );
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        public function actionArchive(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionArchive';
            
            try {
                Yii::$app->getSession()
                         ->setFlash('warning', 'Таблица  ' . $id . ' отправлена в архив!')
                ;
                $this->service->archive($id);
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'archive',
                    $e,
                );
            }
            
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
    }
