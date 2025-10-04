<?php
    
    namespace backend\controllers\utils;
    
    use core\edit\entities\Utils\Data;
    use core\edit\repositories\Utils\ColumnRepository;
    use core\edit\repositories\Utils\DataRepository;
    use core\edit\repositories\Utils\RowRepository;
    use core\edit\repositories\Utils\TableRepository;
    use core\edit\search\Utils\DataSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\Exception;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class DataController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE    = Data::TEXT_TYPE;
        private const string MODEL_LABEL  = Data::MODEL_LABEL;
        private const string MODEL_PREFIX = Data::MODEL_PREFIX;
        private const string ACTION_INDEX = 'Utils_DataController_';
        
        private DataRepository   $repository;
        private TableRepository  $tables;
        private ColumnRepository $columns;
        private RowRepository    $rows;
        
        public function __construct(
            $id,
            $module,
            DataRepository $repository,
            TableRepository $tables,
            ColumnRepository $columns,
            RowRepository $rows,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->tables     = $tables;
            $this->columns    = $columns;
            $this->rows       = $rows;
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
        
        /**
         * Lists all Data models.
         * @param int $id
         * @return Response|string
         * @action Utils_DataController_actionIndex
         */
        public function actionIndex(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $table        = $this->tables::get($id);
            $searchModel  = new DataSearch();
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
        
        /**
         * Displays a single Data model.
         * @param integer $id
         * @return Response|string
         * @action Utils_DataController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/utils/data/view',
                [
                    'model'    => $this->tables::get($id),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new Data model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int $tableId
         * @param int $colId
         * @param int $rowId
         * @return string|Response
         * @throws Exception
         * @action Utils_DataController_actionCreate_
         */
        public function actionCreate(
            int $tableId, int $colId, int $rowId,
        ): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $table  = $this->tables::get($tableId);
            $column = $this->columns::get($colId);
            $row    = $this->rows::get($rowId);
            
            
            if (!$table) {
                return $this->redirect('index');
            }
            
            $model = new Data();
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                //PrintHelper::print('yo');
                return $this->redirect(
                    [
                        '/utils/table/view',
                        'id'       => $table->id,
                        'actionId' => $actionId,
                    ],
                );
            }
            
            return $this->render(
                '@app/views/utils/data/create',
                [
                    'model'    => $model,
                    'table'    => $table,
                    'column'   => $column,
                    'row'      => $row,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Data model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @action DataController_update
         * @throws Exception
         * @action Utils_DataController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $model = $this->repository::get($id);
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(
                    [
                        '/utils/table/view',
                        'id' => $model->table_id,
                    ],
                );
            }
            
            return $this->render(
                '@app/views/utils/data/update',
                [
                    'model'    => $model,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
            return $this->redirect('index');
        }
        
        /**
         * Deletes an existing Data model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Utils_DataController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            try {
                $this->repository::get($id)->delete();
            }
            catch (StaleObjectException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'delete',
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
        
    }
