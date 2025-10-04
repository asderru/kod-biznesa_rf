<?php
    
    namespace backend\controllers\admin;
    
    use core\edit\forms\ModelEditForm;
    use core\edit\repositories\ModelsRepository;
    use core\edit\traits\RedirectControllerTrait;
    use core\edit\useCases\Admin\ModelManageService;
    use core\helpers\ParentHelper;
    use core\helpers\PrintHelper;
    use core\helpers\types\TypeHelper;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\db\Exception;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class UpdateController extends Controller
    {
        use RedirectControllerTrait;
        
        public $layout = '@app/views/layouts/content.php';
        
        private const int    TEXT_TYPE    = 99;
        private const string MODEL_LABEL  = 'Редактирование';
        private const string MODEL_PREFIX = 'update';
        private const string ACTION_INDEX = 'Admin_UpdateController_';
        
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
        
        private ModelManageService $service;
        
        public function __construct(
            $id,
            $module,
            ModelManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
        }
        
        /**
         * Displays a single Edit model.
         * @param ?int $textType
         * @param ?int $id
         * @param ?int $editId
         * @return Response|string
         * @throws \yii\base\Exception
         * @throws \Exception
         * @action Admin_UpdateController_actionView
         */
        public function actionView(?int $textType = null, ?int $id = null, ?int $editId = 0): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            // Проверка наличия и валидности textType и id
            $validTextType = filter_var($textType, FILTER_VALIDATE_INT);
            $validId       = filter_var($id, FILTER_VALIDATE_INT);
            
            if ($validTextType === false || $validId === false) {
                Yii::$app->session->setFlash('warning', 'Неверные параметры запроса.');
                return $this->redirect(['site/index']);
            }
            
            // Преобразуем в int, так как мы уже проверили их валидность
            $textType = (int)$validTextType;
            $id       = (int)$validId;
            
            $parent = ParentHelper::getModel($textType, $id);
            
            if (!$parent) {
                Yii::$app->session->setFlash('warning', 'Модель #' . $id . ' не найдена!');
                return $this->redirect(['site/index']);
            }
            
            $form = new ModelEditForm($parent);
            
            if (
                !$form->load(Yii::$app->request->post())
            ) {
                try {
                    self::startEditTime(
                        $parent,
                        static::TEXT_TYPE,
                    );
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: self::startEditTime',
                        $e,
                    );
                }
            }
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    if ($this->service->edit($textType, $id, $form)) {
                        self::finishEditTime(static::TEXT_TYPE, $parent->id, $form->text);
                        Yii::$app->session->setFlash('success', 'Изменения внесены в базу!');
                        $action = Yii::$app->request->post('action');
                        
                        
                        if ($action === 'return') {
                            return $this->redirect([
                                $parent->getViewUrl(),
                            ],
                            );
                        }
                        
                        if ($action === 'next') {
                            return $parent->nextModel
                                ? $this->redirect([
                                    'view',
                                    'textType' => $parent::TEXT_TYPE,
                                    'id'       => $parent->nextModel->id,
                                    'editId'   => $editId,
                                ])
                                : $this->redirect([$parent->getEditPath() . '/index']);
                        }
                    }
                    else {
                        Yii::$app->session->setFlash('warning', 'Не удалось сохранить изменения.');
                    }
                }
                catch (NotFoundHttpException $e) {
                    Yii::$app->session->setFlash('danger', 'Модель не найдена: ' . $e->getMessage());
                }
                catch (Exception $e) {
                    Yii::$app->session->setFlash('danger', 'Ошибка базы данных: ' . $e->getMessage());
                }
                catch (\yii\base\Exception $e) {
                    Yii::$app->session->setFlash('danger', 'Произошла ошибка: ' . $e->getMessage());
                }
                catch (Throwable $e) {
                    Yii::$app->session->setFlash('danger', 'Непредвиденная ошибка: ' . $e->getMessage());
                }
            }
            
            return $this->render(
                '@app/views/admin/update/view' . $editId,
                [
                    'model'     => $form,
                    'parent'    => $parent,
                    'faqs'      => $parent->faqs ?? null,
                    'footnotes' => $parent->footnotes ?? null,
                    'reviews'   => $parent->reviews ?? null,
                    'galleries' => $parent->galleries ?? null,
                    'parentTextType' => $textType,
                    'parentId'       => $id,
                    'editId'         => $editId,
                    'actionId'       => $actionId,
                    'textType'       => static::TEXT_TYPE,
                    'prefix'         => static::MODEL_PREFIX,
                    'label'          => static::MODEL_LABEL,
                ],
            );
        }
        
        /**
         * Displays a single Edit model.
         * @param ?int $textType
         * @param ?int $id
         * @param ?int $editId
         * @return Response|string
         * @action Admin_UpdateController_actionUpdate
         * @throws \yii\base\Exception
         * @throws \Exception
         */
        public function actionUpdate(?int $textType = null, ?int $id = null, ?int $editId = 0): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionUpdate';
            
            // Проверка наличия и валидности textType и id
            $validTextType = filter_var($textType, FILTER_VALIDATE_INT);
            $validId       = filter_var($id, FILTER_VALIDATE_INT);
            
            if ($validTextType === false || $validId === false) {
                Yii::$app->session->setFlash('warning', 'Неверные параметры запроса.');
                return $this->redirect(['site/index']);
            }
            
            // Преобразуем в int, так как мы уже проверили их валидность
            $textType         = (int)$validTextType;
            $id               = (int)$validId;
            $isSimpleInstance = TypeHelper::isSimpleInstance($textType);
            if ($isSimpleInstance) {
                Yii::$app->session->setFlash(
                    'warning', 'Тип модели #' . TypeHelper::getName(
                        $textType, null, false,
                        false,
                    ) . ' не может быть отредактирован!',
                );
                return $this->redirect(['site/index']);
            }
            
            $parent = ParentHelper::getModel($textType, $id);
            $siblings = ModelsRepository::getModelsArray($parent);
            
            if (!$parent) {
                Yii::$app->session->setFlash('warning', 'Модель #' . $id . ' не найдена!');
                return $this->redirect(['site/index']);
            }
            
            $form = new ModelEditForm($parent);
            $editorName = TypeHelper::getEditorName($editId);
            
            if (
                !$form->load(Yii::$app->request->post())
            ) {
                try {
                    self::startEditTime(
                        $parent,
                        static::TEXT_TYPE,
                    );
                }
                catch (Exception $e) {
                    PrintHelper::exception(
                        $actionId,
                        'Проблема: self::startEditTime',
                        $e,
                    );
                }
            }
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    if ($this->service->edit($textType, $id, $form)) {
                        self::finishEditTime(static::TEXT_TYPE, $parent->id, $form->text);
                        Yii::$app->session->setFlash('success', 'Изменения внесены в базу!');
                        $action = Yii::$app->request->post('action');
                        
                        if ($action === 'return') {
                            return $this->redirect([
                                $parent->getViewUrl(),
                            ],
                            );
                        }
                        
                        if ($action === 'next') {
                            return $parent->nextModel
                                ? $this->redirect([
                                    'update',
                                    'textType' => $parent::TEXT_TYPE,
                                    'id'       => $parent->nextModel->id,
                                    'editId'   => $editId,
                                ])
                                : $this->redirect([$parent->getEditPath() . '/index']);
                        }
                    }
                    else {
                        Yii::$app->session->setFlash('warning', 'Не удалось сохранить изменения.');
                    }
                }
                
                catch (NotFoundHttpException $e) {
                    Yii::$app->session->setFlash('danger', 'Модель не найдена: ' . $e->getMessage());
                }
                catch (Exception $e) {
                    Yii::$app->session->setFlash('danger', 'Ошибка базы данных: ' . $e->getMessage());
                }
                catch (\yii\base\Exception $e) {
                    Yii::$app->session->setFlash('danger', 'Произошла ошибка: ' . $e->getMessage());
                }
                catch (Throwable $e) {
                    Yii::$app->session->setFlash('danger', 'Непредвиденная ошибка: ' . $e->getMessage());
                }
            }
            
            return $this->render(
                '@app/views/admin/update/update',
                [
                    'model'      => $form,
                    'parent'     => $parent,
                    'siblings'   => $siblings,
                    'textType'   => $textType,
                    'editId'     => $editId,
                    'editorName' => $editorName,
                    'actionId'   => $actionId,
                ],
            );
        }
        
    }
