<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\entities\Admin\Edit;
    use core\edit\repositories\Admin\EditRepository;
    use core\edit\search\Admin\EditSearch;
    use core\helpers\ClearHelper;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use Exception;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class EditController extends Controller
    {
        private const int    TEXT_TYPE    = Edit::TEXT_TYPE;
        private const string MODEL_LABEL  = Edit::MODEL_LABEL;
        private const string MODEL_PREFIX = Edit::MODEL_PREFIX;
        private const string ACTION_INDEX = 'Admin_EditController_';
        
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
        
        private EditRepository $repository;
        
        public function __construct(
            $id,
            $module,
            EditRepository $repository,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
        }
        
        /**
         * Lists all Edit models.
         * @param ?int $siteId
         * @param ?int $textType
         * @param ?int $parentId
         * @return Response|string
         * @throws Exception
         * @action Admin_EditController_actionIndex
         */
        public function actionIndex(
            ?int $siteId = null,
            ?int $textType = null,
            ?int $parentId = null,
        ): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new EditSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $siteId,
                $textType,
                $parentId,
            );
            
            return $this->render(
                '@app/views/admin/edit/index',
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
         * Displays a single Edit model.
         * @param int $id
         * @return Response|string
         * @action Admin_EditController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $model = $this->repository::get($id);
            
            $parent = ParentHelper::getModel($model->text_type, $model->parent_id);
            
            return $this->render(
                '@app/views/admin/edit/view',
                [
                    'model'    => $model,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Deletes an existing Edit model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Admin_EditController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            if ($model) {
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
            }
            return $this->redirect(
                (ClearHelper::getAction() !== 'view')
                    ?
                    Yii::$app->request->referrer
                    :
                    'index',
            );
        }
    }
