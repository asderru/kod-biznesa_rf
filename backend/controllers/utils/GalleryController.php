<?php
    
    namespace backend\controllers\utils;
    
    use backend\helpers\ImageHelper;
    use core\edit\entities\Utils\Gallery;
    use core\edit\forms\ModelEditForm;
    use core\edit\forms\UploadMultiPhotosForm;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\forms\UploadVideoForm;
    use core\edit\forms\Utils\Gallery\GalleryAssignForm;
    use core\edit\forms\Utils\Gallery\GalleryForm;
    use core\edit\repositories\Utils\GalleryRepository;
    use core\edit\search\Utils\GallerySearch;
    use core\edit\search\Utils\PhotoSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Photo\ImageProcessorService;
    use core\edit\useCases\Utils\GalleryManageService;
    use core\helpers\ClearHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\read\widgets\nestable\NodeMoveAction;
    use core\tools\Constant;
    use DomainException;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use JetBrains\PhpStorm\Pure;
    use RuntimeException;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    /**
     * GalleryController implements the CRUD actions for Gallery model.
     * @property-read array $roots
     */
    class GalleryController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Gallery::TEXT_TYPE;
        private const string  MODEL_LABEL   = Gallery::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Gallery::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Utils_GalleryController_';
        private const string  SERVICE_INDEX = 'GalleryManageService_';
        
        private GalleryRepository     $repository;
        private GalleryManageService  $service;
        private ImageProcessorService $imageService;
        
        public function __construct(
            $id,
            $module,
            GalleryRepository $repository,
            GalleryManageService $service,
            ImageProcessorService $imageService,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository   = $repository;
            $this->service      = $service;
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
            'nodeMove' => 'array',
            'sort'     => 'array',
        ])]
        public function actions(): array
        {
            return [
                'nodeMove' => [
                    'class'     => NodeMoveAction::class,
                    'modelName' => Gallery::className(),
                ],
            ];
        }
        
        /**
         * Lists all Gallery models.
         * @return Response|string
         * @throws Exception
         * @action Utils_GalleryController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            $sites = ParametrHelper::getSites();
            $roots = $this->repository::getRoots();
            $searchModel  = new GallerySearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            $pageSize = Yii::$app->request->get('pageSize', 50);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            return $this->render(
                '@app/views/utils/gallery/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'roots'        => $roots,
                    'sites' => $sites,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Lists all Gallery models.
         * @param ?int $id
         * @return Response|string
         * @action Utils_GalleryController_actionResort
         */
        public function actionResort(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $query = (new Gallery)::getTree($id);
            
            return $this->render(
                '@app/views/utils/gallery/resort',
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
         * Displays a single Gallery model.
         * @param integer $id
         * @return Response|string
         * @action Utils_GalleryController_actionView
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
            
            $searchModel  = new PhotoSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
                null,
                Constant::STATUS_DRAFT,
            );
            
            $photosForm = new UploadMultiPhotosForm();
            
            if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
                try {
                    $this->service->uploadPhotos($model, $photosForm);
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
                        'service->uploadPhotos',
                        $e,
                    );
                }
            }
            $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
            
            return $this->render(
                '@app/views/utils/gallery/view',
                [
                    'model'        => $model,
                    'parent'       => $parent,
                    'photosForm'   => $photosForm,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Gallery model.
         * @param integer $id
         * @return Response|string
         * @action GalleryController_view
         */
        public function actionVideo(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionVideo';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect(['index']);
            }
            
            $videoForm = new UploadVideoForm();
            
            if (Yii::$app->request->isPost) {
                $videoForm->load(Yii::$app->request->post());
                $videoForm->videoFile = UploadedFile::getInstance($videoForm, 'videoFile');
                
                if (
                    $this->handleFormValidationErrors($videoForm)
                ) {
                    return $this->render(
                        '@app/views/utils/gallery/video',
                        [
                            'model'     => $model,
                            'videoForm' => $videoForm,
                            'actionId'  => $actionId,
                            'textType'  => static::TEXT_TYPE,
                            'prefix'    => static::MODEL_PREFIX,
                            'label'     => static::MODEL_LABEL,
                        ],
                    );
                }
                
                if ($videoForm->validate()) {
                    try {
                        $this->service->uploadVideo($model, $videoForm);
                        return $this->redirect(
                            [
                                'view',
                                'id' => $model->id,
                            ],
                        );
                    }
                    catch (DomainException|Exception $e) {
                        PrintHelper::exception(
                            $actionId,
                            'service->uploadVideo',
                            $e,
                        );
                    }
                }
            }
            
            
            return $this->render(
                '@app/views/utils/gallery/video',
                [
                    'model'     => $model,
                    'videoForm' => $videoForm,
                    'actionId'  => $actionId,
                    'textType'  => static::TEXT_TYPE,
                    'prefix'    => static::MODEL_PREFIX,
                    'label'     => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @param integer $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         */
        public function actionImage(
            int $id,
        ): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionactionImage';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Галерея  #' . $id . ' не найдена!',
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
                '@app/views/utils/gallery/image',
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
         * @param int $textType
         * @param int $parentId
         * @return Response|string
         * @throws Throwable
         */
        public function actionAssign(int $textType, int $parentId): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionAssign';
            
            $parent      = ParentHelper::getModel($textType, $parentId);
            $editView = TypeHelper::getView($textType);
            $searchModel = new GallerySearch();
            
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            $assignForm = new GalleryAssignForm($textType, $parentId);
            
            $selection = Yii::$app->request->post('selection', []);
            
            if ($selection) {
                $this->service->assign($textType, $parentId, $selection);
                
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Изменения внесены в базу!',
                         )
                ;
                return $this->redirect(
                    [
                        $editView,
                        'id' => $parent->id,
                    ],
                );
            }
            
            
            return $this->render('@app/views/utils/gallery/assign', [
                'parent'       => $parent,
                'assignForm'   => $assignForm,
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'actionId'     => $actionId,
                'textType'     => static::TEXT_TYPE,
                'prefix'       => static::MODEL_PREFIX,
                'label'        => static::MODEL_LABEL,
            ]);
        }
        
        /**
         * Creates a new Gallery model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int|null $textType
         * @param int|null $parentId
         * @return string|Response
         * @action Utils_GalleryController_actionCreate_
         */
        public function actionCreate(?int $textType = null, ?int $parentId = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $site = ParametrHelper::getSite();
            
            $isAlone = ($site->children()->all() === []);
            $form    = new GalleryForm();
            $parent  = null;
            
            if ($textType && $parentId) {
                $parent = ParentHelper::getModel($textType, $parentId);
            }
            else {
                Yii::$app->session->setFlash('warning', 'Тема не указана. Укажите тему галереи обязательно!');
            }
            
            if ($form->load(Yii::$app->request->post())) {
                try {
                    $model = $this->service->create($form);
                    
                    if (!$model) {
                        Yii::$app->session->setFlash(
                            'danger',
                            'Проблема с сохранением галереи. Обратитесь к администратору!',
                        );
                        return $this->redirect(['create']);
                    }
                    
                    Yii::$app->session->setFlash('success', 'Галерея успешно создана!');
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
                '@app/views/utils/gallery/create',
                [
                    'model'    => $form,
                    'site'     => $site,
                    'isAlone'  => $isAlone,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        
        /**
         * Updates an existing Gallery model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception|Throwable
         * @action Utils_GalleryController_actionUpdate
         */
        public function actionUpdate(
            int $id,
        ): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $gallery = $this->repository::get($id);
            if (!$gallery) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            if ($gallery->status === Constant::STATUS_ROOT) {
                return $this->redirect([
                    'update-root',
                ]);
            }
            
            $form = new GalleryForm($gallery);
            
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
                            'id' => $gallery->id,
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
                '@app/views/utils/gallery/update',
                [
                    'model'    => $form,
                    'gallery'  => $gallery,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Displays a single Gallery model.
         * @return Response|string
         * @throws Throwable
         * @action GalleryController_view
         */
        public function actionClearCache(): Response|string
        {
            if ($this->service->clearCache()) {
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Кэш галерей очищен!',
                         )
                ;
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
            
        }
        
        /**
         * Deletes an existing Gallery model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Utils_GalleryController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
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
                    $this->service->remove($model->id);
                    Yii::$app->session->
                    setFlash(
                        'warning',
                        'Галерея успешно удалена!',
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
                         'Корневую галерею удалить нельзя!',
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
        
        public function actionDeletePhoto(
            int $id,
        ): string|Response
        {
            try {
                // Проверяем существование модели
                $model = $this->repository::get($id);
                if (!$model) {
                    Yii::$app->session->setFlash(
                        'warning',
                        "Модель #$id не найдена!",
                    );
                    return $this->redirect(['index']);
                }
                
                // Удаляем фото
                UploadMultiPhotosForm::deletePhoto($model);
                
                Yii::$app->session->setFlash(
                    'success',
                    'Фото успешно удалено!',
                );
                
            }
            catch (RuntimeException $e) {
                Yii::error('Ошибка при удалении фото: ' . $e->getMessage());
                Yii::$app->session->setFlash(
                    'danger',
                    'Ошибка при удалении фото: ' . $e->getMessage(),
                );
            }
            catch (Exception $e) {
                Yii::error('Неизвестная ошибка при удалении фото: ' . $e->getMessage());
                Yii::$app->session->setFlash(
                    'danger',
                    'Произошла ошибка при удалении фото',
                );
            }
            
            return $this->redirect(['view', 'id' => $id]);
        }
        
        /**
         * @throws Exception|Throwable
         */
        public function actionDeletePhotos(int $id, int $photoId): Response
        {
            try {
                // Проверяем существование модели
                $model = $this->repository::get($id);
                if (!$model) {
                    Yii::$app->session->setFlash(
                        'warning',
                        "Модель #$id не найдена!",
                    );
                    return $this->redirect(['index']);
                }
                
                // Удаляем фото
                UploadMultiPhotosForm::deletePhotos($model, $photoId);
                
                Yii::$app->session->setFlash(
                    'success',
                    'Фото успешно удалено!',
                );
                
            }
            catch (RuntimeException $e) {
                Yii::error('Ошибка при удалении фото: ' . $e->getMessage());
                Yii::$app->session->setFlash(
                    'danger',
                    'Ошибка при удалении фото: ' . $e->getMessage(),
                );
            }
            catch (Exception $e) {
                Yii::error('Неизвестная ошибка при удалении фото: ' . $e->getMessage());
                Yii::$app->session->setFlash(
                    'danger',
                    'Произошла ошибка при удалении фото',
                );
            }
            
            return $this->redirect(['view', 'id' => $id]);
        }
        
        /**
         * @param integer $id
         * @param         $photo_id
         * @return Response
         */
        public function actionMovePhotoUp(int $id, $photo_id): Response
        {
            $this->service->movePhotoUp($id, $photo_id);
            return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
        }
        
        /**
         * @param integer $id
         * @param         $photo_id
         * @return Response
         */
        public function actionMovePhotoDown(int $id, $photo_id): Response
        {
            $this->service->movePhotoDown($id, $photo_id);
            return $this->redirect(['view', 'id' => $id, '#' => 'photos']);
        }
        
        
        /*####### Active, Draft, Archive #####################################*/
        
        /**
         * @param integer $id
         * @param int     $statusId
         * @return Response
         * @throws \yii\db\Exception
         */
        public function actionStatus(
            int $id, int $statusId,
        ): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionStatus';
            
            try {
                $this->service->status($id, $statusId);
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'status',
                    $e,
                );
            }
            return $this->redirect(['view', 'id' => $id]);
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
            $actionId = static::ACTION_INDEX . '32_actionActivate';
            
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
            return $this->redirect(['view', 'id' => $id]);
        }
        
        /**
         * @param integer $id
         * @return mixed
         * @throws Throwable
         */
        public function actionDraft(int $id): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionDraft';
            
            try {
                $this->service->draft($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'warning',
                             'Отправлено в черновики!',
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
            return $this->redirect(['view', 'id' => $id]);
        }
        
        /**
         * @param integer $id
         * @return mixed
         * @throws Throwable
         */
        public function actionArchive(int $id): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionArchive';
            
            try {
                $this->service->archive($id);
                Yii::$app->getSession()
                         ->setFlash('warning', 'Галерея  ' . $id . ' отправлена в архив!')
                ;
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'archive',
                    $e,
                );
                
            }
            return $this->redirect(['view', 'id' => $id]);
        }
        
        /**
         * @param integer $id
         * @return Response
         * @throws Throwable
         */
        public function actionMain(int $id): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionMain';
            
            try {
                $this->service->setMain($id);
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'setMain',
                    $e,
                );
            }
            return $this->redirect(['view', 'id' => $id]);
        }
        
        /**
         * @param integer $id
         * @return mixed
         * @throws Throwable
         */
        public function actionMenu(int $id): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionMenu';
            
            try {
                $this->service->setMenu($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'primary',
                             'Отправлен в навигацию!',
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
            return $this->redirect(['view', 'id' => $id]);
        }
        
        /**
         * @param integer $id
         * @return mixed
         * @throws Throwable
         */
        public function actionMainMenu(
            int $id,
        ): Response
        {
            $actionId = static::ACTION_INDEX . '10_actionMainMenu';
            
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
            return $this->redirect(['view', 'id' => $id]);
        }
        
        /*####### Services ###################################################*/
        
        public function actionViewAjax(
            int $id,
        ): string|Response
        {
            return $this->renderAjax(
                '_sort',
                [
                    'model' => $this->repository::get($id),
                ],
            );
        }
        
        public function actionLists(
            int $status, int $id,
        ): void
        {
            $models = $this->receiver->getBySite($id)
                                     ->andWhere(
                                         [
                                             '>',
                                             'status',
                                             $status,
                                         ],
                                     )->all()
            ;
            
            if (!empty($models)) {
                foreach ($models as $model) {
                    $time   = $model->depth - 1;
                    $indent = ($model->depth !== Constant::THIS_FIRST_NODE
                        ?
                        str_repeat('&nbsp;&nbsp;', $time)
                        .
                        str_repeat('	&middot;', $time) . ' '
                        :
                        ''
                    );
                    echo "<option value='" . $model->id . "'>" . $indent . $model->name . '</option>';
                }
            }
            else {
                echo '<option> На этом сайте галлерей нет!</option>';
            }
        }
        
        /*###### Roots #######################################################*/
        
        /**
         * Updates an existing StrictPage model.
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
            else {
                // Получите массив ошибок валидации в виде строки
                $errors = implode(', ', $form->getFirstErrors());
                
                if ($errors) {
                    Yii::$app->getSession()
                             ->setFlash('danger', $errors)
                    ;
                }
                // Обработка ошибок, например, вывод ошибок в представлении или что-то ещё
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
    }
