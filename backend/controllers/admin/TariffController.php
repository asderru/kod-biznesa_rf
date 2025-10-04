<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\traits\RedirectControllerTrait;
    use core\helpers\ClearHelper;
    use core\edit\entities\Admin\Tariff;
    use core\edit\search\Admin\TariffSearch;
    use core\helpers\PrintHelper;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\Exception;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class TariffController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int    TEXT_TYPE    = Tariff::TEXT_TYPE;
        private const string MODEL_LABEL  = Tariff::MODEL_LABEL;
        private const string MODEL_PREFIX = Tariff::MODEL_PREFIX;
        private const string ACTION_INDEX = 'Admin_TariffController_';
        
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
         * Lists all Tariff models.
         * @return Response|string
         * @action Admin_TariffController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new TariffSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/admin/tariff/index',
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
         * Displays a single Tariff model.
         * @param integer $id
         * @return Response|string
         * @throws NotFoundHttpException if the model cannot be found
         * @action Admin_TariffController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            return $this->render(
                '@app/views/admin/tariff/view',
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
         * Creates a new Tariff model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @throws Exception
         * @action Admin_TariffController_actionCreate_
         */
        public function actionCreate(): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $model = new Tariff();
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
            return $this->render(
                '@app/views/admin/tariff/create',
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
         * Updates an existing Tariff model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws NotFoundHttpException|Exception if the model cannot be found
         * @action Admin_TariffController_actionUpdate
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            $model = $this->findModel($id);
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->render(
                '@app/views/admin/tariff/update',
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
         * Deletes an existing Tariff model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @throws NotFoundHttpException if the model cannot be found
         * @action Admin_TariffController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->findModel($id);
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
        
        /**
         * Finds the Tariff model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return null|Tariff the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        public function findModel(int $id): null|Tariff
        {
            if (($model = Tariff::findOne($id)) !== null) {
                return $model;
            }
            
            throw new NotFoundHttpException('Страница отсутстсвует.');
        }
    }
