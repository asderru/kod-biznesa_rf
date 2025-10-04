<?php
    
    namespace backend\controllers\admin;
    
    use core\helpers\ParentHelper;
    use JetBrains\PhpStorm\ArrayShape;
    use Yii;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;
    
    class ViewController extends Controller
    {
        private const int    TEXT_TYPE    = 99;
        private const string MODEL_LABEL  = 'Просмотр';
        private const string MODEL_PREFIX = 'view';
        private const string ACTION_INDEX = 'Admin_ViewController_';
        
        public $layout = '@app/views/layouts/express';
        
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
         * Displays a single Edit model.
         * @param ?int $textType
         * @param ?int $id
         * @return Response|string
         * @action Admin_ViewController_actionView
         */
        public function actionView(?int $textType = null, ?int $id = null): Response|string
        {
            $actionId = static::ACTION_INDEX . 'actionView';
            
            // Проверка наличия и валидности textType и id
            $validTextType = filter_var($textType, FILTER_VALIDATE_INT);
            $validId       = filter_var($id, FILTER_VALIDATE_INT);
            
            if ($validTextType === false || $validId === false) {
                Yii::$app->session->setFlash('warning', 'Неверные параметры запроса.');
                return $this->redirect(['index']);
            }
            
            // Преобразуем в int, так как мы уже проверили их валидность
            $textType = (int)$validTextType;
            $id       = (int)$validId;
            
            $model = ParentHelper::getModel($textType, $id);
            
            if (!$model) {
                Yii::$app->session->setFlash('warning', 'Модель #' . $id . ' не найдена!');
                return $this->redirect(['index']);
            }
            
            return $this->render(
                '@app/views/admin/view/view',
                [
                    'model' => $model,
                    'actionId'  => $actionId,
                    'textType' => static::TEXT_TYPE,
                    'prefix'   => static::MODEL_PREFIX,
                    'label'    => static::MODEL_LABEL,
                ],
            );
        }
        
    }
