<?php
    
    namespace backend\controllers\user;
    
    use backend\helpers\ImageHelper;
    use core\edit\entities\User\Person;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\forms\User\PersonForm;
    use core\edit\repositories\User\PersonRepository;
    use core\edit\repositories\User\UserRepository;
    use core\edit\search\User\PersonSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Photo\ImageProcessorService;
    use core\edit\useCases\User\PersonManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use DomainException;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    class PersonController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Person::TEXT_TYPE;
        private const string  ACTION_INDEX  = 'User_PersonController_';
        private const string  SERVICE_INDEX = 'PersonManageService_';
        
        private PersonRepository      $repository;
        private PersonManageService   $service;
        private ImageProcessorService $imageService;
        private UserRepository        $users;
        
        public function __construct(
            $id,
            $module,
            PersonRepository $repository,
            PersonManageService $service,
            ImageProcessorService $imageService,
            UserRepository $users,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository   = $repository;
            $this->service      = $service;
            $this->imageService = $imageService;
            $this->users        = $users;
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
         * Lists all Person models.
         * @param int|null $id
         * @return Response|string
         * @throws Exception
         * @action User_PersonController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new PersonSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            
            return $this->render(
                '@app/views/user/person/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Displays a single Person model.
         * @param integer $id
         * @return Response|string
         * @throws Throwable
         * @action User_PersonController_actionView
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
                '@app/views/user/person/view',
                [
                    'model'      => $model,
                    'uploadForm' => $uploadForm,
                    'actionId'   => $actionId,
                    'textType'   => static::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Creates a new Person model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param ?int $id
         * @return string|Response
         * @action User_PersonController_actionCreate_
         */
        public function actionCreate(?int $id = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $userId = $id ?? Yii::$app->user->id;
            $user   = $this->users->get($userId);
            
            $form = new PersonForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $product = $this->service->create($form);
                    return $this->redirect(
                        [
                            'view',
                            'id' => $product->id,
                        ],
                    );
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
                '@app/views/user/person/create',
                [
                    'model'    => $form,
                    'user'     => $user,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Updates an existing Person model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @action User_PersonController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $person = $this->repository::get($id);
            if (!$person) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Профиль не найден!',
                );
            }
            
            $form = new PersonForm($person);
            
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
                            'id' => $person->id,
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
                '@app/views/user/person/update',
                [
                    'person'   => $person,
                    'model'    => $form,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Deletes an existing Person model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action User_PersonController_actionDelete
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
            }
            
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
            
            return $this->redirect(
                (ClearHelper::getAction() !== 'view')
                    ?
                    Yii::$app->request->referrer
                    :
                    'index',
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
         * @param integer $id
         * @return Response
         * @action PostController_activate
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
            catch (DomainException|Throwable $e) {
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
         * @action PostController_draft
         */
        public function actionDraft(int $id): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionDraft';
            
            try {
                $this->service->draft($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'warning',
                             'Отправлен в черновики!',
                         )
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
        
        /**
         * @param integer $id
         * @return mixed
         * @action PostController_archive
         */
        public function actionArchive(int $id): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionArchive';
            
            try {
                $this->service->archive($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'warning',
                             'Отправлен в архив!',
                         )
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
        
    }
