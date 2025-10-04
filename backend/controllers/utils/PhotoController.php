<?php
    
    namespace backend\controllers\utils;
    
    use backend\helpers\ImageHelper;
    use core\edit\entities\Utils\Gallery;
    use core\edit\entities\Utils\Photo;
    use core\edit\forms\SortForm;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\forms\Utils\Gallery\GalleryPhotoForm;
    use core\edit\repositories\Utils\GalleryRepository;
    use core\edit\repositories\Utils\PhotoRepository;
    use core\edit\search\Utils\PhotoSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Photo\ImageProcessorService;
    use core\edit\useCases\Utils\PhotoManageService;
    use core\helpers\ClearHelper;
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
    
    class PhotoController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Photo::TEXT_TYPE;
        private const string  MODEL_LABEL   = Photo::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Photo::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Utils_PhotoController_';
        private const string  SERVICE_INDEX = 'PhotoManageService_';
        
        private PhotoRepository       $repository;
        private PhotoManageService    $service;
        private ImageProcessorService $imageService;
        private GalleryRepository     $galleries;
        
        public function __construct(
            $id,
            $module,
            PhotoRepository $repository,
            PhotoManageService $service,
            ImageProcessorService $imageService,
            GalleryRepository $galleries,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository   = $repository;
            $this->service      = $service;
            $this->imageService = $imageService;
            $this->galleries    = $galleries;
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
                    'modelName' => Photo::className(),
                ],
            ];
        }
        
        /**
         * Lists all Photo models.
         * @param int|null $galleryId
         * @return Response|string
         * @action Utils_PhotoController_actionIndex
         */
        public function actionIndex(?int $galleryId = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $galleries = Gallery::find()
                                ->noRoots()
                                ->all()
            ;
            
            $searchModel  = new PhotoSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $galleryId,
            );
            
            return $this->render(
                '@app/views/utils/photo/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'galleries'    => $galleries,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * @throws Exception|Throwable
         * @action Utils_PhotoController_actionResort
         */
        public function actionResort(int $id):
        Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $gallery = $this->galleries::get($id);
            
            if (!$gallery) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Галерея не обнаружена!',
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
                '@app/views/utils/photo/resort',
                [
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'gallery'      => $gallery,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Photo model.
         * @param integer $id
         * @return Response|string
         * @throws Throwable
         * @action Utils_PhotoController_actionView
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
                    self::actionDeletePhoto($id);
                    self::actionDeletePhoto($id);
                    $this->imageService->convertPhotoToWeb($model, static::TEXT_TYPE);
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
                '@app/views/utils/photo/view',
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
         * Updates an existing Photo model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @action Utils_PhotoController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $photo = $this->repository::get($id);
            
            if (!$photo) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new GalleryPhotoForm($photo);
            
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
                            'id' => $photo->id,
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
                '@app/views/utils/photo/update',
                [
                    'model'    => $form,
                    'photo'    => $photo,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Updates an existing Photo model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Throwable
         */
        public function actionChange(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionChange';
            
            $photo = $this->repository::get($id);
            
            if (!$photo) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $model      = $photo->gallery;
            $uploadForm = new UploadPhotoForm();
            
            if (Yii::$app->request->isPost) {
                ImageHelper::createWatermark($model->site_id);
                $uploadForm->imageFile = UploadedFile::getInstance($uploadForm, 'imageFile');
                if ($uploadForm->upload($model, static::TEXT_TYPE)) {
                    $this->imageService->convertPhotoToWeb($model, static::TEXT_TYPE);
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
                '@app/views/utils/photo/change',
                [
                    'model'    => $model,
                    'photo'    => $photo,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /**
         * Deletes an existing Razdel model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Utils_PhotoController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionChange';
            
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
                    'Фото успешно удален!',
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
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $model->deletePhoto($model->id);
            
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
         * @throws Throwable
         */
        public function actionStatus(int $id, int $statusId): Response
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
            return $this->redirect([
                Yii::$app->request->referrer,
            ]);
        }
        
        /**
         * @param integer $id
         * @return Response
         * @throws Throwable
         */
        public function actionActivate(int $id): Response
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
            return $this->redirect(
                Yii::$app->request->referrer,
            );
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
            return $this->redirect(
                Yii::$app->request->referrer,
            );
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
                         ->setFlash('warning', 'Фото  ' . $id . ' отправлено в архив!')
                ;
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'archive',
                    $e,
                );
                
            }
            return $this->redirect(
                Yii::$app->request->referrer,
            );
        }
        
    }
