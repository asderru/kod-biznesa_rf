<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\entities\Admin\SiteMode;
    use core\edit\repositories\Admin\SiteModeRepository;
    use core\edit\search\Admin\SiteModeSearch;
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
    use yii\web\Controller;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class SiteModeController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE    = SiteMode::TEXT_TYPE;
        private const string MODEL_LABEL  = SiteMode::MODEL_LABEL;
        private const string MODEL_PREFIX = SiteMode::MODEL_PREFIX;
        private const string ACTION_INDEX = 'Admin_SiteModeController_';
        
        private SiteModeRepository $repository;
        
        public function __construct(
            $id,
            $module,
            SiteModeRepository $repository,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
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
                    'modelName' => SiteMode::className(),
                ],
            ];
        }
        
        /**
         * Lists all SiteMode models.
         * @return Response|string
         * @action Admin_SiteModeController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new SiteModeSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/admin/site-mode/index',
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
         * @action Admin_SiteModeController_actionResort
         */
        public function actionResort(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            //объект ActiveQuery содержащий данные для дерева. depth = 0 - корень.
            //$query = Information::find()->where(['depth' => '0']);
            $query = SiteMode::find()->where(['depth' => Constant::STATUS_ROOT]);
            
            
            //PrintHelper::print($query);
            return $this->render(
                '@app/views/admin/site-mode/resort',
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
         * Displays a single SiteMode model.
         * @param integer $id
         * @return Response|string
         * @action Admin_SiteModeController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/admin/site-mode/view',
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
         * Creates a new SiteMode model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return string|Response
         * @throws Exception
         * @action Admin_SiteModeController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $model = new SiteMode();
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
            return $this->render(
                '@app/views/admin/site-mode/create',
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
         * Updates an existing SiteMode model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Admin_SiteModeController_actionUpdate
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
                '@app/views/admin/site-mode/update',
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
         * Deletes an existing SiteMode model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return Response
         * @action Admin_SiteModeController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            try {
                $this->repository::get($id)?->delete();
            }
            catch (StaleObjectException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: this->repository::get($id)?->delete()',
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
         * @action Admin_SiteModeController_actionView
         */
        public function actionViewAjax(int $id): string|Response
        {
            return $this->renderAjax(
                '@app/views/admin/site-mode/_sort',
                [
                    'model' => $this->repository::get($id),
                ],
            );
        }
        
        public function actionLists(): void
        {
            $models = SiteMode::find()
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
        
        
    }
