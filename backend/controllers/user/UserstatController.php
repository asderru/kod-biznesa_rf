<?php
    
    namespace backend\controllers\user;
    
    use core\edit\entities\User\UserStatistic;
    use core\edit\repositories\User\UserStatRepository;
    use core\edit\search\User\UserStatSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Tech\BlackListManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use Exception;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\web\Controller;
    use yii\web\Response;
    
    class UserstatController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE    = UserStatistic::TEXT_TYPE;
        private const string  MODEL_LABEL  = UserStatistic::MODEL_LABEL;
        private const string MODEL_PREFIX = UserStatistic::MODEL_PREFIX;
        private const string  ACTION_INDEX = 'User_UserstatController_';
        private const string  SERVICE_INDEX = 'BlackListManageService_';
        
        private UserStatRepository     $repository;
        private BlackListManageService $blackListService;
        
        public function __construct(
            $id,
            $module,
            UserStatRepository $repository,
            BlackListManageService $blackListService,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository       = $repository;
            $this->blackListService = $blackListService;
        }
        
        /**
         * Lists all UserStatistic models.
         * @param bool|null $full
         * @param int|null $id
         * @return Response|string
         * @action User_UserstatController_actionIndex
         */
        public function actionIndex(bool|null $full = false, ?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new UserStatSearch();
            $dataProvider = $searchModel->search(
                $this->request->queryParams,
                $full,
                $id,
            );
            
            return $this->render(
                '@app/views/user/userstat/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single UserStatistic model.
         * @param int $id ID
         * @return Response|string
         * @action User_UserstatController_actionView
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
                return $this->redirect(
                    (ClearHelper::getAction() !== 'view')
                        ?
                        Yii::$app->request->referrer
                        :
                        'index',
                );
            }
            
            return $this->render(
                '@app/views/user/userstat/view',
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
         * @throws Exception|Throwable
         * @action User_UserstatController_actionCreate_
         */
        public function actionCreate(int $id, string $ipAddress): string|Response
        {
            try {
                $this->blackListService::create($ipAddress);
                
                $model = $this->repository::get($id);
                
                if (!$model) {
                    Yii::$app->session->
                    setFlash(
                        'warning',
                        'Модель  #' . $id . ' не найдена!',
                    );
                    return $this->redirect(
                        (ClearHelper::getAction() !== 'view')
                            ?
                            Yii::$app->request->referrer
                            :
                            'index',
                    );
                }
                $this->repository::remove($model);
                
                Yii::$app->session->setFlash('success', 'IP-адрес ' . $ipAddress . ' помещен в черный список.');
            }
            catch (Exception $e) {
                Yii::$app->session->setFlash('danger', 'Произошла ошибка при обработке запроса: ' . $e->getMessage());
            }
            
            return $this->redirect('index');
        }
        
        /**
         * Updates an existing UserStatistic model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param int $id ID
         * @return string|Response
         * @throws Exception if the model cannot be found
         * @action User_UserstatController_actionUpdate
         */
        public function actionUpdate(int $id): Response|string
        {
            $actionId = '#user_UserStatController_update';
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect(
                    (ClearHelper::getAction() !== 'view')
                        ?
                        Yii::$app->request->referrer
                        :
                        'index',
                );
            }
            
            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
            return $this->render(
                '@app/views/user/userstat/update',
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
         * Deletes an existing UserStatistic model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param int $id ID
         * @return Response
         * @action User_UserstatController_actionDelete
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
                return $this->redirect(
                    (ClearHelper::getAction() !== 'view')
                        ?
                        Yii::$app->request->referrer
                        :
                        'index',
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
        
        public function actionDeleteAll(): Response
        {
            UserStatistic::deleteAll();
            return $this->redirect(
                'index',
            );
        }
    }
