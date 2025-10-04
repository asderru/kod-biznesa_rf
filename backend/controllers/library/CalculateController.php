<?php
    
    namespace backend\controllers\library;
    
    use core\edit\entities\Library\Book;
    use core\edit\repositories\Library\BookRepository;
    use core\edit\useCases\Library\CalculateService;
    use core\edit\entities\Library\Chapter;
    use Exception;
    use himiklab\sortablegrid\SortableGridAction;
    use JetBrains\PhpStorm\ArrayShape;
    use JetBrains\PhpStorm\Pure;
    use Yii;
    use yii\web\Controller;
    use yii\web\Response;
    
    /**
     * CalculateController implements the CRUD actions for Chapter model.
     */
    class CalculateController extends Controller
    {
        private BookRepository   $repository;
        private CalculateService $service;
        private const string MODEL_LABEL = 'Подсчет слов';
        
        private const int     TEXT_TYPE    = 99;
        private const string  MODEL_PREFIX = 'calculate';
        private const string  ACTION_INDEX = 'Library_CalculateController_';
        
        public function __construct(
            $id,
            $module,
            BookRepository $repository,
            CalculateService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
        }
        
        #[Pure]
        #[ArrayShape([
            'sort' => 'array',
        ])]
        public function actions(): array
        {
            return [
                'sort' => [
                    'class'     => SortableGridAction::className(),
                    'modelName' => Chapter::className(),
                ],
            ];
        }
        
        /**
         * Lists all Chapter models.
         * @return Response|string
         * @throws Exception
         * @action Library_CalculateController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $models = Book::find()
                          ->thisNodes()
                          ->noRoots()
                          ->sorted()
                          ->all()
            ;
            
            return $this->render(
                '@app/views/library/calculate/index',
                [
                    'models'   => $models,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Chapter model.
         * @param integer $id
         * @param ?string $type
         * @return Response|string
         * @throws Exception
         * @action Library_CalculateController_actionView
         */
        public function actionView(int $id, ?string $type = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            $childs = ($model->hasChildren()) ?
                $model->children()->all() : null;
            
            ($type !== 'lex')
                ?
                $content = $this->service->calculate($model, $type)
                :
                $content = $this->service->calculateLexems($model, $type);
            
            return $this->render(
                '@app/views/library/calculate/view',
                [
                    'model'    => $model,
                    'childs'   => $childs,
                    'content'  => $content,
                    'type'     => $type,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Chapter model.
         * @param ?string $type
         * @return Response|string
         * @throws Exception
         * @action Library_CalculateController_actionViewAll
         */
        public function actionViewAll(string|null $type = null):
        Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionViewAll';
            
            $books = Book::find()
                         ->thisNodes()
                         ->noRoots()
                         ->sorted()
                         ->all()
            ;
            ($type !== 'lex')
                ?
                $content = $this->service->calculateAll($type)
                :
                $content = $this->service->calculateAllLexems();
            
            // Подсчет слов в $content
            $wordCount = count($content);
            
            //PrintHelper::print($content);
            
            return $this->render(
                '@app/views/library/calculate/viewAll',
                [
                    'type'      => $type,
                    'content'   => $content,
                    'wordCount' => $wordCount,
                    'books'     => $books,
                    'actionId'  => $actionId,
                    'textType'  => static::TEXT_TYPE,
                    'prefix'    => static::MODEL_PREFIX,
                    'label'     => static::MODEL_LABEL,
                ],
            );
            
        }
        
    }
