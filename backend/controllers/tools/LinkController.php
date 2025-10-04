<?php
    
    namespace backend\controllers\tools;
    
    use core\edit\entities\Tools\Link;
    use core\edit\search\Admin\LinkSearch;
    use core\edit\useCases\Admin\LinkManageService;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\Exception;
    use yii\db\StaleObjectException;
    use yii\web\Controller;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class LinkController extends Controller
    {
        private const int     TEXT_TYPE     = Link::TEXT_TYPE;
        private const string  MODEL_LABEL   = Link::MODEL_LABEL;
        private const string  MODEL_PREFIX  = Link::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Tools_LinkController_';
        
        private LinkManageService $service;
        
        public function __construct(
            $id,
            $module,
            LinkManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
        }
        
        #[ArrayShape([
            'error' => 'string[]',
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
         * Displays homepage.
         * @return Response|string
         * @action LinkController_index
         * @throws Throwable
         * @action Tools_LinkController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new LinkSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/admin/edit/index',
                [
                    'dataProvider' => $dataProvider,
                    'searchModel'  => $searchModel,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix'       => static::MODEL_PREFIX,
                    'label'        => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Проверяет все ссылки на всех страницах сайта (в контенте и обвязке) в зависимости от типа ссылки.
         * @param int $id
         * @param int $linkType
         * @return Response|string
         * @action LinkController_index
         */
        public function actionCheckSite(int $id, int $linkType): Response|string
        {
            $this->service->checkSiteUrl($id, $linkType);
            
            return $this->redirect(
                'index',
            );
        }
        
        /**
         * Проверяет все сломанные ссылки на всех страницах сайта (в контенте и обвязке).
         * @param int $id
         * @return Response|string
         * @action LinkController_index
         * @throws Throwable
         */
        public function actionCheckBroken(int $id): Response|string
        {
            $this->service->checkSiteLinksBroken($id);
            
            Yii::$app->getSession()
                     ->setFlash(
                         'danger',
                         'Все битые ссылки сайта получены!',
                     )
            ;
            return $this->redirect(
                'index',
            );
        }
        
        /**
         * Проверяет страницы - работают или нет.
         * @param int $id
         * @return Response|string
         * @action LinkController_index
         * @throws Throwable
         */
        public function actionCheckPages(int $id): Response|string
        {
            $this->service->checkSitePages($id);
            
            Yii::$app->getSession()
                     ->setFlash(
                         'success',
                         'Статус всех страниц сайта проверен!',
                     )
            ;
            return $this->redirect(
                'index',
            );
        }
        
        /**
         * Проверяются все ссылки в контенте.
         * @param int $siteId
         * @param int $typeId
         * @param int $parentId
         * @return Response|string
         * @throws Throwable
         * @action LinkController_index
         */
        public function actionCheckUrl(
            int $siteId,
            int $typeId,
            int $parentId,
        ): Response|string
        {
            $this->service->checkUrl($siteId, $typeId, $parentId);
            
            return $this->redirect(
                TypeHelper::getView($typeId, $parentId),
            );
        }
        
        /**
         * Проверяются все ссылки на странице.
         * @param int $siteId
         * @param int $typeId
         * @param int $parentId
         * @return Response|string
         * @throws Throwable
         * @action LinkController_index
         */
        public function actionCheckModel(
            int $siteId,
            int $typeId,
            int $parentId,
        ): Response|string
        {
            $this->service->checkModel($siteId, $typeId, $parentId);
            
            return $this->redirect(
                TypeHelper::getView($typeId, $parentId),
            );
        }
        
        
        /**
         * Удаляется ссылка  с $id.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action Tools_LinkController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = Link::findOne([
                'id' => $id,
            ]);
            if (!$model) {
                return $this->redirect(
                    'index',
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
                'index',
            );
        }
        
        
        /**
         * Удаляются все ссылки из БД Link.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @return Response
         * @action PeriodController_delete
         * @throws Exception
         */
        public function actionDeleteAlllinks(): Response
        {
            Yii::$app->db->createCommand()->truncateTable('content_links')->execute();
            return $this->redirect(
                'index',
            );
        }
        
        /**
         * Удаляются все ссылки со статусом $status.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param int $status
         * @return Response
         * @action PeriodController_delete
         */
        public function actionDeleteStatus(int $status): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDeleteStatus';
            
            $models = Link::findAll([
                'status' => $status,
            ]);
            foreach ($models as $model) {
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
            
            Yii::$app->getSession()
                     ->setFlash(
                         'success',
                         'Удалены все ссылки со статусом #' . $status,
                     )
            ;
            
            return $this->redirect(
                'index',
            );
        }
        
        
    }
