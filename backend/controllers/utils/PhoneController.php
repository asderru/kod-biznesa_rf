<?php
    
    namespace backend\controllers\utils;
    
    use core\edit\entities\Utils\Phone;
    use core\edit\forms\Utils\PhoneForm;
    use core\edit\repositories\Utils\PhoneRepository;
    use core\edit\search\Utils\PhoneSearch;
    use core\edit\useCases\Utils\PhoneManageService;
    use core\helpers\ModelHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use DomainException;
    use Throwable;
    use Yii;
    use yii\db\Exception;
    use yii\db\StaleObjectException;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\web\Response;
    class PhoneController extends Controller
    {
        
        private const int     TEXT_TYPE    = Phone::TEXT_TYPE;
        private const string  MODEL_LABEL  = Phone::MODEL_LABEL;
        private const string  MODEL_PREFIX = Phone::MODEL_PREFIX;
        private const string  ACTION_INDEX = 'Utils_PhoneController_';
        private const string SERVICE_INDEX = 'PhoneManageService_';
        
        private PhoneRepository    $repository;
        private PhoneManageService $service;
        
        public function __construct(
            $id,
            $module,
            PhoneRepository $repository,
            PhoneManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
        }
        
        /**
         * @inheritDoc
         */
        public function behaviors(): array
        {
            return array_merge(
                parent::behaviors(),
                [
                    'verbs' => [
                        'class'   => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ],
            );
        }
        
        /**
         * Lists all Phones models.
         * @return string
         * @action Utils_PhoneController_actionIndex
         */
        public function actionIndex(): string
        {
            $actionId    = static::ACTION_INDEX . 'actionIndex';
            $searchModel = new PhoneSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);
            
            return $this->render('index', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'actionId' => $actionId,
                'textType' => static::TEXT_TYPE,
                'prefix'   => static::MODEL_PREFIX,
                'label'    => static::MODEL_LABEL,
            ]);
        }
        
        /**
         * Displays a single Phones model.
         * @param int $id ID записи
         * @return string
         * @action Utils_PhoneController_actionView
         */
        public function actionView(int $id): string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            $model = $this->repository->get($id);
            
            return $this->render(
                'view',
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
         * Creates a new Phones model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @param int|null $textType
         * @param int|null $parentId
         * @return string|Response
         * @action Utils_PhoneController_actionCreate_
         */
        public function actionCreate(?int $textType = null, ?int $parentId = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate_';
            
            $parent = null;
            
            if ($textType && $parentId) {
                $parent = ParentHelper::getModel($textType, $parentId);
            }
            
            $form           = new PhoneForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $model = $this->service->create($form);
                    Yii::$app->session->setFlash('success', 'Номер успешно создан!');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                catch (DomainException|Throwable $e) {
                    Yii::$app->session->setFlash('danger', 'Произошла ошибка при создании номера.');
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'create',
                        $e,
                    );
                }
            }
            
            return $this->render(
                'create',
                [
                    'model' => $form,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Phones model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param int $id ID записи
         * @return string|Response
         * @throws Exception if the model cannot be found
         * @action Utils_PhoneController_actionUpdate
         */
        public function actionUpdate(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate_';
            $model = $this->repository->get($id);
            $parent   = ModelHelper::getModel($model->text_type, $model->parent_id);
            
            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
            return $this->render(
                'update',
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
         * Deletes an existing Phones model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param int $id ID записи
         * @return Response
         * @action Utils_PhoneController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            try {
                $this->findModel($id)->delete();
            }
            catch (StaleObjectException|NotFoundHttpException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема c удалением номера',
                    $e,
                );
            
            }
            
            return $this->redirect(['index']);
        }
        
    }
