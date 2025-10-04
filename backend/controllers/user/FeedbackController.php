<?php
    
    namespace backend\controllers\user;
    
    use core\edit\entities\User\Feedback;
    use core\edit\forms\User\FeedbackForm;
    use core\edit\repositories\User\FeedbackRepository;
    use core\edit\search\User\FeedbackSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\User\FeedbackManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use DomainException;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class FeedbackController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = Feedback::TEXT_TYPE;
        private const string  MODEL_LABEL   = Feedback::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Feedback::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'User_FeedbackController_';
        private const string  SERVICE_INDEX = 'FeedbackManageService_';
        
        private FeedbackRepository    $repository;
        private FeedbackManageService $service;
        
        public function __construct(
            $id,
            $module,
            FeedbackRepository $repository,
            FeedbackManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
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
         * Lists all Feedback models.
         * @param ?int $id
         * @return Response|string
         * @throws Exception
         * @action User_FeedbackController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel = new FeedbackSearch();
            
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            
            return $this->render(
                '@app/views/user/feedback/index',
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
         * Displays a single Feedback model.
         * @param integer $id
         * @return Response|string
         * @throws Throwable
         * @action User_FeedbackController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $model = $this->repository::get($id);
            
            if ($model->status === Constant::STATUS_ROOT) {
                $this->service->draft($model->id);
            }
            return $this->render(
                '@app/views/user/feedback/view',
                [
                    'model'    => $model,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Feedback model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @action User_FeedbackController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $feedback = $this->repository::get($id);
            
            $form = new FeedbackForm($feedback);
            
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
                            'id' => $feedback->id,
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
                '@app/views/user/feedback/update',
                [
                    'model'    => $form,
                    'feedback' => $feedback,
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
         * @action User_FeedbackController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            
            try {
                $this->service->remove($model->id);
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Уведомление успешно удалено!',
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
        
        /**
         * Deletes an existing Razdel model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @return Response
         */
        public function actionDeleteAll(): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDeleteAll';
            
            try {
                $this->service->removeAll();
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Все новые уведомления успешно удалены!',
                );
            }
            catch (DomainException|StaleObjectException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'removeAll',
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
        
        /*####### Editor #####################################################*/
        
        /**
         * @throws Throwable
         */
        public function actionActivate(int $id): bool
        {
            $this->service->activate($id);
            return true;
        }
        
        /**
         * @param integer $id
         * @return mixed
         * @action ProductController_draft
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
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
        }
        
        /**
         * @throws Throwable
         */
        public function actionArchive(int $id): bool
        {
            $this->service->archive($id);
            return true;
        }
        
    }
