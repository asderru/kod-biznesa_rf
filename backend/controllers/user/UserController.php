<?php
    
    namespace backend\controllers\user;
    
    use backend\helpers\AuthHelper;
    use core\edit\auth\ResetPasswordForm;
    use core\edit\entities\User\User;
    use core\edit\forms\User\UserForm;
    use core\edit\repositories\User\UserRepository;
    use core\edit\search\User\PersonSearch;
    use core\edit\search\User\UserSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Auth\PasswordResetService;
    use core\edit\useCases\User\UserManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use DomainException;
    use Exception;
    use himiklab\sortablegrid\SortableGridAction;
    use JetBrains\PhpStorm\ArrayShape;
    use JetBrains\PhpStorm\Pure;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\AccessControl;
    use yii\web\Controller;
    use yii\web\Response;
    
    class UserController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE    = User::TEXT_TYPE;
        private const string MODEL_LABEL  = User::MODEL_LABEL;
        private const string MODEL_PREFIX = User::MODEL_PREFIX;
        private const string ACTION_INDEX = 'User_UserController_';
        private const string  SERVICE_INDEX = 'UserManageService_';
        
        private UserRepository       $repository;
        private UserManageService    $service;
        private PasswordResetService $passService;
        
        public function __construct(
            $id,
            $module,
            UserRepository $repository,
            UserManageService $service,
            PasswordResetService $passService,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository  = $repository;
            $this->service     = $service;
            $this->passService = $passService;
        }
        
        #[Pure]
        #[ArrayShape([
            'access' => 'array',
            'verbs'  => 'array',
        ])]
        public function behaviors(): array
        {
            return [
                'access' => [
                    'class'        => AccessControl::className(),
                    'denyCallback' => function () {
                        die(
                        'Доступ к странице запрещен!!! Обратитесь к администратору'
                        );
                    },
                    'rules'        => [
                        AuthHelper::ruleAdmin(),
                        AuthHelper::ruleSuperadmin(),
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
                    'class' => SortableGridAction::className(),
                ],
            ];
        }
        
        /**
         * @action User_UserController_actionIndex
         */
        public function actionIndex(): string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new UserSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/user/user/index',
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
         * @action User_UserController_actionView
         */
        public function actionView(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/user/user/view',
                [
                    'model'    => $this->repository->get($id),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Exception|Throwable
         * @action User_UserController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = '#user_UserController_create';
            $form = new UserForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $user = $this->service->create($form);
                    return $this->redirect(
                        [
                            'view',
                            'id' => $user->id,
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
                '@app/views/user/user/create',
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
         * @action User_UserController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = '#user_UserController_update';
            $user     = $this->repository->get($id);
            
            $form = new UserEditForm($user);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->edit($user->id, $form);
                    Yii::$app->getSession()
                             ->setFlash('success', 'Изменения внесены в базу!')
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $user->id,
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
                '@app/views/user/user/update',
                [
                    'model'    => $form,
                    'user'     => $user,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @throws Exception
         */
        public function actionPerson(int $id): string|Response
        {
            $actionId     = '#user_UserController_person';
            $user         = $this->repository->get($id);
            $searchModel  = new PersonSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $user->id,
            );
            
            return $this->render(
                '@app/views/user/user/person',
                [
                    'user'         => $user,
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        public function actionPassword(int $id): Response|string
        {
            $actionId = '#user_UserController_password';
            $form     = new ResetPasswordForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->passService->resetPass($id, $form);
                    Yii::$app->session->
                    setFlash('success', 'Новый пароль установлен.');
                }
                catch (DomainException|\yii\base\Exception $e) {
                    PrintHelper::exception($actionId, 'Проблема с service->edit', $e);
                }
                return $this->goHome();
            }
            
            return $this->render(
                '@app/views/user/user/password',
                [
                    'model'    => $form,
                    'user'     => $this->repository->get($id),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @action User_UserController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = '#user_UserController_delete';
            $model    = $this->repository->get($id);
            try {
                $this->service->remove($model->id);
            }
            catch (StaleObjectException|Throwable $e) {
                PrintHelper::exception($actionId, 'Проблема с service->remove', $e);
            }
            Yii::$app->getSession()
                     ->setFlash(
                         'warning',
                         'Пользователь удален безвозвратно!',
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
    }
