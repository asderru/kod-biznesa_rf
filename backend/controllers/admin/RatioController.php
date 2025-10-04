<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\entities\Admin\PhotoRatio;
    use core\edit\forms\Admin\PhotoRatioForm;
    use core\edit\repositories\Admin\PhotoRatioRepository;
    use core\edit\repositories\Admin\PhotoSizeRepository;
    use core\edit\search\Admin\PhotoRatioSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Admin\PhotoRatioManageService;
    use core\helpers\PrintHelper;
    use DomainException;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use Yii;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class RatioController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = PhotoRatio::TEXT_TYPE;
        private const string  MODEL_LABEL   = PhotoRatio::MODEL_LABEL;
        private const string  MODEL_PREFIX  = PhotoRatio::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Admin_RatioController_';
        private const string  SERVICE_INDEX = 'PhotoRatioManageService_';
        
        private PhotoRatioRepository    $repository;
        private PhotoRatioManageService $service;
        private PhotoSizeRepository     $photos;
        
        public function __construct(
            $id,
            $module,
            PhotoRatioManageService $service,
            PhotoRatioRepository $repository,
            PhotoSizeRepository $photos,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
            $this->photos     = $photos;
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
         * Lists all PhotoRatio models.
         * @return Response|string
         * @action Admin_RatioController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new PhotoRatioSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/admin/ratio/index',
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
         * Displays a single PhotoSize model.
         * @param integer $id
         * @return Response|string
         * @action Admin_RatioController_actionView
         */
        public function actionView(int $id):
        Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/admin/ratio/view',
                [
                    'model'    => $this->repository::get($id),
                    'photo'    => $this->photos::get($id),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing PhotoRatio model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @action Admin_RatioController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $ratio = $this->repository::get($id);
            
            if (!$ratio) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect(
                    [
                        'index',
                    ],
                );
            }
            
            $form = new PhotoRatioForm($ratio);
            
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
                            'id' => $ratio->id,
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
                '@app/views/admin/ratio/update',
                [
                    'model'    => $form,
                    'ratio'    => $ratio,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
    }
