<?php
    
    namespace backend\controllers\utils;
    
    use core\edit\assignments\TableAssignment;
    use core\edit\entities\Utils\Table;
    use core\edit\forms\SortForm;
    use core\edit\forms\Utils\Table\AssignTableEditForm;
    use core\edit\forms\Utils\Table\AssignTableForm;
    use core\edit\repositories\Utils\TableAssignmentRepository;
    use core\edit\search\Content\TableAssignmentSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Utils\TableAssignmentManageService;
    use core\helpers\ClearHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
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
    
    class TableAssignController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = TableAssignment::TEXT_TYPE;
        private const string  MODEL_LABEL   = 'Связь таблиц';
        private const string  MODEL_PREFIX  = 'tables_assignments';
        private const string  ACTION_INDEX  = 'Utils_TableAssignController_';
        private const string  SERVICE_INDEX = 'TableAssignmentManageService_';
        
        private TableAssignmentRepository    $repository;
        private TableAssignmentManageService $service;
        
        public function __construct(
            $id,
            $module,
            TableAssignmentManageService $service,
            TableAssignmentRepository $repository,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service    = $service;
            $this->repository = $repository;
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
                    'modelName' => TableAssignment::className(),
                ],
            ];
        }
        
        /**
         * Lists all TableAssignment models.
         * @return Response|string
         * @action Utils_TableAssignController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new TableAssignmentSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/utils/table-assign/index',
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
         * @action Utils_TableAssignController_actionResort
         */
        public function actionResort(int $textType, int $parentId): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $parent = ParentHelper::getModel($textType, $parentId);
            
            $searchModel  = new TableAssignmentSearch();
            $dataProvider = $searchModel->search
            (
                Yii::$app->request->queryParams,
                $textType,
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
                            'resort', 'textType' => $textType, 'parentId' => $parentId,
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
                '@app/views/utils/table-assign/resort',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'parent'       => $parent,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single TableAssignment model.
         * @param int $id
         * @return Response|string
         * @action Utils_TableAssignController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $tableAss = $this->repository::get($id);
            
            $parent = ParentHelper::getModel(
                $tableAss->text_type,
                $tableAss->parent_id,
            );
            
            $tableAsses = (new TableAssignment)
                ->getTablesByParent(
                    $tableAss->site_id,
                    $tableAss->text_type,
                    $tableAss->parent_id,
                )
                ->sorted()
                ->all()
            ;
            
            return $this->render(
                '@app/views/utils/table-assign/view',
                [
                    'models'   => $tableAsses,
                    'parent'   => $parent,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                    'actionId' => $actionId,
                ],
            );
        }
        
        
        /**
         * Creates a new TableAssignment model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int $siteId
         * @param int $textType
         * @param int $parentId
         * @return string|Response
         * @action Utils_TableAssignController_actionCreate_
         */
        public function actionCreate(
            int $siteId,
            int $textType,
            int $parentId,
        ): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form = new AssignTableForm(
                $siteId,
                $textType,
                $parentId,
            );
            $view = TypeHelper::getView($textType);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->assign($form);
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Таблицы успешно привязаны!',
                    );
                    
                    return $this->redirect(
                        [
                            $view,
                            'id' => $parentId,
                        ]
                        ??
                        [
                            'view',
                            'siteId'   => $siteId,
                            'textType' => $textType,
                            'parentId' => $parentId,
                        ],
                    );
                }
                catch (DomainException|StaleObjectException|Throwable $e) {
                    PrintHelper::exception($actionId, 'Проблема с ', $e);
                }
            }
            
            $tables = Table::find()->active()->all();
            
            return $this->render(
                '@app/views/utils/table-assign/create',
                [
                    'model'    => $form,
                    'siteId'   => $siteId,
                    'textType' => $textType,
                    'parentId' => $parentId,
                    'tables'   => $tables,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Product model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Utils_TableAssignController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $tableAss = $this->repository::get($id);
            
            $form = new AssignTableEditForm($tableAss);
            
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
                            'id' => $id,
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
                '@app/views/utils/table-assign/update',
                [
                    'model'    => $form,
                    'tableAss' => $tableAss,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Deletes an existing Note model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Utils_TableAssignController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            
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
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
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
            $actionId = static::ACTION_INDEX . 'actionDraft';
            
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
