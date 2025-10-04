<?php
    
    namespace backend\controllers\content;
    
    use core\edit\entities\Content\Review;
    use core\edit\forms\Content\ReviewForm;
    use core\edit\forms\SortForm;
    use core\edit\forms\TimeForm;
    use core\edit\repositories\Content\ReviewRepository;
    use core\edit\search\Content\ReviewSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Content\ReviewManageService;
    use core\helpers\ClearHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use DomainException;
    use Exception;
    use himiklab\sortablegrid\SortableGridAction;
    use JetBrains\PhpStorm\ArrayShape;
    use JetBrains\PhpStorm\Pure;
    use Throwable;
    use Yii;
    use yii\caching\DbDependency;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class ReviewController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE     = Review::TEXT_TYPE;
        private const string MODEL_LABEL = Review::MODEL_LABEL;
        private const string MODEL_PREFIX  = Review::MODEL_PREFIX;
        private const string ACTION_INDEX  = 'Content_Review_Controller_';
        private const string SERVICE_INDEX = 'ReviewManageService_';
        
        private ReviewRepository    $repository;
        private ReviewManageService $service;
        
        public function __construct(
            $id,
            $module,
            ReviewRepository $repository,
            ReviewManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
        }
        
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
                    'modelName' => Review::className(),
                ],
            ];
        }
        
        /**
         * Lists all Review models.
         * @param int|null $id
         * @return Response|string
         * @throws Throwable
         * @action Content_ReviewController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            $sites    = ParametrHelper::getSites();
            
            $searchModel = new ReviewSearch();
            
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            
            $pageSize = Yii::$app->request->get('pageSize', 50);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            return $this->render(
                '@app/views/content/review/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'sites'        => $sites,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Exception|Throwable
         * @action Content_ReviewController_actionResort
         */
        public function actionResort(
            ?int $textType = null,
            ?int $id = null,
        ): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $parent = ($textType && $id) ? ParentHelper::getModel($textType, $id) : null;
            
            if (!$parent) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Сортировка не возможна!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $searchModel = new ReviewSearch();
            $dependency  = new DbDependency(
                [
                    'sql' => 'SELECT MAX(updated_at) FROM content_reviews',
                ],
            );
            
            $dataProvider = Yii::$app->db->cache(function () use ($searchModel, $id) {
                
                $dataProvider = $searchModel->search(
                    Yii::$app->request->queryParams,
                    $id,
                );
                $dataProvider->prepare();
                return $dataProvider;
            },
                3600 * 24,
                $dependency,
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
                            'textType' => $parent::TEXT_TYPE,
                            'id'       => $parent->id,
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
                '@app/views/content/review/resort',
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
         * Displays a single Review model.
         * @param integer $id
         * @return Response|string
         * @action Content_ReviewController_actionView
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
            
            $parent = ($model->text_type && $model->parent_id) ?
                ParentHelper::getModel(
                    $model->text_type,
                    $model->parent_id,
                )
                : null;
            
            return $this->render(
                '@app/views/content/review/view',
                [
                    'model'    => $model,
                    'parent'   => $parent ?? null,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new Review model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int|null $textType
         * @param int|null $parentId
         * @return string|Response
         * @action Content_ReviewController_actionCreate_
         */
        public function actionCreate(?int $textType = null, ?int $parentId = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form   = new ReviewForm();
            $parent = null;
            
            if ($textType && $parentId) {
                $parent = ParentHelper::getModel($textType, $parentId);
            }
            else {
                Yii::$app->session->setFlash('warning', 'Тема не указана. Укажите тему обзора обязательно!');
            }
            
            if ($form->load(Yii::$app->request->post())) {
                try {
                    $model = $this->service->create($form);
                    
                    if (!$model) {
                        Yii::$app->session->setFlash(
                            'danger',
                            'Проблема с сохранением обзора. Обратитесь к администратору!',
                        );
                        return $this->redirect(['create']);
                    }
                    
                    Yii::$app->session->setFlash('success', 'Обзор успешно создан!');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                catch (DomainException|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'create',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/content/review/create',
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
         * Updates an existing Review model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Content_ReviewController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $review = $this->repository::get($id);
            
            if (!$review) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new ReviewForm($review);
            
            $parent = ($review->text_type && $review->parent_id) ?
                ParentHelper::getModel(
                    $review->text_type,
                    $review->parent_id,
                )
                : null;
            
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
                            'id' => $review->id,
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
                '@app/views/content/review/update',
                [
                    'model'    => $form,
                    'review'   => $review,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Review model.
         * @param integer $id
         * @return Response|string
         * @action ReviewController_view
         */
        public function actionTime(
            int $id,
        ): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionTime';
            
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
            
            $timeForm = new TimeForm();
            if (
                $timeForm->load(Yii::$app->request->post())
                && $timeForm->validate()
            ) {
                try {
                    $this->service->changeTime($model->id, $timeForm);
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ],
                    );
                }
                catch (DomainException|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'changeTime',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/content/review/time',
                [
                    'model'    => $model,
                    'timeForm' => $timeForm,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Deletes an existing Review model.
         * If deletion is successful, the browser will be redirected to the
         * '@app/views/content/review/index' page.
         * @param integer $id
         * @return Response
         * @action Content_ReviewController_actionDelete
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
                    'Обзор успешно удален!',
                );
            }
            catch (DomainException|StaleObjectException|Throwable $e) {
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
         * Displays a single Post model.
         * @return Response|string
         * @throws Throwable
         * @action PostController_view
         */
        public function actionClearCache(): Response|string
        {
            if ($this->service->clearCache()) {
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Кэш обзоров очищен!',
                         )
                ;
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
            
        }
        
        /**
         * @action ReviewController_clearSort
         * @throws Throwable
         */
        public function actionClearSort(
            int $id,
        ): void
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
        
        /*############ Activation ############################################*/
        
        /**
         * @param integer $id
         * @return Response
         * @action ReviewController_activate
         * @throws Throwable
         */
        public function actionActivate(
            int $id,
        ): Response
        {
            $actionId = static::ACTION_INDEX . 'actionActivate';
            
            try {
                $this->service->activate($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Активация проведена успешно!',
                         )
                ;
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'activate',
                    $e,
                );
            }
            
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /**
         * @param integer $id
         * @return mixed
         * @action ReviewController_draft
         * @throws Throwable
         */
        public function actionDraft(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDraft';
            
            try {
                $this->service->draft($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'warning',
                             'Отправлен в черновики!',
                         )
                ;
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'draft',
                    $e,
                );
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /**
         * @param integer $id
         * @return mixed
         * @action ReviewController_archive
         * @throws Throwable
         */
        public function actionArchive(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionArchive';
            
            try {
                $this->service->archive($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'warning',
                             'Отправлен в архив!',
                         )
                ;
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'archive',
                    $e,
                );
                
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
    }
