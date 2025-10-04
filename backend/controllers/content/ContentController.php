<?php
    
    namespace backend\controllers\content;
    
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Content;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Library\Author;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Magazin\Article;
    use core\edit\entities\Magazin\Section;
    use core\edit\entities\Seo\News;
    use core\edit\entities\Shop\Brand;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\edit\forms\Content\ContentEditForm;
    use core\edit\repositories\ContentRepository;
    use core\edit\search\Content\ContentSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Content\ContentManageService;
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
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class ContentController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE     = Content::TEXT_TYPE;
        private const string MODEL_LABEL   = Content::MODEL_LABEL;
        private const string MODEL_PREFIX  = Content::MODEL_PREFIX;
        private const string ACTION_INDEX  = 'Content_ContentController_';
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
        
        /**
         * Lists all Content models.
         * @return Response|string
         * @throws Exception
         * @action Content_ContentController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new ContentSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/content/content/index',
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
         * Displays a single Content model.
         * @param integer $id
         * @return Response|string
         * @action Content_ContentController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/content/content/view',
                [
                    'model'    => $this->repository::get($id),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing Content model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return Response
         * @action ContentController_update
         */
        public function actionCopy(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionCopy';
            
            try {
                if ($this->service->copy($id)) {
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Контент успешно скопирован!',
                    );
                }
                return $this->redirect(
                    [
                        'index',
                    ],
                );
            }
            catch (DomainException|StaleObjectException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'copy',
                    $e,
                );
            }
            Yii::$app->session->
            setFlash(
                'warning',
                'Что-то пошло не так!',
            );
            return $this->redirect(
                'index',
            );
            
            return $this->redirect('index');
        }
        
        /**
         * Updates Content model
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @return Response
         * @throws Exception
         * @throws Throwable
         * @action ContentController__actionContents
         */
        public function actionContents(): Response
        {
            $modelsToCopy = [
                Razdel::class,
                Product::class,
                Brand::class,
                Section::class,
                Article::class,
                Book::class,
                Chapter::class,
                Category::class,
                Post::class,
                Page::class,
                News::class,
                Author::class,
            ];
            
            foreach ($modelsToCopy as $modelClass) {
                $models = $modelClass::find()
                                     ->all()
                ;
                
                if ($models) {
                    foreach ($models as $model) {
                        if ($model->status > Constant::STATUS_ROOT) {
                            $this->service->update($model);
                        }
                    }
                }
            }
            
            Yii::$app->session->
            setFlash(
                'success',
                'Контент успешно скопирован!',
            );
            return $this->redirect(
                ['index',],
            );
            
        }
        
        /**
         * Deletes an existing Content model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param integer $id
         * @return Response
         * @action Content_ContentController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель' . $id . ' не найдена!',
                );
                return $this->redirect(
                    [
                        'index',
                    ],
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
        
        /*####### Edit with AI ###################################################*/
        
        /**
         * Updates an existing Content model.
         * If update is successful, the browser will be redirected to the
         * 'view' content.
         * @param integer $id
         * @return string|Response
         * @action Content_ContentController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
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
            
            $form = new ContentEditForm($content);
            
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
                            'id' => $content->id,
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
                '@app/views/content/content/update',
                [
                    'model'    => $form,
                    'content'  => $content,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
            
        }
        
        /*####### Services ###################################################*/
        
        public function actionGetModels(
            ?int $textType, ?int $site_id,
        ): array
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $models = Content::find()
                             ->andWhere(['=', 'site_id', $site_id])
                             ->andWhere(['=', 'text_type', $textType])
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
    }
