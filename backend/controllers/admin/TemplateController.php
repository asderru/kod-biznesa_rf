<?php
    
    namespace backend\controllers\admin;
    
    use backend\helpers\ImageHelper;
    use core\edit\entities\Admin\Template;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\Admin\TemplateForm;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\repositories\Admin\TemplateRepository;
    use core\edit\search\Admin\TemplateSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Admin\TemplateManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use core\read\widgets\nestable\NodeMoveAction;
    use core\tools\Constant;
    use DomainException;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\Exception;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    class TemplateController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE    = Template::TEXT_TYPE;
        private const string MODEL_LABEL  = Template::MODEL_LABEL;
        private const string MODEL_PREFIX = Template::MODEL_PREFIX;
        private const string ACTION_INDEX = 'Admin_TemplateController_';
        private const string  SERVICE_INDEX = 'TemplateManageService_';
        
        private TemplateRepository $repository;
        private TemplateManageService $service;
        
        public function __construct(
            $id,
            $module,
            TemplateRepository $repository,
            TemplateManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service = $service;
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
        
        public function actions(): array
        {
            return [
                'nodeMove' => [
                    'class'     => NodeMoveAction::class,
                    'modelName' => Template::className(),
                ],
            ];
        }
        
        /**
         * Lists all Template models.
         * @return Response|string
         * @action Admin_TemplateController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new TemplateSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/admin/template/index',
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
         * @action Shop_TemplateController_actionResort
         */
        public function actionResort(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            //объект ActiveQuery содержащий данные для дерева. depth = 0 - корень.
            $query = (new Template)::getTree();
            
            return $this->render(
                '@app/views/admin/template/resort',
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
         * Displays a single Template model.
         * @param integer $id
         * @return Response|string
         * @action Admin_TemplateController_actionView
         * @throws Throwable
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            $model = $this->repository::get($id);
            
            $uploadForm = new UploadPhotoForm();
            
            if (Yii::$app->request->isPost) {
                ImageHelper::createWatermark($model->site_id);
                $uploadForm->imageFile = UploadedFile::getInstance($uploadForm, 'imageFile');
                if ($uploadForm->upload($model, static::TEXT_TYPE)) {
                    $this->imageService->convertToWeb($model, static::TEXT_TYPE);
                    // file is uploaded successfully
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ],
                    );
                }
            }
            
            return $this->render(
                '@app/views/admin/template/view',
                [
                    'model'      => $model,
                    'uploadForm' => $uploadForm,
                    'actionId'   => $actionId,
                    'textType'   => static::TEXT_TYPE,
                    'prefix'     => static::MODEL_PREFIX,
                    'label'      => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new Template model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @throws Exception|Throwable
         * @action Admin_TemplateController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form = new TemplateForm();
            
            // Если валидация прошла успешно
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $model = $this->service->create($form);
                    
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Раздел успешно создан!',
                    );
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
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
                '@app/views/admin/template/create',
                [
                    'model' => $form,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        
        /**
         * Updates an existing Razdel model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception|Throwable
         * @action Admin_TemplateController_actionUpdate_
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate_';
            
            $template = $this->repository::get($id);
            
            if (!$template) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            if ($template->status === Constant::STATUS_ROOT) {
                return $this->redirect(
                    [
                        'update-root',
                    ],
                );
            }
            
            $form = new TemplateForm($template);
            
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
                            'id' => $template->id,
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
                '@app/views/admin/template/update',
                [
                    'model'    => $form,
                    'template' => $template,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        
        /**
         * Deletes an existing Template model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Admin_TemplateController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            if ($model) {
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
         * Finds the Template model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return null|Template the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        /*####### Services ###################################################*/
        
        /**
         * @param integer $id
         * @return string|Response
         * @action Shop_RazdelController_actionViewAjax
         */
        public function actionViewAjax(int $id): string|Response
        {
            return $this->renderAjax(
                '@app/views/admin/template/_sort',
                [
                    'model' => $this->repository::get($id),
                ],
            );
        }
        
        /**
         * @param int     $status
         * @param integer $id
         * @action Shop_RazdelController_actionLists
         */
        public function actionLists(int $status, int $id): void
        {
            $models = Razdel::find()
                            ->andWhere(
                                [
                                    '=',
                                    'site_id',
                                    $id,
                                ],
                            )
                            ->andWhere(
                                [
                                    '>=',
                                    'status',
                                    $status,
                                ],
                            )
                            ->sorted()
                            ->all()
            ;
            
            if (!empty($models)) {
                foreach ($models as $model) {
                    $time   = $model->depth - $status;
                    $indent = ($model->depth !== $status)
                        ?
                        str_repeat('&nbsp;', $time)
                        .
                        str_repeat('&middot;', $time) . ' '
                        :
                        '';
                    
                    echo "<option value='" . $model->id . "'>" . $indent .
                         $model->name . ' (' . $model->id . ')</option>';
                }
            }
            else {
                echo '<option> На этом сайте разделов нет! Создайте корневой раздел!</option>';
            }
        }
        
    }
