<?php
    
    namespace backend\controllers\library;
    
    use backend\helpers\ImageHelper;
    use core\edit\editors\Admin\InformationEditor;
    use core\edit\entities\Library\Author;
    use core\edit\forms\Library\AuthorForm;
    use core\edit\forms\SortForm;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\repositories\Admin\InformationRepository;
    use core\edit\repositories\Library\AuthorRepository;
    use core\edit\search\Library\AuthorSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Library\AuthorManageService;
    use core\edit\useCases\Photo\ImageProcessorService;
    use core\helpers\ClearHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
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
    use yii\web\UploadedFile;
    
    /**
     * AuthorController implements the CRUD actions for Author model.
     * @property-read array $roots
     */
    class AuthorController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE     = Author::TEXT_TYPE;
        private const string ACTION_INDEX  = 'Library_AuthorController_';
        private const string SERVICE_INDEX = 'AuthorManageService_';
        
        private AuthorRepository      $repository;
        private AuthorManageService   $service;
        private InformationRepository $sites;
        private ImageProcessorService $imageService;
        
        public function __construct(
            $id,
            $module,
            AuthorRepository $repository,
            AuthorManageService $service,
            InformationRepository $sites,
            ImageProcessorService $imageService,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
            $this->sites      = $sites;
            $this->imageService = $imageService;
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
                    'modelName' => Author::className(),
                ],
            ];
        }
        
        /**
         * Lists all Author models.
         * @param ?int $id
         * @return Response|string
         * @throws Exception
         * @action Library_AuthorController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $sites = InformationEditor::getArray(['id', 'name']);
          
            $searchModel  = new AuthorSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            $pageSize     = Yii::$app->request->get('pageSize', 50);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            return $this->render(
                '@app/views/library/author/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'sites'        => $sites,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * @throws Exception
         * @action Library_ActionController_actionResort
         */
        public function actionResort(?int $id = null):
        Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new AuthorSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            
            $form = new SortForm();
            
            $site = $this->sites::get($id);
            
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
                            'id' => $site->id,
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
                '@app/views/library/author/resort',
                [
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'site'         => $site,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Displays a single Author model.
         * @param integer $id
         * @return Response|string
         * @throws Throwable
         * @action Library_AuthorController_actionView
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
                '@app/views/library/author/view',
                [
                    'model'      => $model,
                    'uploadForm' => $uploadForm,
                    'actionId'   => $actionId,
                    'textType'   => static::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Creates a new Author model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @action Library_AuthorController_actionCreate_
         * @throws Exception
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form = new AuthorForm();
            
            $site = ParametrHelper::getSite();
            
            if (
                $form->load(Yii::$app->request->post())
            ) {
                try {
                    $model = $this->service->create($form);
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Автор добавлен в библиотеку!',
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
                catch (Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'create_',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/library/author/create',
                [
                    'model'    => $form,
                    'site' => $site,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Updates an existing Author model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @action Library_ActionController_actionUpdate
         * @throws Throwable
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $author = $this->repository::get($id);
            
            if (!$author) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            if ($author->status === Constant::STATUS_ROOT) {
                return $this->redirect([
                    'update-root',
                ]);
            }
            
            $form = new AuthorForm($author);
            
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
                            'id' => $author->id,
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
                '@app/views/library/author/update',
                [
                    'model'    => $form,
                    'author'   => $author,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                ],
            );
            
        }
        
        /**
         * Deletes an existing Author model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @throws Exception
         * @action Library_AuthorController_actionDelete
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
            
            if (!$model->isRoot()) {
                try {
                    $this->actionDeletePhoto($model->id);
                }
                catch (DomainException $e) {
                    PrintHelper::exception(
                        $actionId, 'actionDeletePhoto',
                        $e,
                    );
                }
                
                try {
                    $this->service->remove($model->id);
                    Yii::$app->session->
                    setFlash(
                        'warning',
                        'Автор успешно удален!',
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
                         'Корневого автора удалить нельзя!',
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
        
        ######## Editor ########################################################
        
        public function actionActivate(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionActivate';
            try {
                $this->service->activate($id);
                Yii::$app->getSession()
                         ->setFlash('success', 'Автор  ' . $id . ' активирован!')
                ;
            }
            catch (DomainException|Throwable  $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'activate',
                    $e,
                );
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        public function actionDraft(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDraft';
            
            try {
                $this->service->draft($id);
                Yii::$app->getSession()
                         ->setFlash('warning', 'Автор  ' . $id . ' деактивирован!')
                ;
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'draft',
                    $e,
                );
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        public function actionArchive(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionArchive';
            
            try {
                $this->service->archive($id);
                Yii::$app->getSession()
                         ->setFlash('warning', 'Автор  ' . $id . ' отправлен в архив!')
                ;
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'archive',
                    $e,
                );
                
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /*####### Sort #######################################################*/
        
        /**
         * @throws Throwable
         */
        public function actionClearSort(int $id): void
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
    }
