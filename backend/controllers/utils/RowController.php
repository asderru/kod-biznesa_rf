<?php
    
    namespace backend\controllers\utils;
    
    use core\edit\entities\Utils\Row;
    use core\edit\forms\SortForm;
    use core\edit\forms\Utils\Table\RowForm;
    use core\edit\repositories\Utils\RowRepository;
    use core\edit\repositories\Utils\TableRepository;
    use core\edit\search\Utils\RowSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Utils\RowManageService;
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
    
    class RowController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Row::TEXT_TYPE;
        private const string  MODEL_LABEL   = Row::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Row::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Utils_RowController_';
        private const string  SERVICE_INDEX = 'RowManageService_';
        
        private RowRepository    $repository;
        private TableRepository  $tables;
        private RowManageService $service;
        
        public function __construct(
            $id,
            $module,
            RowRepository $repository,
            TableRepository $tables,
            RowManageService $service,
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
                    'modelName' => Row::className(),
                ],
            ];
        }
        
        /**
         * Lists all Row models.
         * @param int $id
         * @return Response|string
         * @action Utils_RowController_actionIndex
         */
        public function actionIndex(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $table = $this->tables::get($id);
            
            $searchModel  = new RowSearch();
            $dataProvider = $searchModel->search
            (
                Yii::$app->request->queryParams,
                $id,
            );
            
            return $this->render(
                '@app/views/utils/row/index',
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
        
        /**
         * @throws Exception
         * @action Utils_RowController_actionResort
         */
        public function actionResort(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $table = $this->tables::get($id);
            
            if (!$table) {
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
            
            $searchModel  = new RowSearch();
            $dataProvider = $searchModel->search
            (
                Yii::$app->request->queryParams, $id,
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
                '@app/views/utils/row/resort',
                [
                    'searchModel'  => $searchModel,
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
        
        
        /**
         * Displays a single Row model.
         * @param integer $id
         * @return Response|string
         * @action Utils_RowController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/utils/row/view',
                [
                    'model'    => $this->repository::get($id),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new Row model.
         * If creation is successful, the browser will be redirected to the
         * 'view' row.
         * @param int $id
         * @return string|Response
         * @action Utils_RowController_actionCreate_
         */
        public function actionCreate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $table = $this->tables::get($id);
            
            if (!$table) {
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
            
            $searchModel  = new RowSearch();
            $dataProvider = $searchModel->search
            (
                Yii::$app->request->queryParams, $id,
            );
            
            $form           = new RowForm();
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
                        'Строка добавлена в таблицу - ' . $table->name,
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
                '@app/views/utils/row/create',
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
         * Updates an existing Row model.
         * If update is successful, the browser will be redirected to the
         * 'view' row.
         * @param integer $id
         * @return string|Response
         * @action Utils_RowController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $row = $this->repository::get($id);
            
            if (!$row) {
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
            
            $table = $this->tables::get($row->table_id);
            
            $form = new RowForm($row);
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
                            'id' => $row->id,
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
                '@app/views/utils/row/update',
                [
                    'model'    => $form,
                    'row'      => $row,
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
         * @action Utils_RowController_actionDelete
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
                    'Строка успешно удалена!',
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
        
        /*####### Sort #######################################################*/
        
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
                         ->setFlash('success', 'Строка  ' . $id . ' активирована!')
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
                         ->setFlash('warning', 'Строка  ' . $id . ' деактивирована!')
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
                         ->setFlash('warning', 'Строк  ' . $id . ' отправлена в архив!')
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
