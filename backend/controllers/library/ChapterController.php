<?php
    
    namespace backend\controllers\library;
    
    use backend\helpers\ImageHelper;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\forms\Library\ChapterForm;
    use core\edit\forms\ModelEditForm;
    use core\edit\forms\SlugEditForm;
    use core\edit\forms\SortForm;
    use core\edit\forms\Tools\LinkForm;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\repositories\Library\BookRepository;
    use core\edit\repositories\Library\ChapterRepository;
    use core\edit\repositories\Tools\DraftRepository;
    use core\edit\search\Library\ChapterSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Library\CalculateService;
    use core\edit\useCases\Library\ChapterManageService;
    use core\edit\useCases\Photo\ImageProcessorService;
    use core\edit\useCases\Tools\DraftManageService;
    use core\helpers\ClearHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\edit\arrays\Admin\InformationEditor;
    use core\edit\arrays\Library\BookEditor;
    use core\tools\Constant;
    use DomainException;
    use Exception;
    use himiklab\sortablegrid\SortableGridAction;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    /**
     * ChapterController implements the CRUD actions for Chapter model.
     * @property-read array $roots
     */
    class ChapterController  extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Chapter::TEXT_TYPE;
        private const string  MODEL_LABEL   = Chapter::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Chapter::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Library_ChapterController_';
        private const string  SERVICE_INDEX = 'ChapterManageService_';
        
        private ChapterRepository     $repository;
        private BookRepository        $books;
        private ChapterManageService  $service;
        private CalculateService      $calculateService;
        private ImageProcessorService $imageService;
        private DraftRepository       $drafts;
        private DraftManageService    $draftService;
        
        public function __construct(
            $id,
            $module,
            BookRepository $books,
            ChapterRepository $repository,
            ChapterManageService $service,
            CalculateService $calculateService,
            ImageProcessorService $imageService,
            DraftRepository $drafts,
            DraftManageService $draftService,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->books            = $books;
            $this->repository       = $repository;
            $this->service          = $service;
            $this->calculateService = $calculateService;
            $this->imageService     = $imageService;
            $this->drafts           = $drafts;
            $this->draftService     = $draftService;
        }
        
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
                'sort' => [
                    'class'     => SortableGridAction::className(),
                    'modelName' => Chapter::className(),
                ],
            ];
        }
        
        /**
         * Lists all Chapter models.
         * @param ?int $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         * @action Library_ChapterController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            $book     = null;
            if ($id) {
                $book = $this->books::get($id);
            }
            $sites        = InformationEditor::getArray(['id', 'name']);
            $roots        = $this->repository::getRoots();
            $books        = BookReader::getArray(array_merge(Book::DEFAULT_FIELDS, ['description']));
            $searchModel  = new ChapterSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $book?->site_id,
                null,
                Constant::STATUS_DRAFT,
            );
            $pageSize     = Yii::$app->request->get('pageSize', 50);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            return $this->render(
                '@app/views/library/chapter/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'sites'        => $sites,
                    'roots'        => $roots,
                    'book'         => $book,
                    'books'        => $books,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Exception
         * @action Library_ChapterController_actionResort
         */
        public function actionResort(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            $book     = $this->books::get($id);
            
            if (!$book) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Книга не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $books = Book::find()
                         ->noRoots()
                         ->active()
                         ->all()
            ;
            
            $searchModel  = new ChapterSearch();
            $dataProvider = $searchModel->searchByBook(
                Yii::$app->request->queryParams,
                $id,
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
                            'id' => $book->id,
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
                '@app/views/library/chapter/resort',
                [
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'books'        => $books,
                    'book'         => $book,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Chapter model.
         * @param integer $id
         * @return Response|string
         * @throws Exception|Throwable
         * @action Library_ChapterController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            $model    = $this->repository::get($id);
            
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
            
            $root      = $this->repository::getRoot($model->site_id);
            $book      = $model->book ?? $root;
            $editItems = $model->getEditItems()->all();
            
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
                '@app/views/library/chapter/view',
                [
                    'model'      => $model,
                    'book'       => $book,
                    'editItems'  => $editItems,
                    'uploadForm' => $uploadForm,
                    'actionId'   => $actionId,
                    'textType'   => static::TEXT_TYPE,
                    'prefix'     => static::MODEL_PREFIX,
                    'label'      => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new Chapter model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param ?int $id
         * @return string|Response
         * @throws Throwable
         * @throws Exception
         * @action Library_ChapterController_actionCreate_
         */
        public function actionCreate(?int $id = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            // 1. Проверка наличия книг
            if ($id === null && !$this->service->hasAvailableBooks()) {
                $this->addFlash('warning', 'Надо создать книгу!');
                return $this->redirect(['/library/book/create']);
            }
            
            // 2. Проверка существования книги
            $book = $id !== null ? $this->books::get($id) : null;
            if ($id !== null && $book === null) {
                $this->addFlash('warning', 'Такой книги нет');
                return $this->redirect(['index']); // Добавить редирект
            }
            
            // 3. Создание и валидация формы
            $form           = new ChapterForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $model = $this->service->create($form);
                    self::createTime($model, static::TEXT_TYPE);
                    $this->addFlash('success', 'Глава успешно создана!');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                catch (DomainException $e) {
                    PrintHelper::exception($actionId, 'Проблема: ' . static::SERVICE_INDEX . 'create', $e);
                    $this->addFlash('danger', 'Произошла ошибка при создании главы');
                }
            }
            
            // 4. Рендер формы
            return $this->render(
                '@app/views/library/chapter/create', [
                'model'    => $form,
                'isAlone'  => ParametrHelper::isAlone(),
                'book'     => $book,
                'actionId' => $actionId,
                'textType' => static::TEXT_TYPE,
                'prefix'   => static::MODEL_PREFIX,
                'label'    => static::MODEL_LABEL,
            ],
            );
        }
        
        /**
         * Creates a new Chapter model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int $id
         * @return string|Response
         * @throws Throwable
         * @throws Exception
         * @action ChapterController_actionCreateFromDraft
         */
        public function actionCreateFromDraft(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreateFromDraft';
            
            $draft = $this->drafts::get($id);
            
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
            
            $form = new ChapterForm();
            
            if (
                $form->load(Yii::$app->request->post())
            ) {
                try {
                    $model = $this->service->create($form);
                    
                    if (!$model) {
                        Yii::$app->session->
                        setFlash(
                            'warning',
                            'Глава  из черновика #' . $id . ' не создана!',
                        );
                        return $this->redirect([
                            'index',
                        ]);
                    }
                    
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Глава успешно создана!',
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
                '@app/views/library/chapter/create-draft',
                [
                    'model'    => $form,
                    'draft'    => $draft,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Throwable
         */
        public function actionCopyToDraft(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCopyToDraft';
            
            $chapter = $this->repository::get($id);
            
            if (!$chapter) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Глава  #' . $id . ' не найдена! ' . $actionId,
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $draft = $this->draftService->create($chapter);
            Yii::$app->session->
            setFlash(
                'success',
                'Черновик успешно создан!',
            );
            return $this->redirect(
                [
                    '/tools/draft/view',
                    'id' => $draft->id,
                ],
            );
        }
        
        /**
         * Creates a new Chapter model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param ?int $id
         * @return string|Response
         * @throws Throwable
         * @throws Exception
         * @action ChapterController_copy
         */
        public function actionCopy(
            ?int $id = null,
        ): string|Response
        {
            $parent = $this->repository::get($id);
            
            if (!$parent) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $model = $this->service->copy($parent);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  ' . $parent->name . ' не скопирована!',
                );
                
                return $this->redirect([
                    'view',
                    'id' => $id,
                ]);
            }
            
            return $this->redirect(
                
                [
                    'update',
                    'id' => $model->id,
                ],
            );
        }
        
        /**
         * Updates an existing Chapter model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Library_ChapterController_actionUpdate_
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate_';
            
            $chapter = $this->repository::get($id);
            
            if (!$chapter) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            if ($chapter->status === Constant::STATUS_ROOT) {
                return $this->redirect([
                    'update-root',
                ]);
            }
            
            $book           = $this->books::get($chapter->book_id);
            $form           = new ChapterForm($chapter);
            $form->scenario = $form::SCENARIO_UPDATE_POST;
            
            $lexemesBook = ($book) ? $this->calculateService->calculate($book) : null;
            $lexemes     = $this->calculateService->calculateAll();
            
            if (
                !$form->load(Yii::$app->request->post())
            ) {
                self::startEditTime(
                    $chapter,
                    static::TEXT_TYPE,
                );
            }
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->edit($id, $form);
                    self::finishEditTime(static::TEXT_TYPE, $chapter->id, $form->text);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $chapter->id,
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
                '@app/views/library/chapter/update',
                [
                    'model'       => $form,
                    'chapter'     => $chapter,
                    'lexemes'     => $lexemes,
                    'lexemesBook' => $lexemesBook,
                    'actionId'    => $actionId,
                    'textType'    => static::TEXT_TYPE,
                    'prefix'      => static::MODEL_PREFIX,
                    'label'       => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Chapter model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Library_ChapterController_actionUpdateHtml
         */
        public function actionUpdateHtml(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdateHtml';
            
            $chapter = $this->repository::get($id);
            
            if (!$chapter) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new ModelEditForm($chapter);
            
            $nextId = $chapter->getNextModel()?->id;
            
            if (
                !$form->load(Yii::$app->request->post())
            ) {
                self::startEditTime(
                    $chapter,
                    static::TEXT_TYPE,
                );
            }
            
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->expressEdit($id, $form);
                    self::finishEditTime(static::TEXT_TYPE, $chapter->id, $form->text);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    
                    $action = Yii::$app->request->post('action');
                    
                    if ($action === 'view') {
                        return $this->redirect(['view', 'id' => $chapter->id]);
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
                catch
                (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'edit_',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/updateHtml',
                [
                    'model'    => $form,
                    'parent'   => $chapter,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        
        /**
         * @action Library_ChapterController_actionUpdateSlug
         */
        public function actionUpdateSlug(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdateSlug';
            
            $chapter = $this->repository::get($id);
            
            if (!$chapter) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new SlugEditForm($chapter);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->editSlug($id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(['view', 'id' => $id]);
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'editSlug',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/updateSlug',
                [
                    'model'    => $form,
                    'parent'   => $chapter,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * @param integer $id
         * @return string|Response
         * @throws Exception|Throwable
         * @action Library_ChapterController_actionSeolink
         */
        public function actionSeolink(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionSeolink';
            
            $chapter = $this->repository::get($id);
            
            if (!$chapter) {
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new LinkForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->setSeolink($id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $chapter->id,
                        ],
                    );
                }
                catch (DomainException|Exception $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'setSeolink',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/_editSeoLink.php',
                [
                    'model'    => $form,
                    'parent'   => $chapter,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Updates an existing Chapter model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @return array
         */
        public function actionCalculate(): array
        {
            return $this->calculateService->calculateAll();
        }
        
        /**
         * Updates an existing Chapter model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         */
        public function actionBing(
            int $id,
        ): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionBing';
            
            $chapter = $this->repository::get($id);
            
            if (!$chapter) {
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
                $this->service->bing($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'info',
                             'Index добавлен!',
                         )
                ;
            }
            catch (DomainException|Exception|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'bing',
                    $e,
                );
            }
            
            return $this->redirect(
                [
                    'view',
                    'id' => $chapter->id,
                ],
            );
        }
        
        /**
         * Deletes an existing Chapter model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Library_ChapterController_actionDelete
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
                return $this->redirect([
                    'index',
                ]);
            }
            
            if (!$model->isRoot()) {
                
                try {
                    $this->service->remove($model->id);
                    Yii::$app->session->
                    setFlash(
                        'warning',
                        'Текст успешно удален!',
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
            Yii::$app->getSession()
                     ->setFlash(
                         'warning',
                         'Корневой текст удалить нельзя!',
                     )
            ;
            return $this->redirect(
                (ClearHelper::getAction() !== 'view')
                    ?
                    Yii::$app->request->referrer
                    :
                    'index',
            );
        }
        
        /**
         * Updates Content model
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return Response
         * @action Seo_FaqController_actionContent_
         */
        public function actionContent(
            int $id,
        ): Response
        {
            $actionId = static::ACTION_INDEX . 'actionContent_';
            
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
                $this->service->copyContent($model);
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Контент успешно скопирован!',
                );
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'copyContent',
                    $e,
                );
            }
            return $this->redirect(
                [
                    'view',
                    'id' => $id,
                ],
            );
        }
        
        /*
         * @action Seo_FaqController_actionContents
         */
        public function actionContents(): Response
        {
            $actionId = static::ACTION_INDEX . 'actionContents';
            
            $models = Chapter::find()
                             ->noRoots()
                             ->thisSite()
                             ->all()
            ;
            
            if (!$models) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модели не найдены!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            try {
                foreach ($models as $model) {
                    $this->service->copyContent($model);
                }
                
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Контент успешно скопирован!',
                );
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'copyContent',
                    $e,
                );
            }
            return $this->redirect(
                [
                    'index',
                ],
            );
        }
        
        /**
         * @throws Throwable
         */
        public function actionClearCache(): Response|string
        {
            if ($this->service->clearCache()) {
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Кэш текстов очищен!',
                         )
                ;
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
            
        }
        
        /**
         * @action ChapterController_clearSort
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
        
        /*####### Photo ######################################################*/
        
        /**
         * @throws Exception
         */
        public function actionDeletePhoto(
            int $id,
        ): string|Response
        {
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
            
            (new UploadPhotoForm)::deletePhoto($model);
            $model->deletePhotos($model->slug);
            
            return $this->redirect(
                [
                    'view',
                    'id' => $id,
                ],
            );
        }
        
        /*############ Activation ############################################*/
        
        /**
         * @throws Throwable
         */
        public function status(
            int $id, int $statusId,
        ): void
        {
            $model = $this->repository::get($id);
            if (!$model) {
                throw new DomainException('Модель не найдена!');
            }
            
            $this->transaction->wrap(function () use ($model, $statusId) {
                $model->status($statusId);
                $this->repository::save($model);
            });
        }
        
        /**
         * @param integer $id
         * @return Response
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
        
        /*###### Roots #######################################################*/
        
        
        /**
         * Updates an existing Chapter model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param ?int $id
         * @return string|Response
         * @throws Exception
         */
        public function actionUpdateRoot(
            ?int $id = null,
        ): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdateRoot';
            
            $root = $this->repository::getRoot($id);
            $form = new ModelEditForm($root);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->editRoot($root->site_id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $root->id,
                        ],
                    );
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'editRoot',
                        $e,
                    );
                }
            }
            return $this->render(
                '@app/views/layouts/templates/updateRoot',
                [
                    'root'     => $root,
                    'model'    => $form,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        
        /**
         * @throws Throwable
         */
        public function actionGetModel($id): array
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $model = Chapter::findOne($id);
            
            if (!$model) {
                return ['error' => 'Chapter not found'];
            }
            return [
                'id'          => $model->id,
                'image'       => $model->getImageUrl(6),
                'name'        => $model->name,
                'title'       => $model->title,
                'description' => FormatHelper::asHtml($model->description),
                'text'        => FormatHelper::asHtml($model->text),
                'status'      => StatusHelper::statusBadgeLabel($model->status),
                'editUrl'     => Url::to(['/library/chapter/view', 'id' => $model->id]),
            ];
        }
    }
