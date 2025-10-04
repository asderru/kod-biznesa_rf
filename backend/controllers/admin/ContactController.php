<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\editors\Admin\ContactEditor;
    use core\edit\entities\Admin\Contact;
    use core\edit\forms\Admin\ContactForm;
    use core\edit\repositories\Admin\ContactRepository;
    use core\edit\search\Admin\ContactSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Admin\ContactManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class ContactController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE    = Contact::TEXT_TYPE;
        private const string MODEL_LABEL  = Contact::MODEL_LABEL;
        private const string MODEL_PREFIX = Contact::MODEL_PREFIX;
        private const string ACTION_INDEX = 'Admin_ContactController_';
        
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
        
        private ContactRepository    $repository;
        private ContactEditor $reader;
        private ContactManageService $service;
        
        public function __construct(
            $id,
            $module,
            ContactRepository $repository,
            ContactEditor $reader,
            ContactManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
            $this->reader    = $reader;
        }
        
        /**
         * Lists all Contact models.
         * @return Response|string
         * @action Admin_ContactController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new ContactSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/admin/contact/index',
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
         * Displays a single Contact model.
         * @param int|null $siteId
         * @return Response|string
         * @action Admin_ContactController_actionView
         */
        public function actionView(?int $siteId = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/admin/contact/view',
                [
                    'model'    => $this->repository::getBySite($siteId),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        
        /**
         * Displays a single Contact model.
         * @return Response|string
         * @action Admin_ContactController_actionView
         */
        public function actionBase(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/admin/contact/base',
                [
                    'model'    => $this->reader::getContactArray(Contact::FULL_PACK_FIELDS),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Throwable
         * @action Admin_ContactController_actionCreate_
         */
        public function actionCreate(?int $siteId = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionCreate_';
            
            $contact = $this->repository::getBySite($siteId);
            if ($contact) {
                return $this->redirect(['update', 'id' => $contact->id]);
            }
            
            $model = new ContactForm();
            
            if ($model->load(Yii::$app->request->post())) {
                $this->loadJsonFields($model);
                $this->service->create($siteId, $model);
                return $this->redirect(['view', 'id' => $siteId]);
            }
            
            return $this->render('create', [
                'model'    => $model,
                'actionId' => $actionId,
                'textType' => static::TEXT_TYPE,
                'prefix'   => static::MODEL_PREFIX,
                'label'    => static::MODEL_LABEL,
            ]);
        }
        
        /**
         * @throws Throwable
         * @action Admin_ContactController_actionUpdate
         */
        public function actionUpdate(?int $siteId = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $site = $this->repository::get($siteId);
            
            $contact = $this->repository::get($siteId);
            if (!$contact) {
                return $this->redirect(['create', 'id' => $siteId]);
            }
            
            $model = new ContactForm($contact);
            
            if ($model->load(Yii::$app->request->post())) {
                $this->loadJsonFields($model);
                $this->service->edit($siteId, $model);
                return $this->redirect([
                    'view',
                    'siteId' => $siteId,
                ]);
            }
            
            return $this->render(
                '@app/views/admin/contact/update', [
                'model'    => $model,
                'contact'  => $contact,
                'site'     => $site,
                'actionId' => $actionId,
                'textType' => static::TEXT_TYPE,
                'prefix'   => static::MODEL_PREFIX,
                'label'    => static::MODEL_LABEL,
            ],
            );
        }
        
        /**
         * Deletes an existing Contact model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Admin_ContactController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            if ($model) {
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
            }
            return $this->redirect(
                (ClearHelper::getAction() !== 'view')
                    ?
                    Yii::$app->request->referrer
                    :
                    'index',
            );
        }
        
        public function loadJsonFields(ContactForm $model): void
        {
            $jsonFields = ['address', 'analytics', 'languages', 'messengers', 'money', 'phones', 'socialNetworks', 'work_hours'];
            foreach ($jsonFields as $field) {
                if ($data = Yii::$app->request->post($field)) {
                    $model->$field = is_array($data) ? json_encode($data) : $data;
                }
            }
        }
        
    }
