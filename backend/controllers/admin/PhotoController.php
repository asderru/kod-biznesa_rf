<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\entities\Admin\PhotoSize;
    use core\edit\forms\Admin\PhotoSizeForm;
    use core\edit\repositories\Admin\PhotoRatioRepository;
    use core\edit\repositories\Admin\PhotoSizeRepository;
    use core\edit\search\Admin\PhotoSizeSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Admin\PhotoSizeManageService;
    use core\helpers\PrintHelper;
    use DomainException;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use Yii;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class PhotoController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = PhotoSize::TEXT_TYPE;
        private const string  MODEL_LABEL   = PhotoSize::MODEL_LABEL;
        private const string  MODEL_PREFIX  = PhotoSize::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Admin_PhotoController_';
        private const string  SERVICE_INDEX = 'PhotoSizeManageService_';
        
        private PhotoSizeManageService $service;
        private PhotoSizeRepository    $repository;
        private PhotoRatioRepository   $ratios;
        
        
        public function __construct(
            $id,
            $module,
            PhotoSizeManageService $service,
            PhotoSizeRepository $repository,
            PhotoRatioRepository $ratios,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service    = $service;
            $this->repository = $repository;
            $this->ratios     = $ratios;
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
         * Lists all PhotoSize models.
         * @return Response|string
         * @action Admin_ActionController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new PhotoSizeSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/admin/photo/index',
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
         * @action Admin_ActionController_actionView
         */
        public function actionView(int $id):
        Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/admin/photo/view',
                [
                    'model'    => $this->repository::get($id),
                    'ratio'    => $this->ratios::get($id),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing PhotoSize model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @action Admin_ActionController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $photo = $this->repository::get($id);
            
            if (!$photo) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Фото  #' . $id . ' не найдено!',
                );
                return $this->redirect(
                    [
                        'index',
                    ],
                );
            }
            
            $form = new PhotoSizeForm($photo);
            
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
                            'id' => $photo->id,
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
                '@app/views/admin/photo/update',
                [
                    'model'    => $form,
                    'photo'    => $photo,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
    }
