<?php
    
    namespace backend\controllers\utils;
    
    use core\edit\entities\Utils\Column;
    use core\edit\forms\SortForm;
    use core\edit\forms\Utils\Table\ColumnForm;
    use core\edit\repositories\Utils\ColumnRepository;
    use core\edit\repositories\Utils\TableRepository;
    use core\edit\search\Utils\ColumnSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Utils\ColumnManageService;
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
    
    class ColumnController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Column::TEXT_TYPE;
        private const string  MODEL_LABEL   = Column::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Column::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Utils_ColumnController_';
        private const string  SERVICE_INDEX = 'ColumnManageService_';
        
        private ColumnRepository    $repository;
        private TableRepository     $tables;
        private ColumnManageService $service;
        
        public function __construct(
            $id,
            $module,
            ColumnRepository $repository,
            TableRepository $tables,
            ColumnManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->tables     = $tables;
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
                    'modelName' => Column::className(),
                ],
            ];
        }
        
        /**
         * Lists all Column models.
         * @param int $id
         * @return Response|string
         * @action Utils_ColumnController_actionIndex
         */
        public function actionIndex(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $table = $this->tables::get($id);
            
            $searchModel  = new ColumnSearch();
            $dataProvider = $searchModel->search
            (
                Yii::$app->request->queryParams,
                $id,
            );
            
            return $this->render(
                '@app/views/utils/column/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'table'        => $table,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /*####### Sort #######################################################*/
        
        /**
         * @throws Exception
         * @action Utils_ColumnController_actionResort
         */
        public function actionResort(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $table = $this->tables::get($id);
            
            if (!$table) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Таблица не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            $searchModel  = new ColumnSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $table->id,
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
                            'resort',
                            'id' => $table->id,
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
                '@app/views/utils/column/resort',
                [
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'table'        => $table,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
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
        
        
        /**
         * Displays a single Column model.
         * @param integer $id
         * @return Response|string
         * @action Utils_ColumnController_actionView
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
                '@app/views/utils/column/view',
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
         * Creates a new Column model.
         * If creation is successful, the browser will be redirected to the
         * 'view' column.
         * @param int $id
         * @return string|Response
         * @action Utils_ColumnController_actionCreate_
         */
        public function actionCreate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $table = $this->tables::get($id);
            
            if (!$table) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Таблица не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            
            $searchModel  = new ColumnSearch();
            $dataProvider = $searchModel->search
            (
                Yii::$app->request->queryParams,
                $id,
            );
            
            $form           = new ColumnForm();
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
                        'Колонка добавлена в таблицу - ' . $table->name,
                    );
                    
                    return
                        $this->redirect(
                            [
                                'create',
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
                '@app/views/utils/column/create',
                [
                    'model'        => $form,
                    'table'        => $table,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Column model.
         * If update is successful, the browser will be redirected to the
         * 'view' column.
         * @param integer $id
         * @return string|Response
         * @action Utils_ColumnController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $column = $this->repository::get($id);
            
            if (!$column) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Колонка не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $table = $this->tables::get($column->table_id);
            
            if (!$table) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Таблица не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new ColumnForm($column);
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
                            'id' => $column->id,
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
                '@app/views/utils/column/update',
                [
                    'model'    => $form,
                    'column'   => $column,
                    'table'    => $table,
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
         * @action Utils_ColumnController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Колонка не найдена!',
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
                    'Колонка успешно удалена!',
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
                    [
                        '/utils/table/view',
                        'id' => $model->table_id,
                    ],
            );
        }
        
        
        /*####### Editor #####################################################*/
        
        public function actionActivate(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            try {
                Yii::$app->getSession()
                         ->setFlash('success', 'Колонка  ' . $id . ' активирована!')
                ;
                $this->service->activate($id);
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
                Yii::$app->getSession()
                         ->setFlash('warning', 'Колонка  ' . $id . ' деактивирована!')
                ;
                $this->service->draft($id);
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
                $this->service->archive($id);
                Yii::$app->getSession()
                         ->setFlash('warning', 'Колонка  ' . $id . ' отправлена в архив!')
                ;
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
