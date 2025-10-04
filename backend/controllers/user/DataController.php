<?php
    
    namespace backend\controllers\user;
    
    use core\edit\entities\User\UserData;
    use core\edit\search\User\UserDataSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use Throwable;
    use Yii;
    use yii\db\Exception;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class DataController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE    = UserData::TEXT_TYPE;
        private const string MODEL_LABEL  = UserData::MODEL_LABEL;
        private const string MODEL_PREFIX = UserData::MODEL_PREFIX;
        private const string ACTION_INDEX = 'User_DataController_';
        
        /**
         * @inheritDoc
         */
        public function behaviors(): array
        {
            return array_merge(
                parent::behaviors(),
                [
                    'verbs' => [
                        'class'   => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ],
            );
        }
        
        /**
         * Lists all UserData models.
         * @return string|Response
         * @action User_DataController_actionIndex
         */
        public function actionIndex(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new UserDataSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);
            
            return $this->render(
                '@app/views/user/data/index',
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
         * Displays a single UserData model.
         * @param int $id ID
         * @return string|Response
         * @throws NotFoundHttpException if the model cannot be found
         * @action User_DataController_actionView
         */
        public function actionView(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/user/data/view',
                [
                    'model'    => $this->findModel($id),
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Creates a new UserData model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @throws Exception
         * @action User_DataController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $model = new UserData();
            
            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            else {
                $model->loadDefaultValues();
            }
            
            return $this->render(
                '@app/views/user/data/create',
                [
                    'model'    => $model,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Updates an existing UserData model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param int $id ID
         * @return string|Response
         * @throws NotFoundHttpException|Exception if the model cannot be found
         * @action User_DataController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $model = $this->findModel($id);
            
            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
            return $this->render(
                '@app/views/user/data/update',
                [
                    'model'    => $model,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Deletes an existing UserData model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param int $id ID
         * @return Response
         * @throws NotFoundHttpException if the model cannot be found
         * @action User_DataController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            
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
        
        /**
         * Finds the UserData model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param int $id ID
         * @return UserData the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        private function findModel(int $id): UserData
        {
            if (($model = UserData::findOne(['id' => $id])) !== null) {
                return $model;
            }
            
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
