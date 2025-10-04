<?php
    
    namespace backend\controllers\content;
    
    use core\edit\editors\Content\PageEditor;
    use core\edit\entities\Content\Page;
    use core\edit\forms\ColorForm;
    use core\edit\forms\Content\PageForm;
    use core\edit\forms\ModelEditForm;
    use core\edit\forms\SlugEditForm;
    use core\edit\forms\Tools\LinkForm;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\repositories\Content\PageRepository;
    use core\edit\repositories\Tools\DraftRepository;
    use core\edit\search\Content\PageSearch;
    use core\edit\traits\ActionControllerTrait;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Content\PageManageService;
    use core\edit\useCases\Photo\ImageProcessorService;
    use core\edit\useCases\Tools\DraftManageService;
    use core\edit\widgets\nestable\NodeMoveAction;
    use core\helpers\ClearHelper;
    use core\helpers\FormatHelper;
    use core\helpers\ImageHelper;
    use core\helpers\ModelHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\helpers\ReadHelper;
    use core\helpers\StatusHelper;
    use core\tools\Constant;
    use core\tools\params\Label;
    use core\tools\params\Parametr;
    use core\tools\params\Prefix;
    use DomainException;
    use Exception;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    /**
     * PageController implements the CRUD actions for Page model.
     *
     * @property-read array $roots
     */
    class PageController extends Controller
    {
        use ActionControllerTrait;
        use RedirectControllerTrait;
        
        protected const int    TEXT_TYPE     = Page::TEXT_TYPE;
        protected const string ACTION_INDEX  = 'Content_PageController_';
        protected const string SERVICE_INDEX = 'PageManageService_';
        
        private PageRepository        $repository;
        private PageEditor $editor;
        private PageManageService     $service;
        private ImageProcessorService $imageService;
        private DraftRepository       $drafts;
        private DraftManageService    $draftService;
        
        public function __construct(
            $id,
            $module,
            PageRepository $repository,
            PageEditor $editor,
            PageManageService $service,
            ImageProcessorService $imageService,
            DraftRepository $drafts,
            DraftManageService $draftService,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository   = $repository;
            $this->editor = $editor;
            $this->service      = $service;
            $this->imageService = $imageService;
            $this->drafts       = $drafts;
            $this->draftService = $draftService;
        }
        
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
        
        public function actions(): array
        {
            return [
                'nodeMove' => [
                    'class'     => NodeMoveAction::class,
                    'modelName' => Page::className(),
                ],
            ];
        }
        
        /**
         * @throws Throwable
         * @throws Exception
         * @action Content_PageController_actionIndex
         */
        public function actionIndex(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionIndex';
            
            $searchModel  = new PageSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
                $id,
            );
            $pageSize     = Yii::$app->request->get('pageSize', 50);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            return $this->render(
                '@app/views/content/page/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'textType'     => static::TEXT_TYPE,
                    'prefix' => Prefix::page(),
                    'label' => Label::page(),
                ],
            );
        }
        
        /**
         * @action Content_PageController_actionResort
         */
        public function actionResort(?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionResort';
            
            $siteId = $id ?? Parametr::siteId();
            $site   = $this->sites::get($siteId);
            
            if (!$site) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Сайт  #' . $id . ' не найден!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $arrayModels = PageEditor::getArray(array_merge(Page::DEFAULT_FIELDS, ['description']), null, $id);
            
            //PrintHelper::print($arrayModels);
            //объект ActiveQuery содержащий данные для дерева. depth = 0 - корень.
            $query = (new Page)::getTree($id);
            
            return $this->render(
                '@app/views/layouts/sort/resort',
                [
                    'query'       => $query,
                    'site'        => $site,
                    'arrayModels' => $arrayModels,
                    'actionId'    => $actionId,
                    'textType'    => static::TEXT_TYPE,
                    'prefix' => Prefix::page(),
                    'label' => Label::page(),
                ],
            );
        }
        
        /**
         * Displays a single Page model.
         * @param integer $id
         * @return Response|string
         * @action PageController_view
         * @throws Throwable
         * @action Content_PageController_actionView
         */
        public function actionView(int $id): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            $package = $this->editor::getFullPackedPageById($id);
            $model   = $package['model'];
            
            if (!$model['id']) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $uploadForm = new UploadPhotoForm();
            
            if (Yii::$app->request->isPost) {
                $uploadForm->imageFile = UploadedFile::getInstance($uploadForm, 'imageFile');
                if ($uploadForm->upload($model, static::TEXT_TYPE)) {
                    $this->imageService->convertToWeb($model, static::TEXT_TYPE);
                    // file is uploaded successfully
                    return $this->redirect(
                        [
                            'view',
                            'id' => ModelHelper::getId($model),
                        ],
                    );
                }
            }
            
            return $this->render(
                '@app/views/content/page/view',
                [
                    'model'             => $model,
                    'parents'           => $package['parents'],
                    'children'          => $package['children'],
                    'editItems'         => $package['editItems'],
                    'drafts'            => $package['drafts'],
                    'notes'             => $package['notes'],
                    'footnotes'         => $package['footnotes'],
                    'faqs'              => $package['faqs'],
                    'reviews'           => $package['reviews'],
                    'prevModel'         => $package['prevModel'],
                    'nextModel'         => $package['nextModel'],
                    'contentCard'       => $package['contentCard'],
                    'contentCardFields' => $package['contentCardFields'],
                    'galleries'         => $package['galleries'],
                    'assignedGalleries' => $package['assignedGalleries'],
                    'keywords'          => $package['keywords'],
                    'tags'              => $package['tags'],
                    'uploadForm'        => $uploadForm,
                    'actionId'          => $actionId,
                    'textType'          => static::TEXT_TYPE,
                    'prefix' => Prefix::page(),
                    'label' => Label::page(),
                ],
            );
        }
        
        /**
         * @throws Throwable
         * @action Content_PageController_actionCreate_
         */
        public function actionCreate(?int $id = null): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionCreate_';
            $site     = ParametrHelper::getSite();
            $page     = null;
            if ($id) {
                $page = $this->repository::get($id);
            }
            
            $form           = new PageForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            // Если валидация прошла успешно
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $model = $this->service->create($form);
                Yii::$app->session->
                setFlash(
                    'success',
                    'Страница успешно создана!',
                );
                return $this->redirect(
                    [
                        'view',
                        'id' => ModelHelper::getId($model),
                    ],
                );
            }
            
            return $this->render(
                '@app/views/content/page/create',
                [
                    'model'    => $form,
                    'site'     => $site,
                    'page'     => $page,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix' => Prefix::page(),
                    'label' => Label::page(),
                ],
            );
        }
        
        /**
         * Updates an existing Page model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action Content_PageController_actionUpdate_
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate_';
            $site     = ParametrHelper::getSite();
            
            $page = $this->repository::get($id);
            
            if (!$page) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            if ($page->status === Constant::STATUS_ROOT) {
                return $this->redirect(
                    [
                        'update-root',
                    ],
                );
            }
            
            $form           = new PageForm($page);
            $form->scenario = $form::SCENARIO_UPDATE_POST;
            
            if (
                !$form->load(Yii::$app->request->post())
            ) {
                self::startEditTime(
                    $page,
                    static::TEXT_TYPE,
                );
            }
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->edit($id, $form);
                    self::finishEditTime(static::TEXT_TYPE, $page->id, ReadHelper::getWordCount($form->text));
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $page->id,
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
                '@app/views/content/page/update',
                [
                    'model'    => $form,
                    'page'     => $page,
                    'site'     => $site,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix' => Prefix::page(),
                    'label' => Label::page(),
                ],
            );
            
        }
        
        /**
         * Updates an existing Page model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action ContentPageController_actionUpdateHtml
         */
        public function actionUpdateHtml(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdateHtml';
            
            $page = $this->repository::get($id);
            
            if (!$page) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new ModelEditForm($page);
            
            $nextId = $page->getNextModel()?->id;
            
            if (
                !$form->load(Yii::$app->request->post())
            ) {
                self::startEditTime(
                    $page,
                    static::TEXT_TYPE,
                );
            }
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->expressEdit($id, $form);
                    self::finishEditTime(static::TEXT_TYPE, $page->id, ReadHelper::getWordCount($form->text));
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    $action = Yii::$app->request->post('action');
                    
                    if ($action === 'view') {
                        return $this->redirect(['view', 'id' => $page->id]);
                    }
                    return ($nextId)
                        ?
                        $this->redirect(
                            [
                                'update-html',
                                'id' => $nextId,
                            ],
                        )
                        : $this->redirect([
                            'index',
                        ]);
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
                '@app/views/layouts/templates/updateHtml',
                [
                    'model'    => $form,
                    'parent'   => $page,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix' => Prefix::page(),
                    'label' => Label::page(),
                ],
            );
            
        }
        
        /**
         * @action ContentPageController_actionUpdateSlug
         */
        public function actionUpdateSlug(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionUpdateSlug';
            
            $page = $this->repository::get($id);
            
            if (!$page) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new SlugEditForm($page);
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->editSlug($id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(['view', 'id' => $id]);
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'editSlug',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/updateSlug',
                [
                    'model'    => $form,
                    'parent'   => $page,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix' => Prefix::page(),
                    'label' => Label::page(),
                ],
            );
            
        }
        
        /**
         * Updates an existing Page model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception|Throwable
         * @action ContentPageController_actionColor
         */
        public function actionColor(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionColor';
            
            $parent = $this->repository::get($id);
            
            if (!$parent) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new ColorForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->color($parent->id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Цветовой профиль изменен!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id'       => $parent->id,
                            'actionId' => $actionId,
                        ],
                    );
                }
                catch (DomainException|Exception $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'color',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/color',
                [
                    'model'    => $form,
                    'parent'   => $parent,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'label' => Label::page(),
                    'prefix' => Prefix::page(),
                ],
            );
            
        }
        
        /**
         * @param integer $id
         * @return string|Response
         * @throws Exception|Throwable
         * @action ContentPageController_actionSeolink
         */
        public function actionSeolink(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionSeolink';
            
            $page = $this->repository::get($id);
            
            if (!$page) {
                return $this->redirect([
                    'index',
                ]);
            }
            
            $form = new LinkForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->setSeolink($id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $page->id,
                        ],
                    );
                }
                catch (DomainException|Exception $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'setSeolink',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/_editSeoLink.php',
                [
                    'model'    => $form,
                    'parent'   => $page,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix' => Prefix::page(),
                    'label' => Label::page(),
                ],
            );
            
        }
        
        /**
         * Updates an existing Page model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         */
        public function actionBing(int $id): string|Response
        {
            $actionId = static::ACTION_INDEX . 'actionBing';
            
            $page = $this->repository::get($id);
            
            if (!$page) {
                return $this->redirect([
                    'index',
                ]);
            }
            
            try {
                $this->service->bing($id);
                Yii::$app->getSession()
                         ->setFlash(
                             'info',
                             'Index добавлен!',
                         )
                ;
            }
            catch (DomainException|Exception|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'bing',
                    $e,
                );
            }
            
            return $this->redirect(
                [
                    'view',
                    'id' => $page->id,
                ],
            );
        }
        
        /**
         * Deletes an existing Page model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @throws Throwable
         * @action Content_PageController_actionDelete
         */
        public function actionDelete(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionDelete';
            
            $model = $this->repository::get($id);
            
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
            
            try {
                $this->actionDeletePhoto($model->id);
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'this->actionDeletePhoto',
                    $e,
                );
            }
            
            try {
                $this->service->remove($model->id);
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Страница успешно удалена!',
                );
            }
            catch (DomainException|StaleObjectException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'remove',
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
         * Updates Content model
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return Response
         * @action Content_PageController_actionContent_
         */
        public function actionContent(int $id): Response
        {
            $actionId = static::ACTION_INDEX . 'actionContent_';
            
            $model = $this->repository::get($id);
            
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
            try {
                $this->service->copyContent($model);
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Контент успешно скопирован!',
                );
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'copyContent',
                    $e,
                );
            }
            return $this->redirect(
                [
                    'view',
                    'id' => $id,
                ],
            );
        }
        
        /**
         * Updates Content model
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @return Response
         * @throws Exception
         * @action Content_PageController_actionContents
         */
        public function actionContents(): Response
        {
            $actionId = static::ACTION_INDEX . 'actionContents';
            
            $models = Page::find()
                          ->noRoots()
                          ->thisSite()
                          ->all()
            ;
            
            if (!$models) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модели не найдены!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            try {
                foreach ($models as $model) {
                    $this->service->copyContent($model);
                }
                
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Контент успешно скопирован!',
                );
            }
            catch (DomainException|Throwable $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'copyContent',
                    $e,
                );
            }
            return $this->redirect(
                [
                    'index',
                ],
            );
        }
        
        /**
         * Displays a single Post model.
         * @return Response|string
         * @throws Throwable
         * @action PostController_view
         */
        public function actionClearCache(): Response|string
        {
            if ($this->service->clearCache()) {
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Кэш страниц очищен!',
                         )
                ;
            }
            return $this->redirect(Yii::$app->request->referrer ?: 'index');
            
        }
        
        /*####### Photo ######################################################*/
        
        /**
         * @throws Exception
         */
        public function actionDeletePhoto(
            int $id,
        ): string|Response
        {
            $model = $this->repository::get($id);
            
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
            
            (new UploadPhotoForm)::deletePhoto($model);
            $model->deletePhotos($model->slug);
            
            return $this->redirect(
                [
                    'view',
                    'id' => $id,
                ],
            );
        }
        
        
        /*####### Active, Draft, Archive #####################################*/
        
        /**
         * @param integer $id
         * @param int     $statusId
         * @return Response
         * @throws Throwable
         */
        public function actionStatus(int $id, int $statusId): Response
        {
            $actionId = static::ACTION_INDEX . '32_actionStatus';
            
            try {
                $this->service->status($id, $statusId);
            }
            catch (DomainException $e) {
                PrintHelper::exception(
                    $actionId,
                    'Проблема: ' . static::SERVICE_INDEX . 'status',
                    $e,
                );
            }
            return $this->redirect(
                Yii::$app->request->referrer
                    ?:
                    [
                        'view',
                        'id' => $id,
                    ],
            );
        }
        
        /*####### Services ###################################################*/
        
        public function actionViewAjax(int $id): string|Response
        {
            return $this->renderAjax(
                '@app/views/content/page/_sort',
                [
                    'model' => $this->repository::get($id),
                ],
            );
        }
        
        public function actionLists(int $status, int $id): void
        {
            $models = Page::find()
                          ->andWhere(
                              [
                                  '=',
                                  'site_id',
                                  $id,
                              ],
                          )
                          ->andWhere(
                              [
                                  '>=',
                                  'status',
                                  $status,
                              ],
                          )
                          ->sorted()
                          ->all()
            ;
            
            if (!empty($models)) {
                foreach ($models as $model) {
                    $time   = $model->depth - $status;
                    $indent = ($model->depth !== Constant::STATUS_ROOT
                        ?
                        str_repeat('&nbsp;&nbsp;', $time)
                        .
                        str_repeat('	&middot;', $time) . ' '
                        :
                        ''
                    );
                    echo "<option value='" . $model->id . "'>" . $indent .
                         $model->name . ' (' . $model->id . ')</option>';
                }
            }
            else {
                echo '<option> На этом сайте страниц нет! Создайте корневую страницу!</option>';
            }
        }
        
        /*###### Roots #######################################################*/
        
        /**
         * Updates an existing StrictPage model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param ?int $id
         * @return string|Response
         * @action PageController_updateRoot
         * @throws Exception
         */
        public function actionUpdateRoot(?int $id = null): string|Response
        {
            $actionId = '#content_PageController_update_root';
            
            $id = $id ?? Parametr::siteId();
            $root     = Page::find()
                            ->where(
                                [
                                    '=',
                                    'site_id',
                                    $id,
                                ],
                            )
                            ->andWhere(
                                [
                                    '=',
                                    'depth',
                                    Constant::STATUS_ROOT,
                                ],
                            )
                            ->one()
            ;
            
            $form = new ModelEditForm($root);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->editRoot($root->site_id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $root->id,
                        ],
                    );
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: ' . static::SERVICE_INDEX . 'editRoot',
                        $e,
                    );
                }
            }
            
            return $this->render(
                '@app/views/layouts/templates/updateRoot',
                [
                    'model'    => $form,
                    'root'     => $root,
                    'actionId' => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix' => Prefix::page(),
                    'label' => Label::page(),
                ],
            );
            
        }
        
        
        /**
         * @throws Throwable
         */
        public function actionGetModel($id): array
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $model = Page::findOne($id);
            
            if (!$model) {
                return ['error' => 'Page not found'];
            }
            return [
                'id'          => $model->id,
                'image'       => ImageHelper::getModelImageSource($model, 6),
                'name'        => $model->name,
                'title'       => $model->title,
                'description' => FormatHelper::asHtml($model->description),
                'text'        => FormatHelper::asHtml($model->text),
                'status'      => StatusHelper::statusBadgeLabel($model->status),
                'editUrl' => Url::to(['/content/page/view', 'id' => ModelHelper::getId($model),]),
            ];
        }
    }
