<?php
    
    namespace backend\controllers\admin;
    
    use backend\helpers\AuthHelper;
    use backend\helpers\ImageHelper;
    use core\edit\editors\Admin\InformationEditor;
    use core\edit\entities\Admin\Information;
    use core\edit\entities\User\User;
    use core\edit\forms\Admin\ContactForm;
    use core\edit\forms\Admin\InformationForm;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\repositories\Admin\InformationRepository;
    use core\edit\repositories\User\UserRepository;
    use core\edit\search\Admin\InformationSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Admin\ContactManageService;
    use core\edit\useCases\Admin\InformationManageService;
    use core\edit\useCases\Photo\ImageProcessorService;
    use core\edit\widgets\nestable\NodeMoveAction;
    use core\helpers\ClearHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use core\tools\params\Parametr;
    use DomainException;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use JetBrains\PhpStorm\Pure;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\ErrorAction;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    class InformationController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Information::TEXT_TYPE;
        private const string  MODEL_LABEL   = Information::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Information::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Admin_InformationController_';
        private const string  SERVICE_INDEX = 'InformationManageService_';
        
        private InformationRepository   $repository;
        public InformationManageService $service;
        private InformationEditor $reader;
        public ContactManageService     $contactService;
        public ImageProcessorService    $imageService;
        public UserRepository           $users;
        
        public function __construct(
            $id,
            $module,
            InformationRepository $repository,
            InformationManageService $service,
            ContactManageService $contactService,
            ImageProcessorService $imageService,
            UserRepository $users,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository     = $repository;
            $this->service        = $service;
            $this->contactService = $contactService;
            $this->imageService   = $imageService;
            $this->users          = $users;
        }

        public function behaviors(): array
        {
            return [
                'verbs'  => [
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
                'error'    => [
                    'class' => ErrorAction::class,
                ],
                'nodeMove' => [
                    'class'     => NodeMoveAction::class,
                    'modelName' => Information::className(),
                ],
            ];
        }
        
        /**
         * Lists all Information models.
         * @return Response|string
         * @action Admin_InformationController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            if (!ParametrHelper::isServer()) {
                return $this->redirect([
                    'view',
                    'id' => Parametr::siteId(),
                ],
                );
            }
            
            $searchModel  = new InformationSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            $pageSize = Yii::$app->request->get('pageSize', 50);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            return $this->render(
                '@app/views/admin/information/index',
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
         * Lists all Information models.
         * @return Response|string
         * @action Admin_InformationController_actionResort
         */
        public function actionResort(): Response|string
        {
            $site     = ParametrHelper::getSite();
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            if (!ParametrHelper::isServer()) {
                return $this->redirect([
                    'view',
                    'id' => Parametr::siteId(),
                ],
                );
            }
            //ActiveQuery содержащий данные для дерева. depth = 0 - корень.
            $query = (new Information)::getTree();
            
            return $this->render(
                '@app/views/admin/information/resort',
                [
                    'query'    => $query,
                    'site'     => $site,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Exception|Throwable
         * @action Admin_InformationController_actionView
         */
        public function actionView(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $siteId = (ParametrHelper::isServer()) ?
                $id : Parametr::siteId();
            
            $model = $this->repository::get($siteId);
            
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
                '@app/views/admin/information/view',
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
         * Displays a single Information model.
         * @return Response|string
         * @action Admin_InformationController_actionBase
         */
        public function actionBase(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/admin/contact/base',
                [
                    'model'    => $this->reader::getSiteArray(Information::FULL_PACK_FIELDS),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new Information model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @throws Throwable
         * @action Admin_InformationController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $form = new InformationForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $site = $this->service->create($form);
                    if ($site) {
                        Yii::$app->session->setFlash('success', 'Сайт успешно создан.');
                        return $this->redirect(['view', 'id' => $site->id]);
                    }
                    Yii::$app->session->setFlash('danger', 'Сайт не был создан. Пожалуйста, попробуйте еще раз.');
                }
                catch (DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('danger', 'Произошла ошибка при создании сайта: ' . $e->getMessage());
                }
            }
            elseif ($form->hasErrors()) {
                Yii::$app->session->setFlash('danger', 'Проверьте правильность заполнения формы.');
            }
            
            return $this->render(
                '@app/views/admin/information/create',
                [
                    'model'    => $form,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Exception|Throwable
         * @action Admin_InformationController_actionUpdate
         */
        public function actionUpdate(?int $id = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $siteId = (ParametrHelper::isServer()) ?
                $id : Parametr::siteId();
            
            $info = $this->repository::get($siteId);
            
            if (!$info && !ParametrHelper::isServer()) {
                return $this->redirect([
                    'view',
                    'id' => $siteId,
                ],
                );
            }
            
            $form = new InformationForm($info);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->edit($info->id, $form);
                    Yii::$app->getSession()
                             ->setFlash('success', 'Изменения внесены в базу!')
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $info->id,
                        ],
                    );
                }
                catch (DomainException $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'edit_',
                        $e,
                    );
                }
            }
            return $this->render(
                '@app/views/admin/information/update',
                [
                    'model'    => $form,
                    'info'     => $info,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Exception|Throwable
         * @action InformationController_contact
         */
        public function actionContact(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionContact';
            
            $info = $this->repository::get($id);
            
            if (!$info && !ParametrHelper::isServer()) {
                return $this->redirect([
                    'view',
                    'id' => Parametr::siteId(),
                ],
                );
            }
            
            $contact = $info->getContact()->one();
            $form    = new ContactForm($contact);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->contactService->edit($contact?->id, $form);
                    Yii::$app->getSession()
                             ->setFlash('success', 'Изменения внесены в базу!')
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $info->id,
                        ],
                    );
                }
                catch (DomainException $e) {
                    PrintHelper::exception($actionId, 'Проблема с contactService->edit', $e);
                }
            }
            return $this->render(
                '@app/views/admin/information/contact',
                [
                    'model'    => $form,
                    'info' => $info,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /*###### Active ######################################################*/
        
        public function actionActivate(
            int $id,
        ): Response
        {
            $actionId = static::ACTION_INDEX . 'actionActivate';
            
            $model = $this->repository::get($id);
            if (!$model && !ParametrHelper::isServer()) {
                return $this->redirect([
                    'view',
                    'id' => Parametr::siteId(),
                ],
                );
            }
            try {
                $this->service->activate($id);
                Yii::$app->getSession()
                         ->setFlash('success', 'Сайт  #' . $model->name . ' активирован!')
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
        
        public function actionDraft(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDraft';
            
            $model = $this->repository::get($id);
            if (!$model && !ParametrHelper::isServer()) {
                return $this->redirect([
                    'view',
                    'id' => Parametr::siteId(),
                ],
                );
            }
            
            try {
                $this->service->draft($id);
                Yii::$app->getSession()
                         ->setFlash('warning', 'Сайт  #' . $model->name . ' деактивирован!')
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
            
            $model = $this->repository::get($id);
            if (!$model && !ParametrHelper::isServer()) {
                return $this->redirect([
                    'view',
                    'id' => Parametr::siteId(),
                ],
                );
            }
            try {
                $this->service->archive($id);
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
        
        
        public function actionRegister(
            int $id,
        ): Response
        {
            $actionId = static::ACTION_INDEX . 'actionRegister';
            
            $model = $this->repository::get($id);
            if (!$model && !ParametrHelper::isServer()) {
                return $this->redirect([
                    'view',
                    'id' => Parametr::siteId(),
                ],
                );
            }
            try {
                if ($this->service::register($model)) {
                    Yii::$app->getSession()
                             ->setFlash('warning', 'Сайт  #' . $model->name . ' зарегистрирован на сервере!')
                    ;
                }
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'register',
                    $e,
                );
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        
        /**
         * Deletes an existing Post model.
         * If deletion is successful, the browser will be redirected to the
         * '@app/views/blog/post/index' page.
         * @param integer $id
         * @return Response
         * @action Admin_InformationController_actionDelete
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
            
            if (!$model->isRoot() && (new User)->isSuperadmin()) {
                try {
                    $this->service->remove($model->id);
                    Yii::$app->session->
                    setFlash(
                        'warning',
                        'Сайт успешно удален!',
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
                         'Корневой сайт удалить нельзя!',
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
        /**
         * Displays a single Contact model.
         * @param int $id
         * @return Response|string
         * @action Admin_InformationController_actionViewAjax
         */
        /*####### Services ###################################################*/
        
        public function actionViewAjax(int $id): string|Response
        {
            return $this->renderAjax(
                '@app/views/admin/information/_sort',
                [
                    'model' => $this->repository::get($id),
                ],
            );
        }
        
        public function actionLists(int $status, int $id): void
        {
            $models = Information::find()
                                 ->andWhere(
                                     [
                                         '=',
                                         'id',
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
                    $time   = $model->depth;
                    $indent = ($model->depth !== Constant::STATUS_ROOT
                        ?
                        str_repeat('&nbsp;&nbsp;', $time)
                        .
                        str_repeat('	&middot;', $time) . ' '
                        :
                        ''
                    );
                    echo "<option value='" . $model->id . "'>" . $indent .
                         $model->name . ' (' . $model->id . ')</option>';
                }
            }
            else {
                echo '<option> Создайте корневой сайт!</option>';
            }
        }
        
    }
