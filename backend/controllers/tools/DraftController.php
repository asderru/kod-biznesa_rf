<?php
    
    namespace backend\controllers\tools;
    
    use core\edit\entities\Tools\Draft;
    use core\edit\forms\ModelEditForm;
    use core\edit\repositories\Tools\DraftRepository;
    use core\edit\search\Tools\DraftSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Tools\DraftManageService;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use DomainException;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class DraftController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Draft::TEXT_TYPE;
        private const string  MODEL_LABEL   = Draft::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Draft::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Tools_DraftController_';
        private const string  SERVICE_INDEX = 'DraftManageService_';
        
        private DraftRepository    $repository;
        private DraftManageService $service;
        
        public function __construct(
            $id,
            $module,
            DraftRepository $repository,
            DraftManageService $service,
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
        
        /**
         * Lists all Draft models.
         * @param ?int $id
         * @return Response|string
         * @throws Exception
         * @action Tools_DraftController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new DraftSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            $pageSize     = Yii::$app->request->get('pageSize', 50);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            return $this->render(
                '@app/views/tools/draft/index',
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
         * Displays a single Draft model.
         * @param integer $id
         * @return Response|string
         * @action Tools_DraftController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Черновик  #' . $id . ' не найден!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            return $this->render(
                '@app/views/tools/draft/view',
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
         * Displays a single Draft model.
         * @param int|null $textType
         * @param int|null $parentId
         * @return Response|string
         * @throws Throwable
         * @action Tools_DraftController_actionCreate_
         */
        public function actionCreate(?int $textType = null, ?int $parentId = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form = new ModelEditForm();
            $parent = null;
            
            if ($textType && $parentId) {
                $parent = ParentHelper::getModel($textType, $parentId);
            }
            
            if (
                $parent
            ) {
                try {
                    $draft = $this->service->create($parent);
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Черновик успешно создан!',
                    );
                    
                    return
                        $this->redirect(
                            [
                                'view',
                                'id' => $draft->id,
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
                '@app/views/tools/draft/create',
                [
                    'model'    => $form,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Draft model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Throwable
         * @action Tools_DraftController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $draft = $this->repository::get($id);
            
            if (!$draft) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Черновик  #' . $id . ' не найден!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new ModelEditForm($draft);
            
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
                            'id' => $draft->id,
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
            
            $parent = $draft->getParent();
            
            return $this->render(
                '@app/views/tools/draft/update',
                [
                    'model'    => $form,
                    'draft'    => $draft,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Draft model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Tools_DraftController_actionUpdateHtml
         */
        public function actionUpdateHtml(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdateHtml';
            
            $draft = $this->repository::get($id);
            
            if (!$draft) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new ModelEditForm($draft);
            
            $nextId = $draft->getNextModel()?->id;
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->expressEdit($id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    $action = Yii::$app->request->post('action');
                    
                    if ($action === 'view') {
                        return $this->redirect(['view', 'id' => $draft->id]);
                    }
                    return ($nextId)
                        ?
                        $this->redirect(
                            [
                                'update-html',
                                'id' => $nextId,
                            ],
                        )
                        : $this->redirect([
                            'index',
                        ]);
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'service->expressEdit',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/updateHtml',
                [
                    'model'    => $form,
                    'parent'   => $draft,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        
        /**
         * Deletes an existing Draft model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @param int     $textType
         * @return Response
         * @action DraftController_delete
         */
        public function actionModel(int $id, int $textType): Response
        {
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Черновик  #' . $id . ' не найден!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $url = TypeHelper::getLongEditUrl($textType);
            
            return $this->redirect([
                $url . 'create-draft',
                'id' => $id,
            ]);
            
        }
        
        /**
         * Deletes an existing Draft model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @param int     $textType
         * @param int     $parentType
         * @return Response
         * @action DraftController_delete
         */
        public function actionAssign(int $id, int $textType, int $parentType): Response
        {
            $actionId = static::ACTION_INDEX . 'actionAssign';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Черновик  #' . $id . ' не найден!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            try {
                if ($this->service->assign($model->id, $textType, $parentType)) {
                    Yii::$app->session->
                    setFlash(
                        'warning',
                        'Черновик успешно добавлен!',
                    );
                    
                    $url = TypeHelper::getView($textType, $parentType);
                    
                    return $this->redirect($url);
                }
            }
            catch (StaleObjectException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'service->assign',
                    $e,
                );
            }
            
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /**
         * Deletes an existing Draft model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Tools_DraftController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionAssign';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Черновик  #' . $id . ' не найден!',
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
                    'Черновик успешно удален!',
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
        
    }
