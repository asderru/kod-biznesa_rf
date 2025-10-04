<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\forms\ModelCopyForm;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Admin\ModelManageService;
    use core\helpers\ModelHelper;
    use core\helpers\ParametrHelper;
    use JetBrains\PhpStorm\ArrayShape;
    use Yii;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class CopyController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE      = 99;
        private const string MODEL_LABEL    = 'Копирование';
        private const string MODEL_PREFIX   = 'copy';
        private const string ACTION_INDEX   = 'Admin_CopyController_';
        private const string ACTION_SERVICE = 'ModelManageService_';
        private ModelManageService $service;
        
        public function __construct(
            $id,
            $module,
            ModelManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
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
         * @param int $textType
         * @param int $parentId
         * @return Response|string
         * @action Admin_CopyController_actionIndex
         */
        public function actionIndex(int $textType, int $parentId): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $sites = ParametrHelper::getSites();
            
            $source = ModelHelper::getModel($textType, $parentId);
            
            $form = new ModelCopyForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                $this->service->copy($source, $form);
            }
            
            return $this->render(
                '@app/views/admin/copy/index',
                [
                    'sites'    => $sites,
                    'source'   => $source,
                    'model'    => $form,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        
    }
