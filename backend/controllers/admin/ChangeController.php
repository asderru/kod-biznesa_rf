<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\forms\Admin\ChangeSiteForm;
    use core\edit\useCases\Admin\ChangeSiteService;
    use core\helpers\ModelHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use core\edit\arrays\Admin\InformationEditor;
    use DomainException;
    use Exception;
    use Throwable;
    use Yii;
    use yii\web\Controller;
    use yii\web\Response;
    
    class ChangeController extends Controller
    {
        private const string MODEL_LABEL  = 'Смена сайта';
        private const string MODEL_PREFIX = 'SiteChange';
        private const string ACTION_INDEX = 'Admin_ChangeController_';
        private const string SERVICE_INDEX = 'ChangeSiteService_';
        
        private ChangeSiteService $service;
        
        public function __construct(
            $id,
            $module,
            ChangeSiteService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
        }
        
        /**
         * @action Admin_ChangeController_actionIndex
         */
        public function actionIndex(): string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            $sites = InformationEditor::getArray(['id', 'name']);
            
            return $this->render(
                '@app/views/admin/change/index',
                [
                    'sites' => $sites,
                    'actionId' => $actionId,
                    'textType' => null,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * @action Admin_ChangeController_actionView
         */
        public function actionView(int $siteId, int $textType): string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $site = ParametrHelper::getSite($siteId);
            $models = TypeHelper::getAllModels($siteId, $textType);
            
            return $this->render('view', [
                'site' => $site,
                'models'   => $models,
                'actionId' => $actionId,
                'textType' => $textType,
                'prefix'   => static::MODEL_PREFIX,
                'label'    => static::MODEL_LABEL,
            ]);
            
        }
        
        /**
         * @action Admin_ChangeController_actionUpdate
         */
        public function actionUpdate(int $textType, int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            if (ParametrHelper::isAlone()) {
                Yii::$app->getSession()->setFlash(
                    'warning',
                    'Смена сайта невозможна!',
                );
                return $this->redirect(Yii::$app->request->referrer ?: ['index']);
            }
            
            $parent = ModelHelper::getModel($textType, $id);
            
            if (!$parent) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new ChangeSiteForm();
            
            if (
                $form->load(Yii::$app->request->post())
            ) {
                try {
                    $this->service->edit($parent, $form);
                    
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            TypeHelper::getLongEditUrl($textType) . 'view',
                            'id' => $id,
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
            
            return $this->render('update', [
                'model'  => $form,
                'parent' => $parent,
                'actionId' => $actionId,
                'textType' => $textType,
                'prefix'   => static::MODEL_PREFIX,
                'label'    => static::MODEL_LABEL,
            ]);
        }
    }
