<?php
    
    namespace backend\controllers;
    
    use common\auth\Identity;
    use core\edit\auth\LoginForm;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Auth\AuthService;
    use core\helpers\PrintHelper;
    use DomainException;
    use Throwable;
    use Yii;
    use yii\web\Controller;
    use yii\web\Response;
    
    class AuthController extends Controller
    {
        private const string  ACTION_INDEX  = 'AuthController_';
        private const string  SERVICE_INDEX = 'AuthService_';
        
        use RedirectControllerTrait;
        
        public $layout = 'main-login';
        
        private AuthService $authService;
        
        public function __construct(
            $id,
            $module,
            AuthService $authService,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->authService = $authService;
        }
        
        /**
         * @throws Throwable
         */
        public function actionLogin(): Response|string
        {
            $actionId = '#SiteController_login';
            if (!Yii::$app->user->isGuest) {
                return $this->goHome();
            }
            
            $this->layout = 'main-login';
            
            $image = Yii::getAlias('@static') . '/cache/site/col-6_14.jpg';
            
            $form = new LoginForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $user = $this->authService->auth($form);
                    
                    Yii::$app->user->login(new Identity($user), $form->rememberMe ? Yii::$app->params['user.rememberMeDuration'] : 0);
                    
                    return $this->goBack();
                }
                catch (DomainException $e) {
                    PrintHelper::exception($actionId, 'Проблема с ', $e);
                }
            }
            
            return $this->render(
                '@app/views/auth/login',
                [
                    'model' => $form,
                    'image' => $image,
                ],
            );
        }
        
        public function actionLogout(): Response
        {
            Yii::$app->user->logout();
            
            Yii::$app->getSession()
                     ->setFlash(
                         'warning',
                         'Вы покинули сайт',
                     )
            ;
            
            return $this->goHome();
        }
        
    }
