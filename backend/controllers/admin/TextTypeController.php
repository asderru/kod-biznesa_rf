<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\entities\Admin\TextType;
    use core\edit\repositories\Admin\StructureRepository;
    use core\edit\repositories\Admin\TextTypeRepository;
    use core\edit\search\Admin\TextTypeSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use core\read\widgets\nestable\NodeMoveAction;
    use core\tools\Constant;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\Exception;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\helpers\ArrayHelper;
    use yii\web\Controller;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class TextTypeController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE    = TextType::TEXT_TYPE;
        private const string  MODEL_LABEL  = TextType::MODEL_LABEL;
        private const string  MODEL_PREFIX = TextType::MODEL_PREFIX;
        private const string  ACTION_INDEX = 'Admin_TextTypeController_';
        
        private TextTypeRepository  $repository;
        private StructureRepository $structureRepository;
        
        public function __construct(
            $id,
            $module,
            TextTypeRepository $repository,
            StructureRepository $structureRepository,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository          = $repository;
            $this->structureRepository = $structureRepository;
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
        
        #[ArrayShape([
            'error'    => 'string[]',
            'nodeMove' => 'string[]',
        ])]
        public function actions(): array
        {
            return [
                'error'    => [
                    'class' => ErrorAction::class,
                ],
                'nodeMove' => [
                    'class'     => NodeMoveAction::class,
                    'modelName' => TextType::className(),
                ],
            ];
        }
        
        /**
         * Lists all TextType models.
         * @return Response|string
         * @action Admin_TextTypeController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new TextTypeSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/admin/text-type/index',
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
         * @action Admin_TextTypeController_actionResort
         */
        
        public function actionResort(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $query = new TextType()::getTree();
            
            //PrintHelper::print($query);
            return $this->render(
                '@app/views/admin/text-type/resort',
                [
                    'query'    => $query,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single TextType model.
         * @param integer $id
         * @return Response|string
         * @action Admin_TextTypeController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/admin/text-type/view',
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
         * Creates a new TextType model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return string|Response
         * @throws Exception
         * @action Admin_TextTypeController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $model = new TextType();
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
            return $this->render(
                '@app/views/admin/text-type/create',
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
         * Updates an existing TextType model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Admin_TextTypeController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
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
            
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->render(
                '@app/views/admin/text-type/update',
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
         * Deletes an existing TextType model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return Response
         * @action Admin_TextTypeController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            $model    = $this->repository::get($id);
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
                $model->delete();
            }
            catch (StaleObjectException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: $model->delete()',
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
        
        /*####### Services ###################################################*/
        
        /**
         * @action Admin_TextTypeController_actionView
         */
        public function actionViewAjax(int $id): string|Response
        {
            return $this->renderAjax(
                '@app/views/admin/text-type/_sort',
                [
                    'model' => $this->repository::get($id),
                ],
            );
        }
        
        public function actionLists(): void
        {
            $models = TextType::find()
                              ->sorted()
                              ->all()
            ;
            
            if (!empty($models)) {
                foreach ($models as $model) {
                    $time   = $model->depth;
                    $indent = ($model->depth !== Constant::STATUS_ROOT
                        ?
                        str_repeat('&nbsp;&nbsp;', $time)
                        .
                        str_repeat('	&middot;', $time) . ' '
                        :
                        ''
                    );
                    echo "<option value='" . $model->id . "'>" . $indent .
                         $model->name . ' (' . $model->id . ')</option>';
                }
            }
            else {
                echo '<option> Создайте корневой тип!</option>';
            }
        }
        
        
        public function actionFindArray(int $siteId, int $textType, ?array $selected = null): array
        {
            $models = $this->structureRepository::getContentModelsArray($textType, ['id', 'name'], $siteId);
            PrintHelper::print($models);
        }
        
        
        public function actionModelsMap(int $siteId, int $textType): array
        {
            $models = self::actionFindArray($siteId, $textType, ['id', 'name']);
            
            return ArrayHelper::map($models, 'id', 'name');
            
        }
        
        
    }
