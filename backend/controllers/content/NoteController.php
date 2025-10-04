<?php
    
    namespace backend\controllers\content;
    
    use core\edit\arrays\Content\NoteEditor;
    use core\edit\entities\Content\Note;
    use core\edit\forms\Content\NoteForm;
    use core\edit\forms\SortForm;
    use core\edit\repositories\Content\NoteRepository;
    use core\edit\search\Content\NoteSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Content\NoteManageService;
    use core\helpers\ParentHelper;
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
    
    class NoteController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE     = Note::TEXT_TYPE;
        private const string MODEL_LABEL = Note::MODEL_LABEL;
        private const string MODEL_PREFIX  = Note::MODEL_PREFIX;
        private const string ACTION_INDEX  = 'Content_NoteController_';
        private const string SERVICE_INDEX = 'NoteManageService_';
        
        private NoteRepository    $repository;
        private NoteManageService $service;
        private NoteEditor $reader;
        
        public function __construct(
            $id,
            $module,
            NoteRepository $repository,
            NoteManageService $service,
            NoteEditor $reader,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
            $this->reader = $reader;
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
                    'modelName' => Note::className(),
                ],
            ];
        }
        
        /**
         * Lists all Note models.
         * @param ?int $id
         * @return Response|string
         * @throws Exception
         * @action Content_NoteController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new NoteSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            
            return $this->render(
                '@app/views/content/note/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'prefix'       => static::MODEL_PREFIX,
                ],
            );
        }
        
        /**
         * @throws Exception
         * @action Content_NoteController_actionResort
         */
        public function actionResort(
            int $siteId,
            int $typeId,
            int $parentId,
        ): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $searchModel  = new NoteSearch();
            $dataProvider = $searchModel->searchNode(
                Yii::$app->request->queryParams,
                $siteId,
                $typeId,
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
                            'index',
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
                '@app/views/content/note/resort',
                [
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'typeId'       => $typeId,
                    'parentId'     => $parentId,
                    'siteId'       => $siteId,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Note model.
         * @param integer $id
         * @return Response|string
         * @action Content_NoteController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель' . $id . ' не найдена!',
                );
                return $this->redirect(
                    [
                        'index',
                    ],
                );
            }
            
            $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
            $parents = $this->reader::getDropDownArrayModels(0);
            
            return $this->render(
                '@app/views/content/note/view',
                [
                    'model'    => $model,
                    'parent'   => $parent,
                    'parents' => $parents,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Note model.
         * @param int|null $textType
         * @param int|null $parentId
         * @return Response|string
         * @action Content_NoteController_actionCreate_
         */
        public function actionCreate(?int $textType = null, ?int $parentId = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form   = new NoteForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            $parent = null;
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $model = $this->service->create($form);
                    
                    if (!$model) {
                        Yii::$app->session->setFlash(
                            'danger',
                            'Проблема с сохранением заметки. Обратитесь к администратору!',
                        );
                        return $this->redirect(['create']);
                    }
                    
                    Yii::$app->session->setFlash('success', 'Заметка успешно создана!');
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
            
            if ($textType && $parentId) {
                $parent = ParentHelper::getModel($textType, $parentId);
            }
            
            return $this->render(
                '@app/views/content/note/create',
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
         * Copy Note model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int      $id
         * @param int|null $limit
         * @param int|null $textType
         * @param int|null $parentId
         * @return string|Response
         * @action Content_NoteController_actionCopy_
         * @throws Throwable
         */
        public function actionCopy(int $id, ?int $textType = null, ?int $parentId = null, ?int $limit = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCopy_';
            $parent   = $this->repository::get($id);
            
            if (!$parent) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена! Копировать нечего.',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            if ($limit < 2) {
                
                $model = $this->service->copyOne($parent);
                
                Yii::$app->session->
                setFlash(
                    'success',
                    'Модель ' . $parent->name . ' скопирована!',
                );
                
                return $this->redirect(
                    [
                        'update',
                        'id' => $model->id,
                    ],
                );
            }
            
            $ids = $this->service->copyMany($parent, $limit, $textType, $parentId);
            
            $notes = $this->reader::getSelectedArray($ids);
            
            if (!$notes) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Заметка  ' . $parent->name . ' не скопирована! ' . $actionId,
                );
            }
            if ($notes) {
                Yii::$app->session->
                setFlash(
                    'success',
                    'Заметка  ' . $parent->name . ' скопирована в ' . $limit . ' экземплярах.',
                );
            }
            return $this->render(
                'copy',
                [
                    'parent'   => $parent,
                    'models'   => $notes,
                    'limit'    => $limit,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Note model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Throwable
         * @action Content_NoteController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $note = $this->repository::get($id);
            
            if (!$note) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Заметка  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new NoteForm($note);
            $form->scenario = $form::SCENARIO_UPDATE_POST;
            
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
                            'id' => $note->id,
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
                '@app/views/content/note/update',
                [
                    'model'    => $form,
                    'note'     => $note,
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
         * @action Content_NoteController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Заметка  #' . $id . ' не найдена!',
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
                    'Заметка успешно удалена!',
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
                             'Кэш заметок очищен!',
                         )
                ;
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
            
        }
        
        
        /**
         * @throws Throwable
         */
        public function actionClearSort(int $id): void
        {
            $this->service->clearCache();
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
            }
            
            $this->service::reorderSort($model);
            
            $this->redirect(
                [
                    'resort',
                    'id' => $id,
                ],
            );
        }
        
        /*####### Active, Draft, Archive #####################################*/
        
        /**
         * @param integer $id
         * @return Response
         * @throws Throwable
         */
        public function actionActivate(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionActivate';
            
            try {
                $this->service->activate($id);
                Yii::$app->session->
                setFlash(
                    'success',
                    'Заметка активирована!',
                );
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
         * @throws Throwable
         */
        public function actionDraft(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDraft';
            
            try {
                $this->service->draft($id);
                Yii::$app->session->
                setFlash(
                    'success',
                    'Заметка отправлена в черновики!',
                );
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
         * @throws Throwable
         */
        public function actionArchive(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionArchive';
            
            try {
                $this->service->archive($id);
                Yii::$app->session->
                setFlash(
                    'success',
                    'Заметка отправлена в архив!',
                );
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
