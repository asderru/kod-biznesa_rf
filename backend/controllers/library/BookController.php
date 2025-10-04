<?php
    
    namespace backend\controllers\library;
    
    use backend\helpers\ImageHelper;
    use backend\helpers\StatusHelper;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Library\Book;
    use core\edit\forms\ColorForm;
    use core\edit\forms\Library\BookForm;
    use core\edit\forms\ModelEditForm;
    use core\edit\forms\SlugEditForm;
    use core\edit\forms\Tools\LinkForm;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\repositories\Library\BookRepository;
    use core\edit\repositories\Tools\DraftRepository;
    use core\edit\search\Library\BookSearch;
    use core\edit\search\Library\ChapterSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Library\BookManageService;
    use core\edit\useCases\Photo\ImageProcessorService;
    use core\edit\useCases\Tools\DraftManageService;
    use core\helpers\ClearHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\edit\arrays\Admin\InformationEditor;
    use core\edit\arrays\Library\BookEditor;
    use core\read\widgets\nestable\NodeMoveAction;
    use core\tools\Constant;
    use DomainException;
    use Exception;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    /**
     * Library_BookController implements the CRUD actions for Book model.
     * @property-read array $roots
     */
    class BookController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Book::TEXT_TYPE;
        private const string  MODEL_LABEL   = Book::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Book::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Library_BookController_';
        private const string  SERVICE_INDEX = 'BookManageService_';
        
        private BookRepository        $repository;
        private BookManageService     $service;
        private ImageProcessorService $imageService;
        private DraftRepository       $drafts;
        private DraftManageService    $draftService;
        
        public function __construct(
            $id,
            $module,
            BookRepository $repository,
            BookManageService $service,
            ImageProcessorService $imageService,
            DraftRepository $drafts,
            DraftManageService $draftService,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository   = $repository;
            $this->service      = $service;
            $this->imageService = $imageService;
            $this->drafts       = $drafts;
            $this->draftService = $draftService;
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
                'nodeMove' => [
                    'class'     => NodeMoveAction::class,
                    'modelName' => Book::className(),
                ],
            ];
        }
        
        /**
         * Lists all Book models.
         * @param ?int $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         * @action Library_BookController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $sites = InformationEditor::getArray(['id', 'name']);
            $roots        = $this->repository::getRoots();
            $searchModel  = new BookSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
                null,
                Constant::STATUS_DRAFT,
            );
            $pageSize     = Yii::$app->request->get('pageSize', 50);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            return $this->render(
                '@app/views/library/book/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'roots'        => $roots,
                    'sites'        => $sites,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @action Library_BookController_actionResort
         */
        public function actionResort(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $site = ParametrHelper::getSite($id);
            
            if (!$site) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Сайт  #' . $id . ' не найден!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $sites = InformationEditor::getArray(['id', 'name']);
            
            $array       = Book::DEFAULT_FIELDS;
            $arrayModels = BookReader::getArray(array_merge($array, ['description']), null, $id);
            
            //PrintHelper::print($arrayModels);
            //объект ActiveQuery содержащий данные для дерева. depth = 0 - корень.
            $query = (new Book)::getTree($id);
            
            return $this->render(
                '@app/views/layouts/sort/resort',
                [
                    'query'       => $query,
                    'site'        => $site,
                    'sites'       => $sites,
                    'arrayModels' => $arrayModels,
                    'actionId'    => $actionId,
                    'textType'    => static::TEXT_TYPE,
                    'prefix'      => static::MODEL_PREFIX,
                    'label'       => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Book model.
         * @param integer $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         * @action Library_BookController_actionView
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
            
            $parents    = $model->parents()->all();
            $editItems  = $model->getEditItems()->all();
            $searchModel  = new ChapterSearch();
            $dataProvider = $searchModel->searchByBook(
                Yii::$app->request->queryParams,
                $model->id,
            );
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
                "@app/views/library/book/view",
                [
                    'model'        => $model,
                    'editItems'    => $editItems,
                    'parents'      => $parents,
                    'uploadForm'   => $uploadForm,
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
         * Creates a new Book model.
         * If creation is successful, the browser will be redirected to the
         * 'view' book.
         * @return string|Response
         * @throws Throwable
         * @throws \yii\base\Exception
         * @throws Exception
         * @action Library_BookController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate_';
            $site = ParametrHelper::getSite();
            $isAlone = ($site->children()->all() === []);
            
            if (!Author::find()->exists()) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Создайте автора!',
                );
                return $this->redirect(
                    ['/library/author/create',],
                );
            }
            
            $form           = new BookForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $model = $this->service->create($form);
                    self::createTime(
                        $model,
                        static::TEXT_TYPE,
                    );
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Том успешно создан!',
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
                '@app/views/library/book/create',
                [
                    'model'    => $form,
                    'site'     => $site,
                    'isAlone'  => $isAlone,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new Book model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int $id
         * @return string|Response
         * @throws Throwable
         * @throws Exception
         * @action Library_BookController_actionCreateFromDraft
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
            
            $form = new BookForm();
            
            if (
                $form->load(Yii::$app->request->post())
            ) {
                try {
                    $model = $this->service->create($form);
                    
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Том успешно создан!',
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
                '@app/views/library/book/create-draft',
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
            
            $book = $this->repository::get($id);
            
            if (!$book) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Том  #' . $id . ' не найден! ' . $actionId,
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $draft = $this->draftService->create($book);
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
         * Creates a new Book model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param ?int $id
         * @return string|Response
         * @throws Throwable
         * @throws Exception
         * @action Library_BookController_actionCopy
         */
        public function actionCopy(?int $id = null): string|Response
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
         * Updates an existing Book model.
         * If update is successful, the browser will be redirected to the
         * 'view' book.
         * @param integer $id
         * @return string|Response
         * @throws Exception|Throwable
         * @action Library_BookController_actionUpdate_
         */
        public function actionUpdate(
            int $id,
        ): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate_';
            
            $book = $this->repository::get($id);
            
            if (!$book) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            if ($book->status === Constant::STATUS_ROOT) {
                return $this->redirect(
                    [
                        'update-root',
                    ],
                );
            }
            
            $form           = new BookForm($book);
            $form->scenario = $form::SCENARIO_UPDATE_POST;
            
            if (
                !$form->load(Yii::$app->request->post())
            ) {
                self::startEditTime(
                    $book,
                    static::TEXT_TYPE,
                );
            }
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->edit($book->id, $form);
                    self::finishEditTime(static::TEXT_TYPE, $book->id, $form->text);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id'       => $book->id,
                            'actionId' => $actionId,
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
                '@app/views/library/book/update',
                [
                    'model'    => $form,
                    'book'     => $book,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Updates an existing Book model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Library_BookController_actionUpdateHtml
         */
        public function actionUpdateHtml(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdateHtml';
            
            $book = $this->repository::get($id);
            
            if (!$book) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new ModelEditForm($book);
            
            if (
                !$form->load(Yii::$app->request->post())
            ) {
                self::startEditTime(
                    $book,
                    static::TEXT_TYPE,
                );
            }
            
            $nextId = $book->getNextModel()?->id;
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->expressEdit($id, $form);
                    self::finishEditTime(static::TEXT_TYPE, $book->id, $form->text);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    $action = Yii::$app->request->post('action');
                    
                    if ($action === 'view') {
                        return $this->redirect(['view', 'id' => $book->id]);
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
                        'Проблема: ' . static::SERVICE_INDEX . 'edit_',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/updateHtml',
                [
                    'model'    => $form,
                    'parent'   => $book,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        
        /**
         * @action Library_BookController_actionUpdateSlug
         */
        public function actionUpdateSlug(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdateSlug';
            
            $book = $this->repository::get($id);
            
            if (!$book) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new SlugEditForm($book);
            
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
                    'parent'   => $book,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Updates an existing Book model.
         * If update is successful, the browser will be redirected to the
         * 'view' book.
         * @param integer $id
         * @return string|Response
         * @throws Exception|Throwable
         * @action Library_BookController_actionColor
         */
        public function actionColor(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionColor';
            
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
            
            $form = new ColorForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->color($parent->id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Цветовой профиль изменен!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id'       => $parent->id,
                            'actionId' => $actionId,
                        ],
                    );
                }
                catch (DomainException|Exception $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'color',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/color',
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
         * @param integer $id
         * @return string|Response
         * @throws Exception|Throwable
         * @action Library_BookController_actionSeolink
         */
        public function actionSeolink(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionSeolink';
            
            $book = $this->repository::get($id);
            
            if (!$book) {
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
                            'id' => $book->id,
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
                    'parent'   => $book,
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
         * @throws Exception
         */
        public function actionBing(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionBing';
            
            $book = $this->repository::get($id);
            
            if (!$book) {
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
                    'id' => $book->id,
                ],
            );
        }
        
        /**
         * Deletes an existing Book model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' book.
         * @param integer $id
         * @return Response
         * @throws Throwable
         * @action Library_BookController_actionDelete
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
                $this->actionDeletePhoto($model->id);
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'this->actionDeletePhoto',
                    $e,
                );
            }
            
            try {
                $this->service->remove($model->id);
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Страница успешно удалена!',
                );
            }
            catch (DomainException|StaleObjectException $e) {
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
        
        /**
         * Updates Content model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return Response
         * @action Library_BookController_actionContent_
         */
        public function actionContent(int $id): Response
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
        
        /**
         * Updates Content model
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @return Response
         * @throws Exception
         * @action Library_BookController_actionContents
         */
        public function actionContents(): Response
        {
            $actionId = static::ACTION_INDEX . 'actionContents';
            
            $models = Book::find()
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
         * Displays a single Post model.
         * @return Response|string
         * @throws Throwable
         * @action Library_BookController_actionClearCache
         */
        public function actionClearCache(): Response|string
        {
            if ($this->service->clearCache()) {
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Кэш книг очищен!',
                         )
                ;
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
            
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
        
        /*####### Active, Draft, Archive #####################################*/
        
        /**
         * @param integer $id
         * @param int     $statusId
         * @return Response
         */
        public function actionStatus(
            int $id, int $statusId,
        ): Response
        {
            $actionId = static::ACTION_INDEX . 'actionStatus';
            
            try {
                $this->service->status($id, $statusId);
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'status',
                    $e,
                );
            }
            return $this->redirect(
                Yii::$app->request->referrer
                    ?:
                    [
                        'view',
                        'id' => $id,
                    ],
            );
        }
        
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
        
        /**
         * @param integer $id
         * @return Response
         * @throws Throwable
         */
        public function actionMain(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionMain';
            
            try {
                $this->service->setMain($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'primary',
                             'Установлен на главную страницу!',
                         )
                ;
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'setMain',
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
        public function actionMenu(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionMenu';
            
            try {
                $this->service->setMenu($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'primary',
                             'Установлен в навигацию!',
                         )
                ;
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'setMenu',
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
        public function actionMainMenu(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionMainMenu';
            
            try {
                $this->service->setMainMenu($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'primary',
                             'Установлен на главную страницу и в навигацию!',
                         )
                ;
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'setMainMenu',
                    $e,
                );
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /*####### Services ###################################################*/
        
        public function actionViewAjax(int $id): string|Response
        {
            return $this->renderAjax(
                '_sort',
                [
                    'model' => $this->repository::get($id),
                ],
            );
        }
        
        public function actionLists(int $status, int $id): void
        {
            $models = Book::find()
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
                echo '<option> На этом сайте книг нет! Создайте корневой том!</option>';
            }
        }
        
        /*###### Roots #######################################################*/
        
        /**
         * Updates an existing Book model.
         * If update is successful, the browser will be redirected to the
         * 'view' book.
         * @param ?int $id
         * @return string|Response
         * @throws Exception
         * @action Library_BookController_actionUpdateRoot
         */
        public function actionUpdateRoot(?int $id = null): string|Response
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
                    'model'    => $form,
                    'root'     => $root,
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
            
            $model = Book::findOne($id);
            
            if (!$model) {
                return ['error' => 'Book not found'];
            }
            return [
                'id'          => $model->id,
                'image'       => $model->getImageUrl(6),
                'name'        => $model->name,
                'title'       => $model->title,
                'description' => FormatHelper::asHtml($model->description),
                'text'        => FormatHelper::asHtml($model->text),
                'status'      => StatusHelper::statusBadgeLabel($model->status),
                'editUrl'     => Url::to(['/library/book/view', 'id' => $model->id]),
            ];
        }
    }
