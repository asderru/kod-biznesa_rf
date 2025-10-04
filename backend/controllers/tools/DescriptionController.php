<?php
    
    namespace backend\controllers\tools;
    
    use core\edit\entities\Content\Content;
    use core\edit\forms\Content\DescriptionEditForm;
    use core\edit\repositories\ContentRepository;
    use core\edit\search\Content\ContentSearch;
    use core\edit\useCases\Content\ContentManageService;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use DomainException;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class DescriptionController extends Controller
    {
        private const int    TEXT_TYPE     = Constant::DESCRIPTION_TYPE;
        private const string MODEL_LABEL   = 'Мета-описание';
        private const string MODEL_PREFIX  = 'descriptions';
        private const string ACTION_INDEX  = 'Tools_DescriptionController_';
        private const string SERVICE_INDEX = 'ContentManageService_';
        
        private ContentRepository    $repository;
        private ContentManageService $service;
        
        public function __construct(
            $id,
            $module,
            ContentRepository $repository,
            ContentManageService $service,
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
        
        /*####### Edit with AI ###################################################*/
        
        #[ArrayShape([
            'error'    => 'string[]',
            'nodeMove' => 'string[]',
        ])]
        public function actions(): array
        {
            return [
                'error' => [
                    'class' => ErrorAction::class,
                ],
            ];
        }
        
        /*####### Services ###################################################*/
        
        /**
         * Lists all Content models.
         * @return Response|string
         * @throws Exception
         * @action Tools_DescriptionController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new ContentSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/content/description/index',
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
         * Updates an existing Content model.
         * If update is successful, the browser will be redirected to the
         * 'view' content.
         * @param integer $id
         * @return string|Response
         * @action Tools_DescriptionController_actionView
         */
        public function actionView(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $this->layout = '@app/views/layouts/simple';
            
            $content = $this->repository::get($id);
            
            if (!$content) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Контент не найден!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $nextId = $content->getNextModel()?->id;
            
            $parent = self::actionGetModel($content->text_type, $content->parent_id);
            
            $form = new DescriptionEditForm($content);
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->editDescription($id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        ($nextId)
                            ?
                            [
                                'view',
                                'id' => $nextId,
                            ]
                            :
                            'index',
                    );
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception($actionId, static::SERVICE_INDEX . '_editDescription', $e);
                }
            }
            
            return $this->render(
                '@app/views/content/description/view',
                [
                    'model'    => $form,
                    'content'  => $content,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        public static function actionGetModel(
            ?int $textType, ?int $parentId,
        ): ?ActiveRecord
        {
            return ParentHelper::getModel($textType, $parentId);
        }
        
        public static function actionGetModels(
            ?int $textType, ?int $site_id,
        ): array
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $models = self::actionGetQuery($textType, $site_id)
                          ->all()
            ;
            
            $options = [];
            foreach ($models as $model) {
                $options[] = [
                    'id'   => $model->id,
                    'name' => $model->name,
                ];
            }
            
            return $options;
        }
        
        public static function actionGetQuery(
            ?int $textType, ?int $site_id,
        ): ActiveQuery
        {
            return Content::find()
                          ->andWhere(['=', 'text_type', $textType])
                          ->andWhere(['=', 'site_id', $site_id])
            ;
        }
        
        
    }
