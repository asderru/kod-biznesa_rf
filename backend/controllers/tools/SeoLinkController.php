<?php
    
    namespace backend\controllers\tools;
    
    use core\edit\entities\Tools\SeoLink;
    use core\edit\search\Tools\SeoLinkSearch;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Tools\SeoLinkManageService;
    use Throwable;
    use Yii;
    use yii\db\Exception;
    use yii\web\Controller;
    use yii\web\Response;
    
    class SeoLinkController extends Controller
    {
        use RedirectControllerTrait;
        
        private const int     TEXT_TYPE     = SeoLink::TEXT_TYPE;
        private const string  MODEL_LABEL   = SeoLink::MODEL_LABEL;
        private const string  MODEL_PREFIX  = SeoLink::MODEL_PREFIX;
        private const string  ACTION_INDEX  = 'Tools_SeoLinkController_';
        private const string  SERVICE_INDEX = 'SeoLinkManageService_';
        
        private SeoLinkManageService $service;
        
        public function __construct(
            $id,
            $module,
            SeoLinkManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
        }
        
        
        /**
         * Lists all SeoLink models.
         * @return Response|string
         * @throws Throwable
         * @action Tools_SeoLinkController_actionIndex
         */
        public function actionIndex(): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new SeoLinkSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            return $this->render(
                '@app/views/tools/seo-link/index',
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
         * Creates a new Anons model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int    $siteId
         * @param int    $textType
         * @param int    $parentId
         * @param int    $contentId
         * @param string $name
         * @param string $url
         * @return string|Response
         * @throws Exception
         * @action Tools_SeoLinkController_actionCreate_
         */
        public function actionCreate(
            int    $siteId,
            int    $textType,
            int    $parentId,
            int    $contentId,
            string $name,
            string $url,
        ): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate';
            
            $oldName = (SeoLink::find()
                               ->where([
                                   '=',
                                   'site_id',
                                   $siteId,
                               ])
                               ->andWhere([
                                   '=',
                                   'name',
                                   $name,
                               ])
                               ->one()
            );
            
            if ($oldName !== null) {
                Yii::$app->getSession()
                         ->setFlash(
                             'warning',
                             'Такая ссылка существует и ' . $name . ' уже занято!',
                         )
                ;
                return $this->redirect(['index']);
            }
            
            $model = new SeoLink();
            
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->service::createSeoLink(
                    $textType,
                    $parentId,
                    $name,
                );
                return $this->redirect(['index']);
            }
            
            return $this->render(
                '@app/views/tools/seo-link/create',
                [
                    'model'     => $model,
                    'siteId'    => $siteId,
                    'parentId'  => $parentId,
                    'contentId' => $contentId,
                    'name'      => $name,
                    'url'       => $url,
                    'actionId'  => $actionId,
                    'textType'  => static::TEXT_TYPE,
                    'prefix'    => static::MODEL_PREFIX,
                    'label'     => static::MODEL_LABEL,
                ],
            );
        }
        
        
    }
